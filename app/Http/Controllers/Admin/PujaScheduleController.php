<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use App\Models\PujaSchedule;
use App\Models\Temple;
use Illuminate\Http\Request;

class PujaScheduleController extends Controller
{
    public function index()
    {
        $pujaSchedules = PujaSchedule::with(['temple', 'puja'])
            ->orderBy('puja_date')
            ->orderBy('start_time')
            ->get();

        return view('admin.puja-schedules.index', compact('pujaSchedules'));
    }

    public function create()
    {
        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.puja-schedules.create', compact('temples', 'pujas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'puja_id' => 'required|exists:pujas,id',
            'puja_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'booked_capacity' => 'nullable|integer|min:0',
            'booking_start_date' => 'nullable|date',
            'booking_end_date' => 'nullable|date|after_or_equal:booking_start_date',
        ]);

        $bookedCapacity = $request->booked_capacity ?? 0;

        if ($bookedCapacity > $request->capacity) {
            return back()
                ->withInput()
                ->withErrors([
                    'booked_capacity' => 'Booked capacity cannot be greater than total capacity.'
                ]);
        }

        PujaSchedule::create([
            'temple_id' => $request->temple_id,
            'puja_id' => $request->puja_id,
            'puja_date' => $request->puja_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'booked_capacity' => $bookedCapacity,
            'remaining_capacity' => $request->capacity - $bookedCapacity,
            'booking_start_date' => $request->booking_start_date,
            'booking_end_date' => $request->booking_end_date,
            'status' => 1,
        ]);

        return redirect()
            ->route('puja-schedules.index')
            ->with('success', 'Puja schedule created successfully.');
    }

    public function show(PujaSchedule $pujaSchedule)
    {
        $pujaSchedule->load(['temple', 'puja']);

        return view('admin.puja-schedules.show', compact('pujaSchedule'));
    }

    public function edit(PujaSchedule $pujaSchedule)
    {
        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('admin.puja-schedules.edit', compact(
            'pujaSchedule',
            'temples',
            'pujas'
        ));
    }

    public function update(Request $request, PujaSchedule $pujaSchedule)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'puja_id' => 'required|exists:pujas,id',
            'puja_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'booked_capacity' => 'nullable|integer|min:0',
            'booking_start_date' => 'nullable|date',
            'booking_end_date' => 'nullable|date|after_or_equal:booking_start_date',
            'status' => 'required|boolean',
        ]);

        $bookedCapacity = $request->booked_capacity ?? 0;

        if ($bookedCapacity > $request->capacity) {
            return back()
                ->withInput()
                ->withErrors([
                    'booked_capacity' => 'Booked capacity cannot be greater than total capacity.'
                ]);
        }

        $pujaSchedule->update([
            'temple_id' => $request->temple_id,
            'puja_id' => $request->puja_id,
            'puja_date' => $request->puja_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'booked_capacity' => $bookedCapacity,
            'remaining_capacity' => $request->capacity - $bookedCapacity,
            'booking_start_date' => $request->booking_start_date,
            'booking_end_date' => $request->booking_end_date,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('puja-schedules.index')
            ->with('success', 'Puja schedule updated successfully.');
    }

    public function destroy(PujaSchedule $pujaSchedule)
    {
        $pujaSchedule->update([
            'status' => 0
        ]);

        return redirect()
            ->route('puja-schedules.index')
            ->with('success', 'Puja schedule deactivated successfully.');
    }
}
