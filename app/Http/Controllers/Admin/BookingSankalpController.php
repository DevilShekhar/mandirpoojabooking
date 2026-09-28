<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSankalp;
use App\Models\PujaBooking;
use App\Models\Pandit;
use Illuminate\Http\Request;

class BookingSankalpController extends Controller
{
    public function index()
    {
        $bookingSankalps = BookingSankalp::with([
            'booking',
            'pandit'
        ])
        ->latest()
        ->get();

        return view('admin.booking-sankalps.index', compact('bookingSankalps'));
    }

    public function create()
    {
        $bookings = PujaBooking::latest()->get();

        $pandits = Pandit::where('status', 1)
            ->where('verification_status', 'verified')
            ->orderBy('name')
            ->get();

        return view('admin.booking-sankalps.create', compact(
            'bookings',
            'pandits'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'sankalp_date' => 'nullable|date',
            'sankalp_text' => 'nullable|string',
            'pandit_id' => 'nullable|exists:pandits,id',
            'status' => 'required|in:pending,in_progress,completed',
            'completed_at' => 'nullable|date',
            'video_url' => 'nullable|string|max:500',
            'remarks' => 'nullable|string',
        ]);

        BookingSankalp::create([
            'booking_id' => $request->booking_id,
            'sankalp_date' => $request->sankalp_date,
            'sankalp_text' => $request->sankalp_text,
            'pandit_id' => $request->pandit_id,
            'status' => $request->status,
            'completed_at' => $request->completed_at,
            'video_url' => $request->video_url,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('booking-sankalps.index')
            ->with('success', 'Booking Sankalp created successfully.');
    }

    public function show(BookingSankalp $bookingSankalp)
    {
        $bookingSankalp->load([
            'booking',
            'pandit'
        ]);

        return view(
            'admin.booking-sankalps.show',
            compact('bookingSankalp')
        );
    }

    public function edit(BookingSankalp $bookingSankalp)
    {
        $bookings = PujaBooking::latest()->get();

        $pandits = Pandit::where('status', 1)
            ->where('verification_status', 'verified')
            ->orderBy('name')
            ->get();

        return view(
            'admin.booking-sankalps.edit',
            compact('bookingSankalp', 'bookings', 'pandits')
        );
    }

    public function update(Request $request, BookingSankalp $bookingSankalp)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'sankalp_date' => 'nullable|date',
            'sankalp_text' => 'nullable|string',
            'pandit_id' => 'nullable|exists:pandits,id',
            'status' => 'required|in:pending,in_progress,completed',
            'completed_at' => 'nullable|date',
            'video_url' => 'nullable|string|max:500',
            'remarks' => 'nullable|string',
        ]);

        $bookingSankalp->update([
            'booking_id' => $request->booking_id,
            'sankalp_date' => $request->sankalp_date,
            'sankalp_text' => $request->sankalp_text,
            'pandit_id' => $request->pandit_id,
            'status' => $request->status,
            'completed_at' => $request->completed_at,
            'video_url' => $request->video_url,
            'remarks' => $request->remarks,
        ]);

        return redirect()
            ->route('booking-sankalps.index')
            ->with('success', 'Booking Sankalp updated successfully.');
    }

    public function destroy(BookingSankalp $bookingSankalp)
    {
        $bookingSankalp->delete();

        return redirect()
            ->route('booking-sankalps.index')
            ->with('success', 'Booking Sankalp deleted successfully.');
    }
}
