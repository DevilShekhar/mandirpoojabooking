<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\TempleProduct;
use App\Models\Sanatan;
use App\Models\CustomerBookingCancellation;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\News;
use App\Models\Puja;
use App\Models\PujaBookingPayment;
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
        $successfulPujaBookingCount = 0;
        $successfulPujaBookings = collect();
        $failedPujaPaymentCount = 0;
        $failedPujaPayments = collect();
        $cancelledPujaBookingCount = 0;
        $cancelledPujaBookings = collect();
        $panditCount = 0;
        $pandits = collect();
        $newsCount = 0;
        $newsList = collect();
        $pujaCount = 0;
        $pujas = collect();
        $customerCancellationCount = 0;
        $customerCancellations = collect();
        $managerTicketBookingCount = 0;
        $managerTicketBookings = collect();
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
        if ($user->hasRole('Pandit')) {
            $successfulPujaQuery = PujaBooking::with([
                'user',
                'temple',
                'puja',
                'pujaPackage',
            ])->where('pandit_id', $user->id)->where('payment_status', 'paid');
            $successfulPujaBookingCount = (clone $successfulPujaQuery)->count();
            $successfulPujaBookings = (clone $successfulPujaQuery)
                ->latest()
                ->take(5)
                ->get();
            $failedPujaPaymentQuery = PujaBookingPayment::with([
                'user',
                'puja',
                'package',
                'booking',
            ])
            ->where('status', 'failed')
            ->whereHas('booking', function ($query) use ($user) {
                $query->where('pandit_id', $user->id);
            });
            $failedPujaPaymentCount = (clone $failedPujaPaymentQuery)->count();
            $failedPujaPayments = (clone $failedPujaPaymentQuery)
                ->latest()
                ->take(5)
                ->get();
            $cancelledPujaQuery = PujaBooking::with([
                'user',
                'temple',
                'puja',
                'pujaPackage',
            ])
            ->where('pandit_id', $user->id)
            ->where('booking_status', 'cancelled');
            $cancelledPujaBookingCount = (clone $cancelledPujaQuery)->count();
            $cancelledPujaBookings = (clone $cancelledPujaQuery)
                ->latest()
                ->take(5)
                ->get();
        }     
        if ($user->hasRole('Temple Manager')) {
            $templeIds = $user->temples()
                ->wherePivot('approval_status', 'approved')
                ->pluck('temples.id');
            $panditRole = Role::where('name', 'Pandit')->where('guard_name', 'web')->first();
            if ($panditRole && $templeIds->isNotEmpty()) {
                $panditQuery = User::query()
                    ->where(function ($query) use ($panditRole) {
                        $query->where('role_id', $panditRole->id)
                            ->orWhereHas('roles', function ($roleQuery) {
                                $roleQuery->where('name', 'Pandit')
                                    ->where('guard_name', 'web');
                            });
                    })
                    ->whereHas('temples', function ($templeQuery) use ($templeIds) {
                        $templeQuery->whereIn('temples.id', $templeIds)
                            ->where('temple_user.approval_status', 'approved');
                    })
                    ->with(['temples' => function ($query) use ($templeIds) {
                        $query->whereIn('temples.id', $templeIds)
                            ->wherePivot('approval_status', 'approved');
                    }]);
                $panditCount = (clone $panditQuery)->count();
                $pandits = (clone $panditQuery)->latest()->take(5)->get();
            }
            if ($templeIds->isNotEmpty()) {
                $newsQuery = News::with('temple')->whereIn('temple_id', $templeIds);
                $newsCount = (clone $newsQuery)->count();
                $newsList = (clone $newsQuery)->latest()->take(5)->get();
            }
            $pujaQuery = Puja::with(['temple', 'deity', 'pujaCategory']);
                if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
                    $pujaQuery->where('created_by', $user->id);
                }
            $pujaCount = (clone $pujaQuery)->count();
            $pujas = (clone $pujaQuery)->latest()->take(5)->get();
            $successfulPujaQuery = PujaBooking::with([
                    'user',
                    'temple',
                    'deity',
                    'puja',
                    'pujaPackage',
                    'pandit',
                ])
                    ->where('payment_status', 'paid')
                    ->whereIn('temple_id', $templeIds);
            $successfulPujaBookingCount = (clone $successfulPujaQuery)->count();
            $successfulPujaBookings = (clone $successfulPujaQuery)
                    ->latest()
                    ->take(5)
                    ->get();
            $failedPujaPaymentQuery = PujaBookingPayment::with([
                    'user',
                    'puja',
                    'package',
                    'booking',
                ])
                    ->where('status', 'failed')
                    ->whereHas('booking', function ($query) use ($templeIds) {
                        $query->whereIn('temple_id', $templeIds);
                    });
            $failedPujaPaymentCount = (clone $failedPujaPaymentQuery)->count();
            $failedPujaPayments = (clone $failedPujaPaymentQuery)
                    ->latest()
                    ->take(5)
                    ->get();
            $cancelledPujaQuery = PujaBooking::with([
                    'user',
                    'temple',
                    'deity',
                    'puja',
                    'pujaPackage',
                    'pandit',
                ])
                    ->where('booking_status', 'cancelled')
                    ->whereIn('temple_id', $templeIds);
            $cancelledPujaBookingCount = (clone $cancelledPujaQuery)->count();
            $cancelledPujaBookings = (clone $cancelledPujaQuery)
                ->latest()
                ->take(5)
                ->get();
                if ($templeIds->isNotEmpty()) {
                    $customerCancellationQuery = CustomerBookingCancellation::with([
                        'booking.user',
                        'booking.temple',
                        'booking.puja',
                        'booking.pujaPackage',
                        'customer',
                    ])->whereHas('booking', function ($query) use ($templeIds) {
                        $query->whereIn('temple_id', $templeIds);
                    });

                    $customerCancellationCount = (clone $customerCancellationQuery)->count();
                    $customerCancellations = (clone $customerCancellationQuery)->latest()->take(5)->get();
                }
                if ($templeIds->isNotEmpty()) {
                    $managerTicketBookingQuery = DB::table('ticket_bookings')
                        ->join('users', 'users.id', '=', 'ticket_bookings.user_id')
                        ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
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
                            'users.name',
                            'users.email',
                            'temples.name'
                        );

                    $managerTicketBookingCount = DB::query()->fromSub(clone $managerTicketBookingQuery, 'ticket_orders')->count();
                    $managerTicketBookings = (clone $managerTicketBookingQuery)->orderByDesc('latest_id')->take(5)->get();
                }
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
            'successfulPujaBookingCount',
            'successfulPujaBookings',
            'failedPujaPaymentCount',
            'failedPujaPayments',
            'cancelledPujaBookingCount',
            'cancelledPujaBookings',
            'panditCount',
            'pandits',
            'newsCount',
            'newsList',
            'pujaCount',
            'pujas',
            'customerCancellationCount',
            'customerCancellations',
            'managerTicketBookingCount',
            'managerTicketBookings',
        ));
    }
}
