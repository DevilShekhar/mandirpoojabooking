<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaAssignment;
use App\Models\PujaBooking;
use App\Models\Pandit;
use Illuminate\Http\Request;

class PujaAssignmentController extends Controller
{
    public function index()
    {
        $assignments = PujaAssignment::with([
            'booking',
            'pandit'
        ])->latest()->paginate(10);
        return view('admin.puja-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $bookings = PujaBooking::with([
            'puja',
            'pujaPackage',
            'pujaSchedule'
        ])
        ->whereNotIn('booking_status', ['cancelled', 'completed'])
        ->get();

        $pandits = Pandit::where('status', 1)
            ->where('verification_status', 'verified')
            ->orderBy('name')
            ->get();

        return view('admin.puja-assignments.create', compact(
            'bookings',
            'pandits'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'pandit_id' => 'required|exists:pandits,id',
            'assigned_at' => 'nullable|date',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        PujaAssignment::create([
            'booking_id' => $request->booking_id,
            'pandit_id' => $request->pandit_id,
            'assigned_by' => auth()->id(),
            'assigned_at' => $request->assigned_at ?? now(),
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('puja-assignments.index')
            ->with('success', 'Puja assigned successfully.');
    }

    public function show(PujaAssignment $pujaAssignment)
    {
        $pujaAssignment->load([
            'booking',
            'pandit',
            'assignedBy'
        ]);

        return view(
            'admin.puja-assignments.show',
            compact('pujaAssignment')
        );
    }

    public function edit(PujaAssignment $pujaAssignment)
    {
        $bookings = PujaBooking::with([
            'puja',
            'pujaPackage',
            'pujaSchedule'
        ])->get();

        $pandits = Pandit::where('status', 1)
            ->where('verification_status', 'verified')
            ->orderBy('name')
            ->get();

        return view(
            'admin.puja-assignments.edit',
            compact('pujaAssignment', 'bookings', 'pandits')
        );
    }

    public function update(Request $request, PujaAssignment $pujaAssignment)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'pandit_id' => 'required|exists:pandits,id',
            'assigned_at' => 'nullable|date',
            'status' => 'required|string',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
            'remarks' => 'nullable|string',
        ]);

        $pujaAssignment->update([
            'booking_id' => $request->booking_id,
            'pandit_id' => $request->pandit_id,
            'assigned_at' => $request->assigned_at,
            'status' => $request->status,
            'started_at' => $request->started_at,
            'completed_at' => $request->completed_at,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('puja-assignments.index')
            ->with('success', 'Puja assignment updated successfully.');
    }

    public function destroy(PujaAssignment $pujaAssignment)
    {
        $pujaAssignment->delete();

        return redirect()
            ->route('puja-assignments.index')
            ->with('success', 'Puja assignment deleted successfully.');
    }
}
