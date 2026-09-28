<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TempleController extends Controller
{
    public function index()
    {
        $temples = Temple::with('state', 'district', 'city')->latest()->get();

        return view('admin.temple.index', compact('temples'));
    }

    public function create()
    {
        $states = State::get()->where('status',1);
        return view('admin.temple.create', compact('states'));
    }

    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->where('status', 1)
            ->get(['id', 'name']);
            $districts = District::all();

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
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'temple_code' => 'required|string|max:100|unique:temples,temple_code',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_verified' => 'required|boolean',
        ]);

        $district = District::where('id', $request->district_id)
            ->where('state_id', $request->state_id)
            ->firstOrFail();

        $city = City::where('id', $request->city_id)
            ->where('district_id', $district->id)
            ->firstOrFail();

        $mainImage = null;
        $bannerImage = null;

        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image')->store('temples', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $request->file('banner_image')->store('temples', 'public');
        }

        Temple::create([
            'state_id' => $request->state_id,
            'district_id' => $district->id,
            'city_id' => $city->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'temple_code' => $request->temple_code,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'main_image' => $mainImage,
            'banner_image' => $bannerImage,
            'is_verified' => $request->is_verified,
            'status' => 1   
        ]);

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple created successfully.');
    }

    public function edit(Temple $temple)
    {
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
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'temple_code' => 'required|string|max:100|unique:temples,temple_code,' . $temple->id,
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_verified' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $district = District::where('id', $request->district_id)
            ->where('state_id', $request->state_id)
            ->firstOrFail();

        $city = City::where('id', $request->city_id)
            ->where('district_id', $district->id)
            ->firstOrFail();

        $data = [
            'state_id' => $request->state_id,
            'district_id' => $district->id,
            'city_id' => $city->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'temple_code' => $request->temple_code,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_verified' => $request->is_verified,
            'status' => $request->status,
        ];

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')
                ->store('temples', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')
                ->store('temples', 'public');
        }

        $temple->update($data);

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple updated successfully.');
    }

    public function destroy(Temple $temple)
    {
        $temple->delete();

        return redirect()
            ->route('temples.index')
            ->with('success', 'Temple deleted successfully.');
    }
}
