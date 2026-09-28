<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingDevotee;
use App\Models\PujaBooking;
use Illuminate\Http\Request;

class BookingDevoteeController extends Controller
{
    public function index()
    {
        $bookingDevotees = BookingDevotee::with('booking')
            ->latest()
            ->get();

        return view('admin.booking-devotees.index', compact('bookingDevotees'));
    }

    public function create()
    {
        $bookings = PujaBooking::latest()->get();
        return view('admin.booking-devotees.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'name' => 'required|string|max:255',
            'relation' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gotra' => 'nullable|string|max:100',
            'rashi' => 'nullable|string|max:100',
            'nakshatra' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_primary')) {
            BookingDevotee::where('booking_id', $request->booking_id)
                ->update(['is_primary' => false]);
        }

        BookingDevotee::create([
            'booking_id' => $request->booking_id,
            'name' => $request->name,
            'relation' => $request->relation,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'gotra' => $request->gotra,
            'rashi' => $request->rashi,
            'nakshatra' => $request->nakshatra,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return redirect()
            ->route('booking-devotees.index')
            ->with('success', 'Booking devotee created successfully.');
    }

    public function edit(BookingDevotee $bookingDevotee)
    {
        $bookings = PujaBooking::latest()->get();

        return view(
            'admin.booking-devotees.edit',
            compact('bookingDevotee', 'bookings')
        );
    }

    public function update(Request $request, BookingDevotee $bookingDevotee)
    {
        $request->validate([
            'booking_id' => 'required|exists:puja_bookings,id',
            'name' => 'required|string|max:255',
            'relation' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gotra' => 'nullable|string|max:100',
            'rashi' => 'nullable|string|max:100',
            'nakshatra' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_primary')) {
            BookingDevotee::where('booking_id', $request->booking_id)
                ->where('id', '!=', $bookingDevotee->id)
                ->update(['is_primary' => false]);
        }

        $bookingDevotee->update([
            'booking_id' => $request->booking_id,
            'name' => $request->name,
            'relation' => $request->relation,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'gotra' => $request->gotra,
            'rashi' => $request->rashi,
            'nakshatra' => $request->nakshatra,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'is_primary' => $request->boolean('is_primary'),
        ]);

        return redirect()
            ->route('booking-devotees.index')
            ->with('success', 'Booking devotee updated successfully.');
    }

    public function destroy(BookingDevotee $bookingDevotee)
    {
        $bookingDevotee->delete();

        return redirect()
            ->route('booking-devotees.index')
            ->with('success', 'Booking devotee deleted successfully.');
    }
}
