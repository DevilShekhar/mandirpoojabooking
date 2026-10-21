<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SanatanEventBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $isAdmin = $user->hasRole('Admin');
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isCustomer = $user->hasRole('Customer');

        $query = DB::table('sanatan_event_orders as bookings')
            ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
            ->leftJoin('users as customers', 'customers.id', '=', 'bookings.user_id')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone'
            );

        if ($isCustomer) {
            $query->where('bookings.user_id', $user->id);
        } elseif (!$isAdmin && !$isSuperAdmin) {
            $query->where('events.created_by', $user->id);
        }

        $bookings = $query
            ->orderByDesc('bookings.created_at')
            ->paginate(10);

        return view('admin.sanatanevents.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view this booking.');
        }

        $isAdmin = $user->hasRole('Admin');
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isCustomer = $user->hasRole('Customer');

        $query = DB::table('sanatan_event_orders as bookings')
            ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
            ->leftJoin('users as customers', 'customers.id', '=', 'bookings.user_id')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone'
            )
            ->where('bookings.id', $id);

        if ($isCustomer) {
            $query->where('bookings.user_id', $user->id);
        } elseif (!$isAdmin && !$isSuperAdmin) {
            $query->where('events.created_by', $user->id);
        }

        $booking = $query->first();

        if (!$booking) {
            abort(404, 'Booking not found or you are not authorized to view it.');
        }

        return view('admin.sanatanevents.bookings.show', compact('booking'));
    }
}