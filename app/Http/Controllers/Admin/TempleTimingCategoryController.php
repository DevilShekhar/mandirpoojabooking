<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\TempleTimingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class TempleTimingCategoryController extends Controller
{
    private function isAdmin(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $user->hasAnyRole(['Super Admin', 'Admin']) || in_array($user->role?->name, ['Super Admin', 'Admin'], true);
    }

    private function accessibleTemples()
    {
        $query = Temple::query();
        if (!$this->isAdmin()) {
            $userId = auth()->id();
            $query->where(function ($q) use ($userId) {
                $q->where('created_by', $userId)
                    ->orWhere('updated_by', $userId);
            });
        }
        return $query;
    }
    private function authorizeTempleAccess(Temple $temple): void
    {
        abort_unless(auth()->user(), 401);
        if ($this->isAdmin()) {
            return;
        }
        abort_unless((int) $temple->created_by === (int) auth()->id()|| (int) $temple->updated_by === (int) auth()->id(),403);
    }

    private function authorizeCategoryAccess(TempleTimingCategory $category): void {
        $this->authorizeTempleAccess($category->temple);
    }

    private function generateUniqueSlug( string $name, int $templeId,?int $ignoreId = null): string {
        $slug = Str::slug($name);
        if (!$slug) {
            $slug = 'category';
        }
        $originalSlug = $slug;
        $counter = 1;
        while (TempleTimingCategory::where('temple_id', $templeId)
            ->where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }

    public function index()
    {
        $query = TempleTimingCategory::with('temple');
        if (!$this->isAdmin()) {
            $userId = auth()->id();
            $query->whereHas('temple', function ($q) use ($userId) {
                $q->where(function ($subQuery) use ($userId) {
                    $subQuery->where('created_by', $userId)
                        ->orWhere('updated_by', $userId);
                });
            });
        }
        $categories = $query->latest()->get();
        return view('admin.temple-timing-categories.index',compact('categories'));
    }

    public function create()
    {
        $temples = $this->accessibleTemples()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);
        abort_if($temples->isEmpty(), 403, 'No accessible temple found.');
        return view('admin.temple-timing-categories.create',compact('temples'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'exists:temples,id',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);
        // Verify temple ownership.
        $temple = $this->accessibleTemples()
            ->where('id', $validated['temple_id'])
            ->first();
        if (!$temple) {
            abort(403, 'You are not authorized to manage this temple.');
        }
        // Category name must be unique within the selected temple.
        $exists = TempleTimingCategory::where(
            'temple_id',
            $temple->id
        )->where('name', $validated['name'])->exists();
        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This category already exists for this temple.',
                ]);
        }
        try {
            TempleTimingCategory::create([
                'temple_id' => $temple->id,
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug(
                    $validated['name'],
                    $temple->id
                ),
                'status' => $validated['status'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Unable to create category. Please try again.',
                ]);
        }
        return redirect()
            ->route('temple-timing-categories.index')
            ->with('success', 'Temple timing category created successfully.');
    }
    public function show(TempleTimingCategory $templeTimingCategory)
    {
        $this->authorizeCategoryAccess($templeTimingCategory);
        $templeTimingCategory->load('temple');
        return view(
            'admin.temple-timing-categories.show',
            compact('templeTimingCategory')
        );
    }
    public function edit(TempleTimingCategory $templeTimingCategory)
    {
        $this->authorizeCategoryAccess($templeTimingCategory);
        $temples = $this->accessibleTemples()
            ->where(function ($query) use ($templeTimingCategory) {
                $query->where('status', 1)
                    ->orWhere('id', $templeTimingCategory->temple_id);
            })
            ->orderBy('name')
            ->get(['id', 'name']);
        return view(
            'admin.temple-timing-categories.edit',
            compact('templeTimingCategory', 'temples')
        );
    }

    public function update(Request $request,TempleTimingCategory $templeTimingCategory)
    {
        $this->authorizeCategoryAccess($templeTimingCategory);
        $validated = $request->validate([
            'temple_id' => [
                'required',
                'exists:temples,id',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);
        $temple = $this->accessibleTemples()
            ->where('id', $validated['temple_id'])
            ->first();
        if (!$temple) {
            abort(403, 'You are not authorized to manage this temple.');
        }
        // Check duplicate category name within temple.
        $exists = TempleTimingCategory::where(
            'temple_id',
            $temple->id
        )
            ->where('name', $validated['name'])
            ->where('id', '!=', $templeTimingCategory->id)
            ->exists();
        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This category already exists for this temple.',
                ]);
        }
        try {
            $templeTimingCategory->update([
                'temple_id' => $temple->id,
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug(
                    $validated['name'],
                    $temple->id,
                    $templeTimingCategory->id
                ),
                'status' => $validated['status'],
                'updated_by' => auth()->id(),
            ]);
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Unable to update category. Please try again.',
                ]);
        }
        return redirect()->route('temple-timing-categories.index')->with('success', 'Temple timing category updated successfully.');
    }

    public function destroy(TempleTimingCategory $templeTimingCategory)
    {
        $this->authorizeCategoryAccess($templeTimingCategory);
        try {
            $templeTimingCategory->update([
                'status' => 0
            ]);
            return redirect()
                ->route('temple-timing-categories.index')
                ->with('success', 'Temple timing category deactivated successfully.');
        } catch (Throwable $e) {
            report($e);
            return redirect()
                ->route('temple-timing-categories.index')
                ->with('error', 'Unable to deactivate category. Please try again.');
        }
    }
}