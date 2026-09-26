<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pandit;
use App\Models\PanditPuja;
use App\Models\Puja;
use Illuminate\Http\Request;

class PanditPujaController extends Controller
{
    public function index()
    {
        $panditPujas = PanditPuja::with(['pandit', 'puja'])
            ->latest()
            ->paginate(20);

        return view('admin.pandit-pujas.index', compact('panditPujas'));
    }

    public function create()
    {
        $pandits = Pandit::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.pandit-pujas.create', compact('pandits', 'pujas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pandit_id' => 'required|exists:pandits,id',
            'puja_id' => 'required|exists:pujas,id',
        ]);

        $exists = PanditPuja::where('pandit_id', $request->pandit_id)
            ->where('puja_id', $request->puja_id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This puja is already assigned to this pandit.');
        }

        PanditPuja::create([
            'pandit_id' => $request->pandit_id,
            'puja_id' => $request->puja_id,
        ]);

        return redirect()
            ->route('pandit-pujas.index')
            ->with('success', 'Puja assigned to pandit successfully.');
    }

    public function edit(PanditPuja $panditPuja)
    {
        $pandits = Pandit::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.pandit-pujas.edit', compact(
            'panditPuja',
            'pandits',
            'pujas'
        ));
    }

    public function update(Request $request, PanditPuja $panditPuja)
    {
        $request->validate([
            'pandit_id' => 'required|exists:pandits,id',
            'puja_id' => 'required|exists:pujas,id',
        ]);

        $exists = PanditPuja::where('pandit_id', $request->pandit_id)
            ->where('puja_id', $request->puja_id)
            ->where('id', '!=', $panditPuja->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->with('error', 'This puja is already assigned to this pandit.');
        }

        $panditPuja->update([
            'pandit_id' => $request->pandit_id,
            'puja_id' => $request->puja_id,
        ]);

        return redirect()
            ->route('pandit-pujas.index')
            ->with('success', 'Pandit puja mapping updated successfully.');
    }

    public function destroy(PanditPuja $panditPuja)
    {
        $panditPuja->delete();

        return redirect()
            ->route('pandit-pujas.index')
            ->with('success', 'Puja removed from pandit successfully.');
    }
}
