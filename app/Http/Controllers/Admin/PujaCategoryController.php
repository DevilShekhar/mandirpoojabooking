<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PujaCategoryController extends Controller
{
    public function index()
    {
        $pujaCategories = PujaCategory::latest()->get();

        return view('admin.puja-categories.index', compact('pujaCategories'));
    }

    public function create()
    {
        return view('admin.puja-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'required|integer|min:0',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('puja_category_image', 'public');
        }

        PujaCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $image,
            'status' => 1,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()
            ->route('puja-categories.index')
            ->with('Success', 'Puja Category Created Successfully');
    }

    public function edit(PujaCategory $pujaCategory)
    {
        return view('admin.puja-categories.edit', compact('pujaCategory'));
    }

    public function update(Request $request, PujaCategory $pujaCategory)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $image = $pujaCategory->image;

        if ($request->hasFile('image')) {
            if ($pujaCategory->image && Storage::disk('public')->exists($pujaCategory->image)) {
                Storage::disk('public')->delete($pujaCategory->image);
            }

            $image = $request->file('image')->store('puja_category_image', 'public');
        }

        $pujaCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $image,
            'status' => $request->status,
            'sort_order' => $request->sort_order,
        ]);

        return redirect()
            ->route('puja-categories.index')
            ->with('Success', 'Puja Category Updated Successfully');
    }

    public function destroy(PujaCategory $pujaCategory)
    {
        $pujaCategory->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('puja-categories.index')
            ->with('Success', 'Puja Category Deleted Successfully');
    }
}
