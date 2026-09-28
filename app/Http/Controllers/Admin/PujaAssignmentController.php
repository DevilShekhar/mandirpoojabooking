<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pandit;
use App\Models\PujaAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PujaAssignmentController extends Controller
{
    public function index()
    {
        $pujaAssignments = PujaAssignment::with([
            'booking',
            'pandit',
            'assignedBy'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('admin.puja-assignments.index', compact('pujaAssignments'));
    }

    public function create()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();

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
            'booking_id' => 'required|exists:bookings,id',
            'pandit_id' => 'required|exists:pandits,id',
            'status' => 'required|in:assigned,accepted,started,completed,cancelled',
            'assigned_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
            'remarks' => 'nullable|string',
        ]);

        PujaAssignment::create([
            'booking_id' => $request->booking_id,
            'pandit_id' => $request->pandit_id,
            'assigned_by' => Auth::id(),
            'assigned_at' => $request->assigned_at ?? now(),
            'status' => $request->status,
            'started_at' => $request->started_at,
            'completed_at' => $request->completed_at,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('puja-assignments.index')
            ->with('success', 'Puja assigned to pandit successfully.');
    }

    public function show(PujaAssignment $pujaAssignment)
    {
        $pujaAssignment->load([
            'booking',
            'pandit.temple',
            'assignedBy'
        ]);

        return view(
            'admin.puja-assignments.show',
            compact('pujaAssignment')
        );
    }

    public function edit(PujaAssignment $pujaAssignment)
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();

        $pandits = Pandit::where('status', 1)
            ->where('verification_status', 'verified')
            ->orderBy('name')
            ->get();

        return view('admin.puja-assignments.edit', compact(
            'pujaAssignment',
            'bookings',
            'pandits'
        ));
    }

    public function update(Request $request, PujaAssignment $pujaAssignment)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'pandit_id' => 'required|exists:pandits,id',
            'status' => 'required|in:assigned,accepted,started,completed,cancelled',
            'assigned_at' => 'nullable|date',
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
