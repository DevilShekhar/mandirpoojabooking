<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\TempleProduct;
use App\Models\Sanatan;
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
        $templeProductCount = 0;
        $templeProducts = collect();
        $samgriBookingCount = 0;
        $samgriBookings = collect();
        $completedSamgriBookingCount = 0;
        $completedSamgriBookings = collect();
        $sanatanEventCount = 0;
        $activeSanatanEventCount = 0;
        $inactiveSanatanEventCount = 0;
        $sanatanEvents = collect();
        $sanatanEventBookingCount = 0;
        $sanatanEventBookings = collect();
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
        if ($user->hasRole('Store Manager') && $user->temple_id) {
            $templeProductQuery = TempleProduct::where('temple_id', $user->temple_id)->where('created_by', $user->id);
            $templeProductCount = (clone $templeProductQuery)->count();
            $templeProducts = $templeProductQuery->latest()->take(5)->get();
            $samgriBookingQuery = PujaBooking::with(['user','temple','puja','pujaPackage',])
                ->where('temple_id', $user->temple_id)
                ->whereNotNull('selected_products')
                ->whereJsonLength('selected_products', '>', 0)
                ->where('samgri_otp_verified', 0)
                ->whereDate('schedule_date', '>=', today());
            $samgriBookingCount = (clone $samgriBookingQuery)->count();
            $samgriBookings = $samgriBookingQuery->orderBy('schedule_date', 'asc')->orderBy('schedule_time', 'asc')
                ->take(5)
                ->get();
            $completedSamgriQuery = PujaBooking::with([
                'user',
                'temple',
                'puja',
                'pujaPackage',
                'samgriVerification.verifiedBy',
            ])
                ->where('temple_id', $user->temple_id)
                ->whereNotNull('selected_products')
                ->whereJsonLength('selected_products', '>', 0)
                ->where('samgri_otp_verified', 1);
            $completedSamgriBookingCount = (clone $completedSamgriQuery)->count();
            $completedSamgriBookings = $completedSamgriQuery->latest()->take(5)->get();
        }
        if ($user->hasRole('Event Creator')) {
            $sanatanEventQuery = Sanatan::where('created_by', $user->id);
            $sanatanEventCount = (clone $sanatanEventQuery)->count();
            $activeSanatanEventCount = (clone $sanatanEventQuery)->where('status', 1)->count();
            $inactiveSanatanEventCount = (clone $sanatanEventQuery)->where('status', 0)->count();
            $sanatanEvents = (clone $sanatanEventQuery)->latest()->take(5)->get();
            $sanatanEventBookingQuery = DB::table('sanatan_event_orders as bookings')
                ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
                ->leftJoin('users as customers', 'customers.id', '=', 'bookings.user_id')
                ->where('events.created_by', $user->id)
                ->select(
                    'bookings.*',
                    'events.name as event_name',
                    'customers.name as customer_name',
                    'customers.email as customer_email',
                    'customers.phone as customer_phone'
                );
            $sanatanEventBookingCount = (clone $sanatanEventBookingQuery)->count();
            $sanatanEventBookings = (clone $sanatanEventBookingQuery)
                ->orderByDesc('bookings.created_at')
                ->take(5)
                ->get();
        }
        return view('dashboard', compact(
            'bookingCount',
            'bookings',
            'sanatanEventBookingCount',
            'sanatanEventBookings',
            'cancellationCount',
            'cancellations',
            'ticketBookingCount',
            'ticketBookings',
            'templeProductCount',
            'templeProducts',
            'samgriBookingCount',
            'samgriBookings',
            'completedSamgriBookingCount',
            'completedSamgriBookings',
            'sanatanEventCount',
            'sanatanEvents',
        ));
    }
}
