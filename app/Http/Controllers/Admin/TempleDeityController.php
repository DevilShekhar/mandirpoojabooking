<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deity;
use App\Models\Temple;
use App\Models\TempleDeity;
use Illuminate\Http\Request;

class TempleDeityController extends Controller
{
    public function index()
    {
        $templeDeities = TempleDeity::with('temple', 'deity')
            ->latest()
            ->get();

        return view('admin.temple-deities.index', compact('templeDeities'));
    }

    public function create()
    {
        $temples = Temple::where('status', 1)->get();
        $deities = Deity::where('status', 1)->get();

        return view('admin.temple-deities.create', compact('temples', 'deities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'is_primary' => 'required|boolean',
            'display_order' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        TempleDeity::create([
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'is_primary' => $request->is_primary,
            'display_order' => $request->display_order,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('temple-deities.index')
            ->with('Success', 'Temple Deity Created Successfully');
    }

    public function edit(TempleDeity $templeDeity)
    {
        $temples = Temple::where('status', 1)->get();
        $deities = Deity::where('status', 1)->get();

        return view('admin.temple-deities.edit', compact(
            'templeDeity',
            'temples',
            'deities'
        ));
    }

    public function update(Request $request, TempleDeity $templeDeity)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'is_primary' => 'required|boolean',
            'display_order' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ]);

        $templeDeity->update([
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'is_primary' => $request->is_primary,
            'display_order' => $request->display_order,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('temple-deities.index')
            ->with('Success', 'Temple Deity Updated Successfully');
    }

    public function destroy(TempleDeity $templeDeity)
    {
        $templeDeity->update([
            'status' => 0,
        ]);

        return redirect()
            ->route('temple-deities.index')
            ->with('Success', 'Temple Deity Deleted Successfully');
    }
}
