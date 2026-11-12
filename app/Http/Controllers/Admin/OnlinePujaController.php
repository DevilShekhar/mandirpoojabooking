<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePuja;
use App\Models\OnlinePujaCategory;
use App\Models\OnlinePujaPackage;
use App\Models\OnlinePujaFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            $query->where('created_by', $user->id);
        }

        $pujas = $query->latest()->get();

        return view('admin.online-pujas.index', compact('pujas'));
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
        $validated = $this->validatePuja($request);

        $uploadedFiles = [];

        DB::beginTransaction();

        try {

            // Generate unique slug.
            $validated['slug'] = $this->generateSlug($request->title);

            // Upload banner.
            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')
                    ->store('online-pujas', 'public');

                $validated['banner_image'] = $path;
                $uploadedFiles[] = $path;
            }

            // Upload thumbnail.
            if ($request->hasFile('thumbnail_image')) {
                $path = $request->file('thumbnail_image')
                    ->store('online-pujas', 'public');

                $validated['thumbnail_image'] = $path;
                $uploadedFiles[] = $path;
            }

            // Upload gallery images.
            $gallery = [];

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {

                    $path = $image->store(
                        'online-pujas/gallery',
                        'public'
                    );

                    $gallery[] = $path;
                    $uploadedFiles[] = $path;
                }
            }

            $validated['gallery_images'] = $gallery;

            // Default checkbox values.
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_popular'] = $request->boolean('is_popular');
            $validated['whatsapp_delivery_enabled'] =
                $request->boolean('whatsapp_delivery_enabled');

            // User tracking.
            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            // Remove relationship fields.
            unset(
                $validated['packages'],
                $validated['faq'],
                $validated['gallery_images']
            );

            // Create main Online Puja.
            $onlinePuja = OnlinePuja::create(array_merge(
                $validated,
                ['gallery_images' => $gallery]
            ));

            // Save packages.
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

            // Save FAQs.
            foreach ($request->input('faq', []) as $faq) {

                OnlinePujaFaq::create([
                    'online_puja_id' => $onlinePuja->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('online-pujas.index')
                ->with('success', 'Online Puja created successfully.');

        } catch (\Throwable $e) {

            DB::rollBack();

            // Delete uploaded files after failure.
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            Log::error('Online Puja Store Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong while creating Online Puja. Please check the Laravel log.'
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
            'faqs'
        ]);

        return view('admin.online-pujas.show', compact('online_puja'));
    }

    public function edit(OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);

        $categories = OnlinePujaCategory::where('status', 1)
            ->orderBy('name')
            ->get();

        $online_puja->load([
            'packages',
            'faqs'
        ]);

        return view(
            'admin.online-pujas.edit',
            compact('online_puja', 'categories')
        );
    }

    public function update(Request $request, OnlinePuja $online_puja)
    {
        $this->authorizePujaAccess($online_puja);

        $validated = $this->validatePuja(
            $request,
            $online_puja->id
        );

        $uploadedFiles = [];
        $oldFiles = [];

        DB::beginTransaction();

        try {

            // Generate slug only when title changes.
            if ($request->title === $online_puja->title) {
                $validated['slug'] = $online_puja->slug;
            } else {
                $validated['slug'] = $this->generateSlug(
                    $request->title,
                    $online_puja->id
                );
            }

            // Banner image.
            if ($request->hasFile('banner_image')) {

                if ($online_puja->banner_image) {
                    $oldFiles[] = $online_puja->banner_image;
                }

                $path = $request->file('banner_image')
                    ->store('online-pujas', 'public');

                $validated['banner_image'] = $path;
                $uploadedFiles[] = $path;
            }

            // Thumbnail image.
            if ($request->hasFile('thumbnail_image')) {

                if ($online_puja->thumbnail_image) {
                    $oldFiles[] = $online_puja->thumbnail_image;
                }

                $path = $request->file('thumbnail_image')
                    ->store('online-pujas', 'public');

                $validated['thumbnail_image'] = $path;
                $uploadedFiles[] = $path;
            }

            // Existing gallery.
            $existingGallery = $online_puja->gallery_images ?? [];

            if (is_string($existingGallery)) {
                $existingGallery = json_decode($existingGallery, true) ?? [];
            }

            $existingGallery = is_array($existingGallery)
                ? $existingGallery
                : [];

            // Remove selected images.
            $removeImages = $request->input(
                'remove_gallery_images',
                []
            );

            foreach ($removeImages as $removeImage) {

                if (in_array($removeImage, $existingGallery, true)) {

                    $oldFiles[] = $removeImage;

                    $existingGallery = array_values(
                        array_diff($existingGallery, [$removeImage])
                    );
                }
            }

            // Add new gallery images.
            if ($request->hasFile('gallery_images')) {

                foreach ($request->file('gallery_images') as $image) {

                    $path = $image->store(
                        'online-pujas/gallery',
                        'public'
                    );

                    $existingGallery[] = $path;
                    $uploadedFiles[] = $path;
                }
            }

            $validated['gallery_images'] = array_values($existingGallery);

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

            $online_puja->update($validated);

            // Replace packages.
            OnlinePujaPackage::where(
                'online_puja_id',
                $online_puja->id
            )->delete();

            foreach ($request->input('packages', []) as $package) {

                OnlinePujaPackage::create([
                    'online_puja_id' => $online_puja->id,
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

            // Replace FAQs.
            OnlinePujaFaq::where(
                'online_puja_id',
                $online_puja->id
            )->delete();

            foreach ($request->input('faq', []) as $faq) {

                OnlinePujaFaq::create([
                    'online_puja_id' => $online_puja->id,
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            // Delete old files only after successful commit.
            foreach (array_unique($oldFiles) as $file) {
                if ($file) {
                    Storage::disk('public')->delete($file);
                }
            }

            return redirect()
                ->route('online-pujas.index')
                ->with('success', 'Online Puja updated successfully.');

        } catch (\Throwable $e) {

            DB::rollBack();

            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            Log::error('Online Puja Update Error', [
                'puja_id' => $online_puja->id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    config('app.debug')
                        ? $e->getMessage()
                        : 'Something went wrong while updating Online Puja.'
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

    /**
     * Check user access.
     */
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

    /**
     * Validate Online Puja.
     */
    private function validatePuja(Request $request, $id = null)
    {
        return $request->validate([

            'online_puja_category_id' => [
                'required',
                'exists:online_puja_categories,id'
            ],

            'title' => [
                'required',
                'string',
                'max:255'
            ],

            'status' => [
                'required',
                'in:0,1'
            ],

            'short_description' => ['nullable', 'string'],

            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'thumbnail_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

            'gallery_images' => ['nullable', 'array'],

            'gallery_images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
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

            // Packages.
            'packages' => ['nullable', 'array'],

            'packages.*.package_name' => [
                'required',
                'string',
                'max:255'
            ],

            'packages.*.devotees_count' => [
                'required',
                'integer',
                'min:1'
            ],

            'packages.*.price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'packages.*.description' => ['nullable', 'string'],
            'packages.*.is_recommended' => ['nullable', 'boolean'],

            'packages.*.status' => [
                'required',
                'boolean'
            ],

            'packages.*.sort_order' => [
                'nullable',
                'integer',
                'min:0'
            ],

            // FAQs.
            'faq' => ['nullable', 'array'],

            'faq.*.question' => [
                'required',
                'string',
                'max:255'
            ],

            'faq.*.answer' => [
                'required',
                'string'
            ],
        ]);
    }

    /**
     * Generate globally unique slug.
     */
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