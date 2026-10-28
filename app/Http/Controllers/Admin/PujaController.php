<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\PujaFaq;
use App\Models\PujaPackage;
use App\Models\Temple;
use App\Models\Deity;
use App\Models\PujaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
class PujaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Puja::with(['temple','deity','pujaCategory','faqs','packages',]);
        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
            $query->where('created_by', $user->id);
        }
        $pujas = $query->latest()->get();
        return view('admin.pujas.index', compact('pujas'));
    }
    public function create()
    {
        $user = Auth::user();
        $temples = Temple::where('created_by', $user->id)->where('status', 1)->latest()->get();
        $selectedTempleId = $temples->first()?->id;
        $deities = Deity::where('status', 1)->latest()->get();
        $pujaCategories = PujaCategory::where('status', 1)->orderBy('sort_order')->get();
        $templeProducts = DB::table('temple_products')->whereIn('temple_id', $temples->pluck('id'))->where('status', 1)->orderBy('name')->get(['id', 'temple_id', 'name', 'price', 'qty', 'unit']);
        return view('admin.pujas.create', compact('temples','deities','pujaCategories','selectedTempleId','templeProducts'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_category_id' => 'required|exists:puja_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'ritual_details' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_online' => 'required|boolean',
            'is_offline' => 'required|boolean',
            'status' => 'nullable|boolean',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required|string|max:500',
            'faq.*.answer' => 'required|string',
            'packages' => 'nullable|array',
            'packages.*.name' => 'required|string|max:150',
            'packages.*.person_count' => 'required|integer|min:1',
            'packages.*.pandit_price' => 'required|numeric|min:0',
            'packages.*.products' => 'nullable|array',
            'packages.*.products.*.product_id' => 'required|integer|min:1',
            'packages.*.products.*.quantity' => 'required|integer|min:1',
            'packages.*.discount_price' => 'nullable|numeric|min:0',
            'packages.*.sort_order' => 'nullable|integer|min:0',
            'packages.*.short_description' => 'nullable|string|max:500',
            'packages.*.description' => 'nullable|string',
            'packages.*.is_featured' => 'nullable|boolean',
            'packages.*.package_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        DB::beginTransaction();

        $image = null;
        $bannerImage = null;
        $uploadedPackageImages = [];

        try {
            $lastPuja = Puja::latest('id')->first();

            if ($lastPuja && $lastPuja->puja_code) {
                $lastNumber = (int) str_replace(
                    'PUJA-',
                    '',
                    $lastPuja->puja_code
                );

                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $pujaCode = 'PUJA-' . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );

            if ($request->hasFile('image')) {
                $image = $request->file('image')
                    ->store('puja_image', 'public');
            }

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')
                    ->store('puja_banner', 'public');
            }

            $puja = Puja::create([
                'temple_id' => $validated['temple_id'],
                'deity_id' => $validated['deity_id'],
                'puja_category_id' => $validated['puja_category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'puja_code' => $pujaCode,
                'description' => $this->getNullableText($request, 'description'),
                'benefits' => $this->getNullableText($request, 'benefits'),
                'ritual_details' => $this->getNullableText($request, 'ritual_details'),
                'duration' => $validated['duration'] ?? null,
                'image' => $image,
                'banner_image' => $bannerImage,
                'is_online' => $validated['is_online'],
                'is_offline' => $validated['is_offline'],
                'status' => $validated['status'] ?? 1,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['packages'] ?? [] as $index => $package) {
                $packageImage = null;

                if ($request->hasFile("packages.$index.package_image")) {
                    $packageImage = $request
                        ->file("packages.$index.package_image")
                        ->store('puja_packages', 'public');

                    $uploadedPackageImages[] = $packageImage;
                }

                $packageProducts = $this->validateAndPriceProducts((int) $validated['temple_id'], $package['products'] ?? []);
                $panditPrice = (float) $package['pandit_price'];
                $finalPrice = round($panditPrice + collect($packageProducts)->sum(fn ($item) => $item['line_total']), 2);
                $discountPrice = isset($package['discount_price']) && $package['discount_price'] !== '' ? (float) $package['discount_price'] : null;
                if ($discountPrice !== null && $discountPrice > $finalPrice) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'packages' => 'Discount price cannot be greater than the calculated final package price.',
                    ]);
                }
                PujaPackage::create([
                    'puja_id' => $puja->id,
                    'name' => $package['name'],
                    'slug' => $this->generatePackageSlug(
                        $package['name'],
                        $puja->id
                    ),
                    'person_count' => $package['person_count'],
                    'price' => $finalPrice,
                    'pandit_price' => $panditPrice,
                    'products' => $packageProducts,
                    'discount_price' => $discountPrice,
                    'package_image' => $packageImage,
                    'short_description' => $package['short_description'] ?? null,
                    'description' => $package['description'] ?? null,
                    'is_featured' => !empty($package['is_featured']),
                    'sort_order' => $package['sort_order'] ?? $index,
                    'status' => 1,
                ]);
            }

            foreach ($validated['faq'] ?? [] as $index => $faq) {
                PujaFaq::create([
                    'puja_id' => $puja->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $index,
                    'status' => 1,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pujas.index')
                ->with('Success', 'Puja Created Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($image && Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }

            if ($bannerImage && Storage::disk('public')->exists($bannerImage)) {
                Storage::disk('public')->delete($bannerImage);
            }

            foreach ($uploadedPackageImages as $packageImage) {
                if (Storage::disk('public')->exists($packageImage)) {
                    Storage::disk('public')->delete($packageImage);
                }
            }

            if ($e instanceof ValidationException) {
                throw $e;
            }

            report($e);

            return back()
                ->withInput()
                ->with('Error', app()->environment('local')
                    ? 'Unable to create Puja: ' . $e->getMessage()
                    : 'Unable to create Puja. Please check the Laravel log for details.');
        }
    }

    public function edit(Puja $puja)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['Super Admin', 'Admin']) && $puja->created_by != $user->id) {
            abort(403);
        }
        $puja->load([
            'faqs' => function ($query) 
            {
                $query->orderBy('sort_order');
            },
            'packages' => function ($query) 
            {
                $query->orderBy('sort_order');
            },
        ]);
        $templesQuery = Temple::where('status', 1);
        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) 
        {
            $templesQuery->where('created_by', $user->id);
        }
        $temples = $templesQuery->latest()->get();
        $deities = Deity::where('status', 1)->latest()->get();
        $pujaCategories = PujaCategory::where('status', 1)->orderBy('sort_order')->get();
        $templeProducts = DB::table('temple_products')->whereIn('temple_id', $temples->pluck('id'))->where('status', 1)->orderBy('name')->get(['id', 'temple_id', 'name', 'price', 'qty', 'unit']);
        return view('admin.pujas.edit', compact('puja','temples','deities','pujaCategories','templeProducts'));
    }
    public function update(Request $request, Puja $puja)
    {
        $validated = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_category_id' => 'required|exists:puja_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'ritual_details' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_online' => 'required|boolean',
            'is_offline' => 'required|boolean',
            'status' => 'required|boolean',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required|string|max:500',
            'faq.*.answer' => 'required|string',
            'packages' => 'nullable|array',
            'packages.*.name' => 'required|string|max:150',
            'packages.*.person_count' => 'required|integer|min:1',
            'packages.*.pandit_price' => 'required|numeric|min:0',
            'packages.*.products' => 'nullable|array',
            'packages.*.products.*.product_id' => 'required|integer|min:1',
            'packages.*.products.*.quantity' => 'required|integer|min:1',
            'packages.*.discount_price' => 'nullable|numeric|min:0',
            'packages.*.sort_order' => 'nullable|integer|min:0',
            'packages.*.short_description' => 'nullable|string|max:500',
            'packages.*.description' => 'nullable|string',
            'packages.*.is_featured' => 'nullable|boolean',
            'packages.*.package_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'packages.*.existing_package_image' => 'nullable|string|max:500',
        ]);
        $description = $request->input('description');
        $benefits = $request->input('benefits');
        $ritualDetails = $request->input('ritual_details');
        $description = $description !== null && trim($description) !== ''
            ? trim($description)
            : null;
        $benefits = $benefits !== null && trim($benefits) !== ''
            ? trim($benefits)
            : null;
        $ritualDetails = $ritualDetails !== null && trim($ritualDetails) !== ''
            ? trim($ritualDetails)
            : null;
        DB::beginTransaction();
        $oldImage = $puja->image;
        $oldBannerImage = $puja->banner_image;
        $newImage = $oldImage;
        $newBannerImage = $oldBannerImage;
        $newPackageImages = [];
        try {
            if ($request->hasFile('image')) {
                $newImage = $request->file('image')->store('puja_image', 'public');
            }
            if ($request->hasFile('banner_image')) {
                $newBannerImage = $request->file('banner_image')->store('puja_banner', 'public');
            }
            $puja->update([
                'temple_id' => $validated['temple_id'],
                'deity_id' => $validated['deity_id'],
                'puja_category_id' => $validated['puja_category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name'], $puja->id),
                'puja_code' => $puja->puja_code,
                'description' => $description,
                'benefits' => $benefits,
                'ritual_details' => $ritualDetails,
                'duration' => $validated['duration'] ?? null,
                'image' => $newImage,
                'banner_image' => $newBannerImage,
                'is_online' => $validated['is_online'],
                'is_offline' => $validated['is_offline'],
                'status' => $validated['status'],
                'updated_by' => auth()->id(),

            ]);
            $oldPackages = PujaPackage::where('puja_id', $puja->id)->get();
            $oldPackageImages = $oldPackages->pluck('package_image')->filter()->unique()->values()->all();
            $usedPackageImages = [];
            PujaPackage::where('puja_id', $puja->id)->delete();
            foreach ($validated['packages'] ?? [] as $index => $package) {
                $packageImage = $package['existing_package_image'] ?? null;
                if ($request->hasFile("packages.$index.package_image")) {
                    $packageImage = $request
                        ->file("packages.$index.package_image")
                        ->store('puja_packages', 'public');
                    $newPackageImages[] = $packageImage;
                }
                if ($packageImage) {
                    $usedPackageImages[] = $packageImage;
                }
                $packageProducts = $this->validateAndPriceProducts((int) $validated['temple_id'], $package['products'] ?? []);
                $panditPrice = (float) $package['pandit_price'];
                $finalPrice = round($panditPrice + collect($packageProducts)->sum(fn ($item) => $item['line_total']), 2);
                $discountPrice = isset($package['discount_price']) && $package['discount_price'] !== '' ? (float) $package['discount_price'] : null;
                if ($discountPrice !== null && $discountPrice > $finalPrice) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'packages' => 'Discount price cannot be greater than the calculated final package price.',
                    ]);
                }
                PujaPackage::create([
                    'puja_id' => $puja->id,
                    'name' => $package['name'],
                    'slug' => $this->generatePackageSlug($package['name'], $puja->id),
                    'person_count' => $package['person_count'],
                    'price' => $finalPrice,
                    'pandit_price' => $panditPrice,
                    'products' => $packageProducts,
                    'discount_price' => $discountPrice,
                    'package_image' => $packageImage,
                    'short_description' => $package['short_description'] ?? null,
                    'description' => $package['description'] ?? null,
                    'is_featured' => !empty($package['is_featured']),
                    'sort_order' => $package['sort_order'] ?? $index,
                    'status' => 1,
                ]);
            }
            PujaFaq::where('puja_id', $puja->id)->delete();
            foreach ($validated['faq'] ?? [] as $index => $faq) {
                PujaFaq::create([
                    'puja_id' => $puja->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $index,
                    'status' => 1,
                ]);
            }
            DB::commit();
            foreach (array_diff($oldPackageImages ?? [], $usedPackageImages ?? []) as $oldPackageImage) {
                if (Storage::disk('public')->exists($oldPackageImage)) {
                    Storage::disk('public')->delete($oldPackageImage);
                }
            }
            if ($oldImage && $newImage !== $oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            if ($oldBannerImage && $newBannerImage !== $oldBannerImage && Storage::disk('public')->exists($oldBannerImage)) {
                Storage::disk('public')->delete($oldBannerImage);
            }
            return redirect()
                ->route('pujas.index')
                ->with('Success', 'Puja Updated Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($newImage && $newImage !== $oldImage && Storage::disk('public')->exists($newImage)) {
                Storage::disk('public')->delete($newImage);
            }
            if ($newBannerImage && $newBannerImage !== $oldBannerImage && Storage::disk('public')->exists($newBannerImage)) {
                Storage::disk('public')->delete($newBannerImage);
            }
            foreach ($newPackageImages as $packageImage) {
                if (Storage::disk('public')->exists($packageImage)) {
                    Storage::disk('public')->delete($packageImage);
                }
            }
            if ($e instanceof ValidationException) {
                throw $e;
            }
            report($e);
            return back()
                ->withInput()
                ->with('Error', 'Unable to update Puja. ' . $e->getMessage());
        }
    }
    public function destroy(Puja $puja)
    {
        $puja->update([
            'status' => 0,
        ]);
        return redirect()
            ->route('pujas.index')
            ->with('Success', 'Puja Deleted Successfully');
    }

    private function validateAndPriceProducts(int $templeId, array $items): array
    {
        $productIds = collect($items)->pluck('product_id')->map(fn ($id) => (int) $id)->filter()->unique()->values();
        if ($productIds->isEmpty()) {
            return [];
        }
        $products = DB::table('temple_products')
            ->where('temple_id', $templeId)
            ->where('status', 1)
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
        if ($products->count() !== $productIds->count()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'packages' => 'One or more selected products are invalid or do not belong to the selected temple.',
            ]);
        }
        $pricedItems = [];
        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);
            if (!$productId || $quantity < 1 || !$products->has($productId)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'packages' => 'Please select valid products and quantities for every package.',
                ]);
            }
            $product = $products->get($productId);
            $lineTotal = round((float) $product->price * $quantity, 2);
            $pricedItems[] = [
                'product_id' => (int) $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'unit' => $product->unit,
                'line_total' => $lineTotal,
            ];
        }
        return $pricedItems;
    }

    private function getNullableText(Request $request, string $key): ?string
    {
        $value = $request->input($key);
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);
        if ($slug === '') {
            $slug = 'puja';
        }
        $originalSlug = $slug;
        $counter = 1;
        while (
            Puja::where('slug', $slug)
                ->when(
                    $ignoreId,
                    function ($query) use ($ignoreId) {
                        $query->where('id', '!=', $ignoreId);
                    }
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
    private function generatePackageSlug(
        string $name,
        int $pujaId
    ): string {
        $slug = Str::slug($name);
        if ($slug === '') {
            $slug = 'package';
        }
        $originalSlug = $slug;
        $counter = 1;
        while (PujaPackage::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
}