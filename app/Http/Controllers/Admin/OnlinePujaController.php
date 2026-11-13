<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\OnlinePuja;
use App\Models\OnlinePujaCategory;
use App\Models\OnlinePujaPackage;
use App\Models\OnlinePujaFaq;
use App\Models\OnlinePujaOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OnlinePujaController extends Controller

{

    
public function index()
{
    $user = Auth::user();

    $query = OnlinePuja::with([
        'category',
        'creator',
        'updater'
    ]);

    if ($user->hasRole('Pandit')) {

        // Pandit: Only show records created by logged-in user
        $query->where('created_by', $user->id);

    } elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {

        // Admin and Super Admin: Show all records
        // No filter required

    }

    $pujas = $query->latest()->get();

    return view('admin.online-pujas.index', compact('pujas'));
}

    public function bookings($id)
    {
        $user = Auth::user();

        // Find selected puja
        $pujaQuery = OnlinePuja::query();

        // Restrict non-admin users to their own pujas
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            $pujaQuery->where('created_by', $user->id);
        }

        $puja = $pujaQuery->findOrFail($id);

        // Get bookings for this particular puja
        $bookings = OnlinePujaOrder::with(['package'])
            ->where('online_puja_id', $puja->id)
            ->latest()
            ->get();

        return view(
            'admin.online-pujas.bookings',
            compact('puja', 'bookings')
        );
    }
    public function create()
    {
        $categories = OnlinePujaCategory::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('admin.online-pujas.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $contentFields = [
            'puja_significance',
            'puja_benefits',
            'puja_procedure',
            'what_you_will_receive',
        ];
        $connection = DB::connection((new OnlinePuja())->getConnectionName());
        // Validation and explicit Summernote copying.
        $validated = $this->validatePuja($request);
        $validated = $this->copyPujaContentFields($request, $validated);
        $uploadedFiles = [];
        $connection->beginTransaction();
        try {
            $validated['slug'] = $this->generateSlug($request->input('title'));
            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('online-pujas', 'public');
                $validated['banner_image'] = $path;
                $uploadedFiles[] = $path;
            }
            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')->store('online-pujas', 'public');
                $validated['thumbnail_image'] = $path;
                $uploadedFiles[] = $path;
            }
            $gallery = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    if (!$image) {
                        continue;
                    }
                    $path = $image->store('online-pujas/gallery', 'public');
                    $gallery[] = $path;
                    $uploadedFiles[] = $path;
                }
            }
            $validated['gallery_images'] = $gallery;
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_popular'] = $request->boolean('is_popular');
            $validated['whatsapp_delivery_enabled'] = $request->boolean('whatsapp_delivery_enabled');
            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();
            unset($validated['packages'], $validated['faq']);
            // Use fill + save so we can log exactly what Eloquent will persist.
            $onlinePuja = new OnlinePuja();
            $onlinePuja->fill($validated);
            $saveResult = $onlinePuja->save();
            $databaseRow = $connection->table('online_pujas')
                ->where('id', $onlinePuja->id)
                ->first($contentFields);
            foreach ($request->input('packages', []) as $package) {
                OnlinePujaPackage::create([
                    'online_puja_id' => $onlinePuja->id,
                    'package_name' => $package['package_name'],
                    'devotees_count' => $package['devotees_count'],
                    'price' => $package['price'],
                    'description' => $package['description'] ?? null,
                    'is_recommended' => $package['is_recommended'] ?? 0,
                    'status' => $package['status'] ?? 1,
                    'sort_order' => $package['sort_order'] ?? 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
            foreach ($request->input('faq', []) as $faq) {
                OnlinePujaFaq::create([
                    'online_puja_id' => $onlinePuja->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
            $connection->commit();
            return redirect()
                ->route('online-pujas.index')
                ->with('success', 'Online Puja created successfully.');

        } catch (\Throwable $e) {
            if ($connection->transactionLevel() > 0) {
                $connection->rollBack();
            }

            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }
            report($e);
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong while creating Online Puja. Please check storage/logs/laravel.log.'
                );
        }
    }
    public function show(OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);
        $online_puja->load([
            'category',
            'creator',
            'updater',
            'packages',
            'faqs',
        ]);
        return view('admin.online-pujas.show', compact('online_puja'));
    }

    public function edit(OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);
        $categories = OnlinePujaCategory::where('status', 1)
            ->orderBy('name')
            ->get();
        $online_puja->load(['packages', 'faqs']);
        return view(
            'admin.online-pujas.edit',
            compact('online_puja', 'categories')
        );
    }

    public function update(Request $request, OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);
        $pujaId = $online_puja->id;
        $connection = DB::connection($online_puja->getConnectionName());
        $contentFields = [
            'puja_significance',
            'puja_benefits',
            'puja_procedure',
            'what_you_will_receive',
        ];
        $validated = $this->validatePuja($request, $pujaId);
        $validated = $this->copyPujaContentFields($request, $validated);
        $uploadedFiles = [];
        $oldFiles = [];
        $connection->beginTransaction();
        try {
            if ($request->input('title') !== $online_puja->title) {
                $validated['slug'] = $this->generateSlug(
                    $request->input('title'),
                    $pujaId
                );
            } else {
                $validated['slug'] = $online_puja->slug;
            }
            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('online-pujas', 'public');
                $uploadedFiles[] = $path;
                if ($online_puja->banner_image) {
                    $oldFiles[] = $online_puja->banner_image;
                }
                $validated['banner_image'] = $path;
            }
            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')->store('online-pujas', 'public');
                $uploadedFiles[] = $path;
                if ($online_puja->thumbnail_image) {
                    $oldFiles[] = $online_puja->thumbnail_image;
                }
                $validated['thumbnail_image'] = $path;
            }
            $gallery = $online_puja->gallery_images ?? [];
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true) ?: [];
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            foreach ($request->input('remove_gallery_images', []) as $removeImage) {
                if (in_array($removeImage, $gallery, true)) {
                    $oldFiles[] = $removeImage;
                    $gallery = array_values(array_filter(
                        $gallery,
                        function ($image) use ($removeImage) {
                            return $image !== $removeImage;
                        }
                    ));
                }
            }
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    if (!$image) {
                        continue;
                    }
                    $path = $image->store('online-pujas/gallery', 'public');
                    $gallery[] = $path;
                    $uploadedFiles[] = $path;
                }
            }
            $validated['gallery_images'] = array_values($gallery);
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_popular'] = $request->boolean('is_popular');
            $validated['whatsapp_delivery_enabled'] =
                $request->boolean('whatsapp_delivery_enabled');
            $validated['updated_by'] = Auth::id();
            unset(
                $validated['packages'],
                $validated['faq'],
                $validated['remove_gallery_images']
            );
            $online_puja->fill($validated);
            $saveResult = $online_puja->save();
            $databaseRow = $connection->table('online_pujas')
                ->where('id', $pujaId)
                ->first($contentFields);
            OnlinePujaPackage::where('online_puja_id', $pujaId)->delete();
            foreach ($request->input('packages', []) as $package) {
                OnlinePujaPackage::create([
                    'online_puja_id' => $pujaId,
                    'package_name' => $package['package_name'],
                    'devotees_count' => $package['devotees_count'],
                    'price' => $package['price'],
                    'description' => $package['description'] ?? null,
                    'is_recommended' => $package['is_recommended'] ?? 0,
                    'status' => $package['status'] ?? 1,
                    'sort_order' => $package['sort_order'] ?? 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
            OnlinePujaFaq::where('online_puja_id', $pujaId)->delete();
            foreach ($request->input('faq', []) as $faq) {
                OnlinePujaFaq::create([
                    'online_puja_id' => $pujaId,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
            $connection->commit();
            foreach (array_unique($oldFiles) as $file) {
                if ($file) {
                    Storage::disk('public')->delete($file);
                }
            }
            return redirect()
                ->route('online-pujas.index')
                ->with('success', 'Online Puja updated successfully.');
        } catch (\Throwable $e) {
            if ($connection->transactionLevel() > 0) {
                $connection->rollBack();
            }
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }
            report($e);
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong while updating Online Puja. Please check storage/logs/laravel.log.'
                );
        }
    }
    public function destroy(OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);
        $online_puja->update([
            'status' => 0,
            'updated_by' => Auth::id(),
        ]);
        return redirect()
            ->route('online-pujas.index')
            ->with('success', 'Online Puja deactivated successfully.');
    }
    private function copyPujaContentFields(Request $request, array $validated): array
    {
        $fields = [
            'puja_significance',
            'puja_benefits',
            'puja_procedure',
            'what_you_will_receive',
        ];
        foreach ($fields as $field) {
            $validated[$field] = $this->normalizeSummernoteContent(
                $request->input($field)
            );
        }
        return $validated;

    }
    private function normalizeSummernoteContent($content): ?string
    {
        if ($content === null) {
            return null;
        }
        $content = trim((string) $content);
        if ($content === '') {
            return null;
        }
        $hasMedia = preg_match(
            '/(?:<(?:img|video|audio|iframe|table|svg|object|embed)\b)/i',
            $content
        );
        $plainText = html_entity_decode(
            strip_tags($content),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
        $plainText = preg_replace(
            '/[\s\p{Z}\x{00A0}\x{200B}\x{FEFF}]+/u',
            '',
            $plainText
        );
        if (!$hasMedia && $plainText === '') {
            return null;
        }
        return $content;
    }

    private function summernoteDebugSummary(Request $request, array $fields): array
    {
        $summary = [];
        foreach ($fields as $field) {
            $value = $request->input($field);
            $summary[$field] = [
                'present' => $request->exists($field),
                'is_null' => $value === null,
                'length' => is_string($value) ? strlen($value) : null,
                'preview' => is_string($value) ? mb_substr($value, 0, 180) : null,
            ];
        }
        return $summary;
    }

    private function arrayContentDebugSummary(array $data, array $fields): array
    {
        $summary = [];
        foreach ($fields as $field) {
            $value = $data[$field] ?? null;
            $summary[$field] = [
                'exists' => array_key_exists($field, $data),
                'is_null' => $value === null,
                'length' => is_string($value) ? strlen($value) : null,
                'preview' => is_string($value) ? mb_substr($value, 0, 180) : null,
            ];
        }
        return $summary;
    }
    private function modelContentDebugSummary(OnlinePuja $model, array $fields): array
    {
        $summary = [];
        foreach ($fields as $field) {
            $value = $model->getAttribute($field);
            $summary[$field] = [
                'is_null' => $value === null,
                'length' => is_string($value) ? strlen($value) : null,
                'preview' => is_string($value) ? mb_substr($value, 0, 180) : null,
            ];
        }
        return $summary;
    }

    private function rowContentDebugSummary($row, array $fields): array
    {
        $summary = [];
        foreach ($fields as $field) {
            $value = $row ? ($row->{$field} ?? null) : null;
            $summary[$field] = [
                'is_null' => $value === null,
                'length' => is_string($value) ? strlen($value) : null,
                'preview' => is_string($value) ? mb_substr($value, 0, 180) : null,
            ];
        }
        return $summary;
    }
    private function authorizePujaAccess(OnlinePuja $online_puja)
    {
        $user = Auth::user();
        if (
            !in_array($user->role, ['admin', 'super_admin']) &&
            $online_puja->created_by != $user->id
        ) {
            abort(403, 'You are not authorized to access this Online Puja.');
        }
    }
    private function validatePuja(Request $request, $id = null)
    {
        return $request->validate([
            'online_puja_category_id' => [
                'required',
                'exists:online_puja_categories,id',
            ],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
            'short_description' => ['nullable', 'string'],
            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'thumbnail_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string'],
            'puja_significance' => ['nullable', 'string'],
            'puja_benefits' => ['nullable', 'string'],
            'puja_procedure' => ['nullable', 'string'],
            'what_you_will_receive' => ['nullable', 'string'],
            'temple_name' => ['nullable', 'string', 'max:255'],
            'temple_location' => ['nullable', 'string', 'max:255'],
            'temple_city' => ['nullable', 'string', 'max:255'],
            'temple_state' => ['nullable', 'string', 'max:255'],
            'temple_address' => ['nullable', 'string'],
            'temple_description' => ['nullable', 'string'],
            'puja_datetime' => ['nullable', 'date'],
            'puja_day' => ['nullable', 'string', 'max:255'],
            'tithi' => ['nullable', 'string', 'max:255'],
            'muhurat' => ['nullable', 'string', 'max:255'],
            'muhurat_end_at' => ['nullable', 'date'],
            'booking_start_at' => ['nullable', 'date'],
            'booking_end_at' => ['nullable', 'date'],
            'total_seat' => ['nullable', 'integer', 'min:0'],
            'booked_seat' => ['nullable', 'integer', 'min:0'],
            'video_delivery_hours' => ['nullable', 'integer', 'min:0'],
            'whatsapp_delivery_enabled' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'packages' => ['nullable', 'array'],
            'packages.*.package_name' => ['required', 'string', 'max:255'],
            'packages.*.devotees_count' => ['required', 'integer', 'min:1'],
            'packages.*.price' => ['required', 'numeric', 'min:0'],
            'packages.*.description' => ['nullable', 'string'],
            'packages.*.is_recommended' => ['nullable', 'boolean'],
            'packages.*.status' => ['required', 'boolean'],
            'packages.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'faq' => ['nullable', 'array'],
            'faq.*.question' => ['required', 'string', 'max:255'],
            'faq.*.answer' => ['required', 'string'],
        ]);
    }

    private function generateSlug($title, $id = null)
    {
        $originalSlug = Str::slug($title);
        if (!$originalSlug) {
            $originalSlug = 'online-puja';
        }
        $originalSlug = Str::limit($originalSlug, 230, '');
        $slug = $originalSlug;
        $count = 1;
        while (
            OnlinePuja::where('slug', $slug)
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }
}