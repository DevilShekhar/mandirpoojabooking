<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Festival;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FestivalController extends Controller
{
    public function index()
    {
        $festivals = Festival::latest()->get();
        return view('admin.festivals.index', compact('festivals'));
    }

    public function create()
    {
        return view('admin.festivals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'festival_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (Festival::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $image = null;
        $bannerImage = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('festival_images', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->store('festival_banners', 'public');
        }

        Festival::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'festival_date' => $request->festival_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'image' => $image,
            'banner_image' => $bannerImage,
            'status' => 1,
        ]);

        return redirect()
            ->route('festivals.index')
            ->with('success', 'Festival created successfully.');
    }

    public function edit(Festival $festival)
    {
        return view('admin.festivals.edit', compact('festival'));
    }

    public function update(Request $request, Festival $festival)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'festival_date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status' => 'required|boolean',
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (
            Festival::where('slug', $slug)
                ->where('id', '!=', $festival->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'festival_date' => $request->festival_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('festival_images', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')
                ->store('festival_banners', 'public');
        }

        $festival->update($data);

        return redirect()
            ->route('festivals.index')
            ->with('success', 'Festival updated successfully.');
    }

    public function destroy(Festival $festival)
    {
        $festival->update([
            'status' => 0
        ]);

        return redirect()
            ->route('festivals.index')
            ->with('success', 'Festival deactivated successfully.');
    }
}
