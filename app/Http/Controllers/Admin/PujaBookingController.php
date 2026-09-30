<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\User;
use App\Models\Temple;
use App\Models\Deity;
use App\Models\Puja;
use App\Models\PujaPackage;
use App\Models\PujaSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PujaBookingController extends Controller
{
    public function index()
    {
        $bookings = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pujaSchedule',
            'createdBy',
            'updatedBy'
        ])
        ->where('created_by', auth()->id())
        ->latest()
        ->paginate(10);

        return view('admin.puja-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'Customer');
        })
            ->orderBy('name')
            ->get();

        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        $deities = Deity::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujaSchedules = PujaSchedule::where('status', 1)
            ->orderBy('puja_date')
            ->get();

        return view('admin.puja-bookings.create', compact(
            'users',
            'temples',
            'deities',
            'pujas',
            'pujaPackages',
            'pujaSchedules'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_id' => 'required|exists:pujas,id',
            'puja_package_id' => 'required|exists:puja_packages,id',
            'puja_schedule_id' => 'required|exists:puja_schedules,id',

            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_mobile' => 'required|string|max:20',

            'address_id' => 'nullable|integer',

            'package_name' => 'required|string|max:255',
            'person_count' => 'required|integer|min:1',

            'base_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'coupon_discount' => 'nullable|numeric|min:0',
            'addon_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',

            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:new,payment_verified,confirmed,cancelled,completed',
            'sankalp_status' => 'required|in:pending,completed',
            'puja_status' => 'required|in:pending,scheduled,completed',
            'video_status' => 'required|in:pending,available',
            'prasad_status' => 'required|in:pending,packed,shipped,delivered',
        ]);

        $booking = PujaBooking::create([
            'booking_number' => 'PB-' . strtoupper(Str::random(10)),

            'user_id' => $request->user_id,
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'puja_id' => $request->puja_id,
            'puja_package_id' => $request->puja_package_id,
            'puja_schedule_id' => $request->puja_schedule_id,

            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_mobile' => $request->customer_mobile,
            'address_id' => $request->address_id,

            'package_name' => $request->package_name,
            'person_count' => $request->person_count,

            'base_amount' => $request->base_amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'coupon_discount' => $request->coupon_discount ?? 0,
            'addon_amount' => $request->addon_amount ?? 0,
            'shipping_amount' => $request->shipping_amount ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total_amount' => $request->total_amount,

            'payment_status' => $request->payment_status,
            'booking_status' => $request->booking_status,
            'sankalp_status' => $request->sankalp_status,
            'puja_status' => $request->puja_status,
            'video_status' => $request->video_status,
            'prasad_status' => $request->prasad_status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('puja-bookings.index')
            ->with('success', 'Puja booking created successfully.');
    }

    public function show(PujaBooking $pujaBooking)
    {
        $pujaBooking->load([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pujaSchedule',
            'devotees',
            'sankalp',
            'payments',
            'assignments'
        ]);

        return view('admin.puja-bookings.show', compact('pujaBooking'));
    }

    public function edit(PujaBooking $pujaBooking)
    {
        $users = User::orderBy('name')->get();

        $temples = Temple::where('status', 1)
            ->orderBy('name')
            ->get();

        $deities = Deity::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujas = Puja::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujaPackages = PujaPackage::where('status', 1)
            ->orderBy('name')
            ->get();

        $pujaSchedules = PujaSchedule::where('status', 1)
            ->orderBy('puja_date')
            ->get();

        return view('admin.puja-bookings.edit', compact(
            'pujaBooking',
            'users',
            'temples',
            'deities',
            'pujas',
            'pujaPackages',
            'pujaSchedules'
        ));
    }

    public function update(Request $request, PujaBooking $pujaBooking)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'temple_id' => 'required|exists:temples,id',
            'deity_id' => 'required|exists:deities,id',
            'puja_id' => 'required|exists:pujas,id',
            'puja_package_id' => 'required|exists:puja_packages,id',
            'puja_schedule_id' => 'required|exists:puja_schedules,id',

            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_mobile' => 'required|string|max:20',

            'address_id' => 'nullable|integer',

            'package_name' => 'required|string|max:255',
            'person_count' => 'required|integer|min:1',

            'base_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'coupon_discount' => 'nullable|numeric|min:0',
            'addon_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',

            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:new,payment_verified,confirmed,cancelled,completed',
            'sankalp_status' => 'required|in:pending,completed',
            'puja_status' => 'required|in:pending,scheduled,completed',
            'video_status' => 'required|in:pending,available',
            'prasad_status' => 'required|in:pending,packed,shipped,delivered',
        ]);

        $pujaBooking->update([
            'user_id' => $request->user_id,
            'temple_id' => $request->temple_id,
            'deity_id' => $request->deity_id,
            'puja_id' => $request->puja_id,
            'puja_package_id' => $request->puja_package_id,
            'puja_schedule_id' => $request->puja_schedule_id,

            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_mobile' => $request->customer_mobile,
            'address_id' => $request->address_id,

            'package_name' => $request->package_name,
            'person_count' => $request->person_count,

            'base_amount' => $request->base_amount,
            'discount_amount' => $request->discount_amount ?? 0,
            'coupon_discount' => $request->coupon_discount ?? 0,
            'addon_amount' => $request->addon_amount ?? 0,
            'shipping_amount' => $request->shipping_amount ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total_amount' => $request->total_amount,

            'payment_status' => $request->payment_status,
            'booking_status' => $request->booking_status,
            'sankalp_status' => $request->sankalp_status,
            'puja_status' => $request->puja_status,
            'video_status' => $request->video_status,
            'prasad_status' => $request->prasad_status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('puja-bookings.index')
            ->with('success', 'Puja booking updated successfully.');
    }

    public function destroy(PujaBooking $pujaBooking)
    {
        $pujaBooking->update([
            'booking_status' => 'cancelled'
        ]);

        return redirect()
            ->route('puja-bookings.index')
            ->with('success', 'Puja booking cancelled successfully.');
    }
}
