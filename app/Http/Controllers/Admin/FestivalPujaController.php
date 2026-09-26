<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FestivalPuja;
use App\Models\Festival;
use App\Models\Puja;
use Illuminate\Http\Request;

class FestivalPujaController extends Controller
{
    public function index()
    {
        $festivalPujas = FestivalPuja::with(['festival', 'puja'])
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.festival-pujas.index', compact('festivalPujas'));
    }

    public function create()
    {
        $festivals = Festival::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.festival-pujas.create', compact('festivals', 'pujas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'festival_id' => 'required|exists:festivals,id',
            'puja_id' => 'required|exists:pujas,id',
            'special_price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $exists = FestivalPuja::where('festival_id', $request->festival_id)
            ->where('puja_id', $request->puja_id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This Puja is already linked with the selected Festival.');
        }

        FestivalPuja::create([
            'festival_id' => $request->festival_id,
            'puja_id' => $request->puja_id,
            'special_price' => $request->special_price,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('festival-pujas.index')
            ->with('success', 'Festival Puja created successfully.');
    }

    public function edit(FestivalPuja $festivalPuja)
    {
        $festivals = Festival::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.festival-pujas.edit', compact(
            'festivalPuja',
            'festivals',
            'pujas'
        ));
    }

    public function update(Request $request, FestivalPuja $festivalPuja)
    {
        $request->validate([
            'festival_id' => 'required|exists:festivals,id',
            'puja_id' => 'required|exists:pujas,id',
            'special_price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $exists = FestivalPuja::where('festival_id', $request->festival_id)
            ->where('puja_id', $request->puja_id)
            ->where('id', '!=', $festivalPuja->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This Puja is already linked with the selected Festival.');
        }

        $festivalPuja->update([
            'festival_id' => $request->festival_id,
            'puja_id' => $request->puja_id,
            'special_price' => $request->special_price,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('festival-pujas.index')
            ->with('success', 'Festival Puja updated successfully.');
    }

    public function destroy(FestivalPuja $festivalPuja)
    {
        $festivalPuja->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('festival-pujas.index')
            ->with('success', 'Festival Puja deactivated successfully.');
    }
}
