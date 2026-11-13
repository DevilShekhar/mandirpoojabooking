<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePujaOrder;
use Illuminate\Http\Request;

class MyOnlinePujaBookingController extends Controller
{
    /**
     * Display all bookings created by the logged-in user.
     */
    public function index()
    {
        $bookings = OnlinePujaOrder::with(['puja', 'package'])
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return view(
            'admin.my-online-pujabooking.index',
            compact('bookings')
        );
    }

    /**
     * Display booking details.
     */
    public function show($id)
    {
        
        $booking = OnlinePujaOrder::with([
            'puja',
            'package'
        ])
        ->where('created_by', auth()->id())
        ->findOrFail($id);

        return view(
            'admin.my-online-pujabooking.show',
            compact('booking')
        );
    }
}