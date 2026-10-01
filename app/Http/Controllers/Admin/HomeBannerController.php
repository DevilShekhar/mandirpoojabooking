<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        $homeBanners = HomeBanner::with(['createdBy', 'updatedBy'])            
            ->latest()
            ->paginate(10);

        return view('admin.home-banners.index', compact('homeBanners'));
    }

    public function create()
    {
        return view('admin.home-banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'btn_name' => 'nullable|string|max:255',
            'btn_url' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('home-banners', 'public');
        }

        $validated['status'] = 1;
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        HomeBanner::create($validated);

        return redirect()
            ->route('home-banners.index')
            ->with('success', 'Home banner created successfully.');
    }

    public function show(HomeBanner $homeBanner)
    {
        return view('admin.home-banners.show', compact('homeBanner'));
    }

    public function edit(HomeBanner $homeBanner)
    {
        return view('admin.home-banners.edit', compact('homeBanner'));
    }

    public function update(Request $request, HomeBanner $homeBanner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'btn_name' => 'nullable|string|max:255',
            'btn_url' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
        ]);

        if ($request->hasFile('image')) {
            if ($homeBanner->image && Storage::disk('public')->exists($homeBanner->image)) {
                Storage::disk('public')->delete($homeBanner->image);
            }

            $validated['image'] = $request->file('image')
                ->store('home-banners', 'public');
        }

        $validated['updated_by'] = Auth::id();

        $homeBanner->update($validated);

        return redirect()
            ->route('home-banners.index')
            ->with('success', 'Home banner updated successfully.');
    }

    public function destroy(HomeBanner $homeBanner)
    {
        $homeBanner->update([
            'status' => 0,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('home-banners.index')
            ->with('success', 'Home banner deleted successfully.');
    }
}