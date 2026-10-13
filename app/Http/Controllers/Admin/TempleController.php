<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class TempleController extends Controller
{
    private function isAdmin(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $user->hasAnyRole(['Super Admin', 'Admin'])
            || in_array($user->role?->name, ['Super Admin', 'Admin'], true);
    }

    private function authorizeTempleAccess(Temple $temple): void
    {
        $user = auth()->user();

        abort_unless($user, 401);

        if ($this->isAdmin()) {
            return;
        }

        abort_unless(
            (int) $temple->created_by === (int) $user->id
            || (int) $temple->updated_by === (int) $user->id,
            403
        );
    }

    public function index()
    {
        $query = Temple::with(['state', 'district', 'city']);

        if (!$this->isAdmin()) {
            $userId = auth()->id();

            $query->where(function ($q) use ($userId) {
                $q->where('created_by', $userId)
                    ->orWhere('updated_by', $userId);
            });
        }

        $temples = $query->latest()->get();

        return view('admin.temple.index', compact('temples'));
    }

    public function create()
    {
        $states = State::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.temple.create', compact('states'));
    }

    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    public function getCities($districtId)
    {
        $cities = City::where('district_id', $districtId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'temple_code' => [
                'required',
                'string',
                'max:100',
                'unique:temples,temple_code',
            ],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'main_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'is_verified' => ['required', 'boolean'],
        ]);

        $district = District::where('id', $validated['district_id'])
            ->where('state_id', $validated['state_id'])
            ->first();

        if (!$district) {
            return back()
                ->withInput()
                ->withErrors([
                    'district_id' => 'The selected district does not belong to the selected state.',
                ]);
        }

        $city = City::where('id', $validated['city_id'])
            ->where('district_id', $district->id)
            ->first();

        if (!$city) {
            return back()
                ->withInput()
                ->withErrors([
                    'city_id' => 'The selected city does not belong to the selected district.',
                ]);
        }

        $mainImage = null;
        $bannerImage = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image')
                    ->store('temples', 'public');
            }

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')
                    ->store('temples', 'public');
            }

            Temple::create([
                'state_id' => $validated['state_id'],
                'district_id' => $district->id,
                'city_id' => $city->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'temple_code' => $validated['temple_code'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'main_image' => $mainImage,
                'banner_image' => $bannerImage,
                'is_verified' => $validated['is_verified'],
                'status' => 1,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($mainImage) {
                Storage::disk('public')->delete($mainImage);
            }

            if ($bannerImage) {
                Storage::disk('public')->delete($bannerImage);
            }

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Unable to create temple. Please try again.',
                ]);
        }

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple created successfully.');
    }

    public function edit(Temple $temple)
    {
        $this->authorizeTempleAccess($temple);

        $states = State::where('status', 1)
            ->orderBy('name')
            ->get();

        $districts = District::where('state_id', $temple->state_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $cities = City::where('district_id', $temple->district_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.temple.edit', compact(
            'temple',
            'states',
            'districts',
            'cities'
        ));
    }

    public function update(Request $request, Temple $temple)
    {
        $this->authorizeTempleAccess($temple);

        $validated = $request->validate([
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'name' => ['required', 'string', 'max:255'],
            'temple_code' => [
                'required',
                'string',
                'max:100',
                'unique:temples,temple_code,' . $temple->id,
            ],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'main_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'is_verified' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        $district = District::where('id', $validated['district_id'])
            ->where('state_id', $validated['state_id'])
            ->first();

        if (!$district) {
            return back()
                ->withInput()
                ->withErrors([
                    'district_id' => 'The selected district does not belong to the selected state.',
                ]);
        }

        $city = City::where('id', $validated['city_id'])
            ->where('district_id', $district->id)
            ->first();

        if (!$city) {
            return back()
                ->withInput()
                ->withErrors([
                    'city_id' => 'The selected city does not belong to the selected district.',
                ]);
        }

        $oldMainImage = $temple->main_image;
        $oldBannerImage = $temple->banner_image;
        $newMainImage = null;
        $newBannerImage = null;

        DB::beginTransaction();

        try {
            $data = [
                'state_id' => $validated['state_id'],
                'district_id' => $district->id,
                'city_id' => $city->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'temple_code' => $validated['temple_code'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'is_verified' => $validated['is_verified'],
                'status' => $validated['status'],
                'updated_by' => auth()->id(),
            ];

            if ($request->hasFile('main_image')) {
                $newMainImage = $request->file('main_image')
                    ->store('temples', 'public');

                $data['main_image'] = $newMainImage;
            }

            if ($request->hasFile('banner_image')) {
                $newBannerImage = $request->file('banner_image')
                    ->store('temples', 'public');

                $data['banner_image'] = $newBannerImage;
            }

            $temple->update($data);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($newMainImage) {
                Storage::disk('public')->delete($newMainImage);
            }

            if ($newBannerImage) {
                Storage::disk('public')->delete($newBannerImage);
            }

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Unable to update temple. Please try again.',
                ]);
        }

        if ($newMainImage && $oldMainImage) {
            Storage::disk('public')->delete($oldMainImage);
        }

        if ($newBannerImage && $oldBannerImage) {
            Storage::disk('public')->delete($oldBannerImage);
        }

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple updated successfully.');
    }

    public function destroy(Temple $temple)
    {
        $this->authorizeTempleAccess($temple);

        $temple->delete();

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple deleted successfully.');
    }
}