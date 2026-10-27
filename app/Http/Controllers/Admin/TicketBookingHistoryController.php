<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketBookingHistoryController extends Controller
{
    /**
     * Customer: Show one summary row per Razorpay order.
     */
    public function myBookings(Request $request)
    {
        $query = DB::table('ticket_bookings')
            ->join(
                'temples',
                'temples.id',
                '=',
                'ticket_bookings.temple_id'
            )
            ->where('ticket_bookings.user_id', Auth::id())
            ->where('ticket_bookings.payment_status', 'paid')
            ->where('ticket_bookings.booking_status', 'confirmed')
            ->select(
                'ticket_bookings.razorpay_order_id',
                'ticket_bookings.user_id',
                'ticket_bookings.temple_id',
                'ticket_bookings.booking_date',
                'ticket_bookings.payment_status',
                'ticket_bookings.booking_status',
                'temples.name as temple_name',
                DB::raw(
                    'MIN(ticket_bookings.booking_reference) AS booking_reference'
                ),
                DB::raw(
                    'COUNT(ticket_bookings.id) AS total_members'
                ),
                DB::raw(
                    'SUM(ticket_bookings.price) AS total_amount'
                ),
                DB::raw(
                    'MAX(ticket_bookings.id) AS latest_id'
                )
            )
            ->groupBy(
                'ticket_bookings.razorpay_order_id',
                'ticket_bookings.user_id',
                'ticket_bookings.temple_id',
                'ticket_bookings.booking_date',
                'ticket_bookings.payment_status',
                'ticket_bookings.booking_status',
                'temples.name'
            );

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'temples.name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'ticket_bookings.razorpay_order_id',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'ticket_bookings.booking_reference',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $bookings = $query
            ->orderByDesc('latest_id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.ticket-booking.my-index',
            compact('bookings')
        );
    }

    /**
     * Customer: Show all members belonging to their own order.
     */
    public function myBookingDetails(string $orderId)
    {
        abort_if(trim($orderId) === '', 404);

        $bookings = TicketBooking::with('temple')
            ->where('user_id', Auth::id())
            ->where('razorpay_order_id', $orderId)
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->orderBy('id')
            ->get();

        abort_if($bookings->isEmpty(), 404);

        return view(
            'admin.ticket-booking.my-details',
            compact('bookings')
        );
    }

    /**
     * Manager: Show one summary row per order for approved temples.
     */
    public function managerHistory(Request $request)
    {
        $templeIds = DB::table('temple_user')
            ->where('user_id', Auth::id())
            ->where('approval_status', 'approved')
            ->pluck('temple_id');

        $query = DB::table('ticket_bookings')
            ->join(
                'users',
                'users.id',
                '=',
                'ticket_bookings.user_id'
            )
            ->join(
                'temples',
                'temples.id',
                '=',
                'ticket_bookings.temple_id'
            )
            ->whereIn('ticket_bookings.temple_id', $templeIds)
            ->where('ticket_bookings.payment_status', 'paid')
            ->where('ticket_bookings.booking_status', 'confirmed')
            ->select(
                'ticket_bookings.razorpay_order_id',
                'ticket_bookings.user_id',
                'ticket_bookings.temple_id',
                'ticket_bookings.booking_date',
                'ticket_bookings.payment_status',
                'ticket_bookings.booking_status',
                'users.name as customer_name',
                'users.email as customer_email',
                'temples.name as temple_name',
                DB::raw(
                    'MIN(ticket_bookings.booking_reference) AS booking_reference'
                ),
                DB::raw(
                    'COUNT(ticket_bookings.id) AS total_members'
                ),
                DB::raw(
                    'SUM(ticket_bookings.price) AS total_amount'
                ),
                DB::raw(
                    'MAX(ticket_bookings.id) AS latest_id'
                )
            )
            ->groupBy(
                'ticket_bookings.razorpay_order_id',
                'ticket_bookings.user_id',
                'ticket_bookings.temple_id',
                'ticket_bookings.booking_date',
                'ticket_bookings.payment_status',
                'ticket_bookings.booking_status',
                'users.name',
                'users.email',
                'temples.name'
            );

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'users.name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'users.email',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'temples.name',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'ticket_bookings.razorpay_order_id',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $bookings = $query
            ->orderByDesc('latest_id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.ticket-booking.manager-index',
            compact('bookings')
        );
    }

    /**
     * Manager: Show all members for an order in approved temples.
     */
    public function managerBookingDetails(string $bookingReference)
    {
        $templeIds = DB::table('temple_user')
            ->where('user_id', Auth::id())
            ->where('approval_status', 'approved')
            ->pluck('temple_id');

        // The route parameter contains the Razorpay order ID.
        $bookings = TicketBooking::with(['temple', 'user'])
            ->where('razorpay_order_id', $bookingReference)
            ->whereIn('temple_id', $templeIds)
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->orderBy('id')
            ->get();

        abort_if($bookings->isEmpty(), 404);

        return view(
            'admin.ticket-booking.manager-details',
            compact('bookings')
        );
    }
}