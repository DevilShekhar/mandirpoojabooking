<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('country', 'state', 'district')
            ->latest()
            ->get();

        return view('admin.city.index', compact('cities'));
    }

    public function create()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.city.create', compact('countries'));
    }

    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|boolean',
        ]);

        $state = State::where('id', $request->state_id)
            ->where('country_id', $request->country_id)
            ->firstOrFail();

        $district = District::where('id', $request->district_id)
            ->where('state_id', $state->id)
            ->firstOrFail();

        City::create([
            'country_id' => $request->country_id,
            'state_id' => $state->id,
            'district_id' => $district->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'pincode' => $request->pincode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit(City $city)
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        $states = State::where('country_id', $city->country_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $districts = District::where('state_id', $city->state_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.city.edit', compact(
            'city',
            'countries',
            'states',
            'districts'
        ));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|boolean',
        ]);

        $state = State::where('id', $request->state_id)
            ->where('country_id', $request->country_id)
            ->firstOrFail();

        $district = District::where('id', $request->district_id)
            ->where('state_id', $state->id)
            ->firstOrFail();

        $city->update([
            'country_id' => $request->country_id,
            'state_id' => $state->id,
            'district_id' => $district->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'pincode' => $request->pincode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()
            ->route('cities.index')
            ->with('success', 'City deleted successfully.');
    }
}
