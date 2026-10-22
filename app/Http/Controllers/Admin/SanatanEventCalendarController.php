<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SanatanEventCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $isAdmin = $user->hasRole('Admin');
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isCustomer = $user->hasRole('Customer');
        $isEventCreator = $user->hasRole('Event Creator');

        if (!$isAdmin && !$isSuperAdmin && !$isCustomer && !$isEventCreator) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $month = $request->query('month', now()->format('Y-m'));

        try {
            $currentMonth = Carbon::createFromFormat('!Y-m', $month);

            if ($currentMonth->format('Y-m') !== $month) {
                throw new \InvalidArgumentException('Invalid month.');
            }
        } catch (\Throwable $e) {
            $currentMonth = now()->startOfMonth();
        }

        $startDate = $currentMonth->copy()->startOfMonth();
        $endDate = $currentMonth->copy()->endOfMonth();

        $query = DB::table('sanatan_event_orders as bookings')
            ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
            ->leftJoin('users as customers', 'customers.id', '=', 'bookings.user_id')
            ->leftJoin('users as event_creators', 'event_creators.id', '=', 'events.created_by')
            ->leftJoin('users as cancelled_users', 'cancelled_users.id', '=', 'bookings.cancelled_by')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'event_creators.name as event_creator_name',
                'cancelled_users.name as cancelled_by_name'
            )
            ->whereDate('bookings.event_date', '>=', $startDate->toDateString())
            ->whereDate('bookings.event_date', '<=', $endDate->toDateString());

        if (!$isAdmin && !$isSuperAdmin) {
            if ($isCustomer && $isEventCreator) {
                $query->where(function ($q) use ($user) {
                    $q->where('bookings.user_id', $user->id)
                        ->orWhere('events.created_by', $user->id);
                });
            } elseif ($isCustomer) {
                $query->where('bookings.user_id', $user->id);
            } elseif ($isEventCreator) {
                $query->where('events.created_by', $user->id);
            }
        }

        $bookings = $query
            ->orderBy('bookings.event_date')
            ->orderByDesc('bookings.created_at')
            ->get();

        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return Carbon::parse($booking->event_date)->format('Y-m-d');
        });

        $calendarStart = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEnd = $endDate->copy()->endOfWeek(Carbon::SATURDAY);
        $calendarDays = collect();

        for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
            $day = $date->copy();
            $dateKey = $day->format('Y-m-d');

            $calendarDays->push([
                'date' => $dateKey,
                'day' => $day->day,
                'isCurrentMonth' => $day->month === $currentMonth->month,
                'isToday' => $day->isToday(),
                'bookings' => $bookingsByDate->get($dateKey, collect()),
            ]);
        }

        $statusCounts = [
            'pending' => $bookings->filter(fn ($booking) => strtolower($booking->booking_status ?? '') === 'pending')->count(),
            'confirmed' => $bookings->filter(fn ($booking) => strtolower($booking->booking_status ?? '') === 'confirmed')->count(),
            'cancelled' => $bookings->filter(fn ($booking) => strtolower($booking->booking_status ?? '') === 'cancelled')->count(),
        ];

        return view('admin.sanatan-events-calendar.index', compact(
            'currentMonth',
            'calendarDays',
            'bookings',
            'bookingsByDate',
            'statusCounts'
        ));
    }
}