<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with('state.country')->latest()->get();

        return view('admin.district.index', compact('districts'));
    }

    public function create()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.district.create', compact('countries'));
    }

    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
        ]);

        $state = State::where('id', $request->state_id)
            ->where('country_id', $request->country_id)
            ->firstOrFail();

        District::create([
            'state_id' => $state->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => 1
        ]);

        return redirect()
            ->route('districts.index')
            ->with('success', 'District created successfully.');
    }

    public function edit(District $district)
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        $states = State::where('country_id', $district->state->country_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.district.edit', compact(
            'district',
            'countries',
            'states'
        ));
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $state = State::where('id', $request->state_id)
            ->where('country_id', $request->country_id)
            ->firstOrFail();

        $district->update([
            'state_id' => $state->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('districts.index')
            ->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();

        return redirect()
            ->route('districts.index')
            ->with('success', 'District deleted successfully.');
    }
}
