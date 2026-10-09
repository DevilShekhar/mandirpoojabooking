<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sanatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SanatanController extends Controller
{
    public function index()
    {
        $sanatans = Sanatan::latest()->paginate(10);
        return view('admin.sanatans.index', compact('sanatans'));
    }

    public function create()
    {
        return view('admin.sanatans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'program_type' => ['required', 'string', 'max:100'],
            'language' => ['nullable', 'array'],
            'language.*' => ['string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'is_online' => ['required', 'boolean'],
            'is_offline' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        $validated['slug'] = $this->generateSlug($validated['name']);
        $validated['language'] = json_encode(array_values(array_unique($request->input('language', []))));
        $validated['is_online'] = $request->boolean('is_online');
        $validated['is_offline'] = $request->boolean('is_offline');
        $validated['status'] = $request->boolean('status');
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sanatans', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('sanatans/banners', 'public');
        }

        unset($validated['language']);
        $validated['language'] = json_encode(array_values(array_unique($request->input('language', []))));

        Sanatan::create($validated);

        return redirect()->route('admin.sanatan-events.index')->with('success', 'Sanatan event created successfully.');
    }

    public function show(Sanatan $sanatan)
    {
        return view('admin.sanatans.show', compact('sanatan'));
    }

    public function edit(Sanatan $sanatan)
    {
        return view('admin.sanatans.edit', compact('sanatan'));
    }

    public function update(Request $request, Sanatan $sanatan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'program_type' => ['required', 'string', 'max:100'],
            'language' => ['nullable', 'array'],
            'language.*' => ['string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'is_online' => ['required', 'boolean'],
            'is_offline' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        if ($sanatan->name !== $validated['name']) {
            $validated['slug'] = $this->generateSlug($validated['name'], $sanatan->id);
        }

        $validated['language'] = json_encode(array_values(array_unique($request->input('language', []))));
        $validated['is_online'] = $request->boolean('is_online');
        $validated['is_offline'] = $request->boolean('is_offline');
        $validated['status'] = $request->boolean('status');
        $validated['updated_by'] = Auth::id();

        if ($request->hasFile('image')) {
            if ($sanatan->image) {
                Storage::disk('public')->delete($sanatan->image);
            }
            $validated['image'] = $request->file('image')->store('sanatans', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($sanatan->banner_image) {
                Storage::disk('public')->delete($sanatan->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('sanatans/banners', 'public');
        }

        $sanatan->update($validated);

        return redirect()->route('admin.sanatan-events.index')->with('success', 'Sanatan event updated successfully.');
    }

    public function destroy(Sanatan $sanatan)
    {
        $sanatan->update([
            'status' => 0,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.sanatan-events.index')->with('success', 'Sanatan event deactivated successfully.');
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $originalSlug = Str::slug($name) ?: 'sanatan-event';
        $slug = $originalSlug;
        $counter = 1;

        while (Sanatan::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}