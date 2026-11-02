<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\CustomerBookingCancellation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $bookingCount = 0;
        $bookings = collect();
        $sanatanEventBookingCount = 0;
        $sanatanEventBookings = collect();
        $cancellationCount = 0;
        $cancellations = collect();
        $ticketBookingCount = 0;
        $ticketBookings = collect();

        if ($user->hasRole('Customer')) {
            $bookingQuery = PujaBooking::where('user_id', $user->id);
            $bookingCount = (clone $bookingQuery)->count();
            $bookings = $bookingQuery
                ->with(['temple','puja','pujaPackage',])
                ->latest()->take(5)->get();
            $sanatanEventBookingQuery = DB::table('sanatan_event_orders as bookings')->where('bookings.user_id', $user->id);
            $sanatanEventBookingCount = (clone $sanatanEventBookingQuery)->count();
            $sanatanEventBookings = $sanatanEventBookingQuery
                ->leftJoin('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
                ->select('bookings.*','events.name as event_name')
                ->orderByDesc('bookings.created_at')->take(5)->get();
            $cancellationQuery = CustomerBookingCancellation::where('customer_id', $user->id);
            $cancellationCount = (clone $cancellationQuery)->count();
            $cancellations = $cancellationQuery
                ->with(['booking.puja','booking.pujaPackage',])
                ->latest()->take(5)->get();
            $ticketBookingQuery = DB::table('ticket_bookings')
                ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
                ->where('ticket_bookings.user_id', $user->id)
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
                    DB::raw('MIN(ticket_bookings.booking_reference) AS booking_reference'),
                    DB::raw('COUNT(ticket_bookings.id) AS total_members'),
                    DB::raw('SUM(ticket_bookings.price) AS total_amount'),
                    DB::raw('MAX(ticket_bookings.id) AS latest_id')
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
            $ticketBookingCount = DB::query()->fromSub(clone $ticketBookingQuery, 'ticket_orders')->count();
            $ticketBookings = (clone $ticketBookingQuery)->orderByDesc('latest_id')->take(5)->get();
        }
        return view('dashboard', compact(
            'bookingCount',
            'bookings',
            'sanatanEventBookingCount',
            'sanatanEventBookings',
            'cancellationCount',
            'cancellations',
            'ticketBookingCount',
            'ticketBookings'
        ));
    }
}
