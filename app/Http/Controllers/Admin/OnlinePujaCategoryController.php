<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePujaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OnlinePujaCategoryController extends Controller
{
    // Display all categories
    public function index()
    {
        $categories = OnlinePujaCategory::with(['creator', 'updater'])->latest()->get();
        return view('admin.online-puja-categories.index',compact('categories'));
    }

    public function create()
    {
        return view('admin.online-puja-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255','unique:online_puja_categories,name',],
        ]);

        $originalSlug = Str::slug($validated['name']);
        $slug = $originalSlug;
        $counter = 1;
        while (OnlinePujaCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        OnlinePujaCategory::create([
            'name'       => $validated['name'],
            'slug'       => $slug,
            'status'     => 1,
            'created_by' => Auth::id(),
        ]);
        return redirect()
            ->route('online-puja-categories.index')
            ->with('success', 'Online Puja Category created successfully.');
    }

    public function edit(OnlinePujaCategory $online_puja_category)
    {
        return view('admin.online-puja-categories.edit',compact('online_puja_category'));
    }


    public function update(Request $request,OnlinePujaCategory $online_puja_category) {
        $validated = $request->validate([
            'name' => ['required','string','max:255',
                Rule::unique('online_puja_categories', 'name')->ignore($online_puja_category->id),
            ],
            'status' => ['required','in:0,1',],
        ]);

        $originalSlug = Str::slug($validated['name']);
        $slug = $originalSlug;
        $counter = 1;

        while (OnlinePujaCategory::where('slug', $slug)
            ->where('id', '!=', $online_puja_category->id)->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $online_puja_category->update([
            'name'       => $validated['name'],
            'slug'       => $slug,
            'status'     => $validated['status'],
            'updated_by' => Auth::id(),
        ]);
        return redirect()
            ->route('online-puja-categories.index')
            ->with('success', 'Online Puja Category updated successfully.');
    }

    public function destroy(OnlinePujaCategory $online_puja_category)
    {
        $online_puja_category->update([
            'status'     => 0,
            'updated_by' => Auth::id(),
        ]);
        return redirect()
            ->route('online-puja-categories.index')
            ->with(
                'success',
                'Online Puja Category status updated to inactive successfully.'
            );
    }
}