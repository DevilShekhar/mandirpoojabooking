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
use App\Models\HomeBanner;
use App\Models\Deity;
use App\Models\PujaCategory;
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
        $completedPujaBookingCount = 0;
        $completedPujaBookingAmount = 0;
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
        $homeBannerCount = 0;
        $templeRegistrationCount = 0;
        $pendingTempleRegistrationCount = 0;
        $approvedTempleRegistrationCount = 0;
        $rejectedTempleRegistrationCount = 0;
        $totalUsersCount = 0;
        $deityCount = 0;
        $pujaCategoryCount = 0;
        $ticketPaidCount = 0;
        $ticketPaidAmount = 0;
        $ticketCancelledCount = 0;
        $ticketCancelledAmount = 0;

        $eventPaidCount = 0;
        $eventPaidAmount = 0;
        $eventCancelledCount = 0;
        $eventCancelledAmount = 0;
        $eventRefundCount = 0;
        $eventRefundAmount = 0;

        $pujaPaidCount = 0;
        $pujaPaidAmount = 0;
        $pujaCancelledCount = 0;
        $pujaCancelledAmount = 0;
        $pujaRefundCount = 0;
        $pujaRefundAmount = 0;
        $completedSanatanEventBookingCount = 0;
        $completedSanatanEventBookingAmount = 0;
        $completedSanatanEventBookings = collect();
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
                // Completed bookings belonging to this Event Creator's events
$completedSanatanEventBookingQuery = DB::table('sanatan_event_orders as bookings')
    ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
    ->where('events.created_by', $user->id)
    ->where('bookings.booking_status', 'completed');

// Total completed bookings
$completedSanatanEventBookingCount = (clone $completedSanatanEventBookingQuery)
    ->count();

// Total amount from completed bookings
$completedSanatanEventBookingAmount = (clone $completedSanatanEventBookingQuery)
    ->sum('bookings.amount');
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
            $completedPujaQuery = PujaBooking::where('pandit_id', $user->id)->where('booking_status', 'completed');
            $completedPujaBookingCount = (clone $completedPujaQuery)->count();
            $completedPujaBookingAmount = (clone $completedPujaQuery)->sum('total_amount');
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
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            $homeBannerCount = HomeBanner::count();
            $sanatanEventQuery = Sanatan::query();
            $sanatanEventCount = (clone $sanatanEventQuery)->count();
            $activeSanatanEventCount = (clone $sanatanEventQuery)->where('status', 1)->count();
            $inactiveSanatanEventCount = (clone $sanatanEventQuery)->where('status', 0)->count();
            $sanatanEvents = (clone $sanatanEventQuery)->latest()->take(5)->get();
            $templeRegistrationCount = DB::table('temple_user')->count();
            $pendingTempleRegistrationCount = DB::table('temple_user')->where('approval_status', 'pending')->count();
            $approvedTempleRegistrationCount = DB::table('temple_user')->where('approval_status', 'approved')->count();
            $rejectedTempleRegistrationCount = DB::table('temple_user')->where('approval_status', 'rejected')->count();
            $totalUsersCount = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Super Admin']);
            })->count();
            $newsCount = News::count();
            $samgriBookingQuery = PujaBooking::whereNotNull('selected_products')
                ->whereJsonLength('selected_products', '>', 0)
                ->where('samgri_otp_verified', 0)
                ->whereDate('schedule_date', '>=', today());

            $samgriBookingCount = $samgriBookingQuery->count();

            $completedSamgriBookingQuery = PujaBooking::whereNotNull('selected_products')
                ->whereJsonLength('selected_products', '>', 0)
                ->where('samgri_otp_verified', 1);

            $completedSamgriBookingCount = $completedSamgriBookingQuery->count();
            $deityCount = Deity::count();
            $pujaCategoryCount = PujaCategory::count();
            $pujaCount = Puja::count();
            $sanatanEventBookingCount = DB::table('sanatan_event_orders')->count();
            $successfulPujaBookingCount = PujaBooking::where('payment_status', 'paid')->count();
            $failedPujaPaymentCount = PujaBookingPayment::where('status', 'failed')->count();
            $cancelledPujaBookingCount = PujaBooking::where('booking_status', 'cancelled')->count();
            $ticketPaidQuery = DB::table('ticket_bookings')->where('payment_status', 'paid')->where('booking_status', 'confirmed');
            $ticketPaidCount = (clone $ticketPaidQuery)->distinct('razorpay_order_id')->count('razorpay_order_id');
            $ticketPaidAmount = (clone $ticketPaidQuery)->sum('price');

            $ticketCancelledQuery = DB::table('ticket_bookings')->where('booking_status', 'cancelled');
            $ticketCancelledCount = (clone $ticketCancelledQuery)->distinct('razorpay_order_id')->count('razorpay_order_id');
            $ticketCancelledAmount = (clone $ticketCancelledQuery)->sum('price');

            $eventPaidQuery = DB::table('sanatan_event_orders')->where('payment_status', 'paid');
            $eventPaidCount = (clone $eventPaidQuery)->count();
            $eventPaidAmount = (clone $eventPaidQuery)->sum('amount');

            $eventCancelledQuery = DB::table('sanatan_event_orders')->where('booking_status', 'cancelled');
            $eventCancelledCount = (clone $eventCancelledQuery)->count();
            $eventCancelledAmount = (clone $eventCancelledQuery)->sum('amount');

            $eventRefundQuery = DB::table('sanatan_event_orders')->where('refund_status', 'processed');
            $eventRefundCount = (clone $eventRefundQuery)->count();
            $eventRefundAmount = (clone $eventRefundQuery)->sum('refund_amount');

            $pujaPaidQuery = PujaBooking::where('payment_status', 'paid');
            $pujaPaidCount = (clone $pujaPaidQuery)->count();
            $pujaPaidAmount = (clone $pujaPaidQuery)->sum('total_amount');

            $pujaCancelledQuery = PujaBooking::where('booking_status', 'cancelled');
            $pujaCancelledCount = (clone $pujaCancelledQuery)->count();
            $pujaCancelledAmount = (clone $pujaCancelledQuery)->sum('total_amount');

            $pujaRefundQuery = CustomerBookingCancellation::where('refund_status', 'processed');
            $pujaRefundCount = (clone $pujaRefundQuery)->count();
            $pujaRefundAmount = (clone $pujaRefundQuery)->sum('refund_amount');
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
            'homeBannerCount',
            'templeRegistrationCount',
            'pendingTempleRegistrationCount',
            'approvedTempleRegistrationCount',
            'rejectedTempleRegistrationCount',
            'totalUsersCount',
            'deityCount',
            'pujaCategoryCount',
            'ticketPaidCount',
            'ticketPaidAmount',
            'ticketCancelledCount',
            'ticketCancelledAmount',
            'eventPaidCount',
            'eventPaidAmount',
            'eventCancelledCount',
            'eventCancelledAmount',
            'eventRefundCount',
            'eventRefundAmount',
            'pujaPaidCount',
            'pujaPaidAmount',
            'pujaCancelledCount',
            'pujaCancelledAmount',
            'pujaRefundCount',
            'pujaRefundAmount',
            'completedPujaBookingCount',
            'completedPujaBookingAmount',
            'completedSanatanEventBookingCount',
            'completedSanatanEventBookingAmount',
        ));
    }
}
