<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::latest()->paginate(20);
        return view('admin.country.index', compact('countries'));
    }

    public function create(){
        return view('admin.country.create');
    }

    public function store(Request $request){
        $request->validate([
           'name' => 'required|string|unique:countries,name',
           'code' => 'required|string|unique:countries,code',
           'phone_code' => 'required|string',
           'status'=>'required'
        ]);

        Country::create([
            'name'=>$request->name,
            'code'=>$request->code,
            'phone_code'=>$request->phone_code,
            'status' => 1,
        ]);

        return redirect()->route('countries.index')
            ->with('success', 'Country created successfully');
    }

    public function edit(Country $country)
    {
        return view('admin.country.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:2|unique:countries,code,' . $country->id,
            'phone_code' => 'required|string|max:10',
            'status' => 'required|boolean',
        ]);

        $country->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'phone_code' => $request->phone_code,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('countries.index')
            ->with('success', 'Country updated successfully.');
    }
}
