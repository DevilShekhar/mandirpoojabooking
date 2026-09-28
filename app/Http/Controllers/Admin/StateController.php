<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('country')->latest()->get();

        return view('admin.state.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
        ]);

        State::create([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'slug' => Str::slug($request->name),
            'status' => 1
        ]);

        return redirect()
            ->route('states.index')
            ->with('success', 'State created successfully.');
    }

    public function edit(State $state)
    {
        $countries = Country::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.state.edit', compact('state', 'countries'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'status' => 'required|boolean',
        ]);

        $state->update([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'slug' => Str::slug($request->name),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->update(['status' => 0]);

        return redirect()
            ->route('states.index')
            ->with('success', 'State deleted successfully.');
    }
}
