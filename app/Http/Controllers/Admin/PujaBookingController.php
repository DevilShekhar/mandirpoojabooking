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
use App\Models\PujaBookingPayment;
use App\Models\BookingDevotee;
use App\Models\BookingSankalp;
use App\Models\PujaAssignment;
use Illuminate\Support\Facades\Schema;
class PujaBookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pujaSchedule',
            'createdBy',
            'updatedBy'
        ]);
        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('updated_by', $user->id);
            });
        }
        $bookings = $query->latest()->paginate(10);
        return view('admin.puja-bookings.index', compact('bookings'));
    }
    public function show($id)
    {
        $user = auth()->user();
        $pujaBooking = PujaBooking::query()
            ->where('id', $id)
            ->orWhere('booking_number', $id)
            ->firstOrFail();
        $isAuthorized = $user->hasAnyRole(['Admin', 'Super Admin']);
        if (!$isAuthorized && $user->hasRole('Temple Manager')) {
            $panditIds = User::where('created_by', $user->id)
                ->where('role_id', 5)
                ->pluck('id');
            $templeIds = \Illuminate\Support\Facades\DB::table('temple_user')
                ->whereIn('user_id', $panditIds)
                ->where('approval_status', 'approved')
                ->pluck('temple_id')
                ->unique();
            $isAuthorized = $templeIds->contains(
                (int) $pujaBooking->temple_id
            );
        }
        if (!$isAuthorized && $user->hasRole('Pandit')) {
            $isAuthorized = (int) $pujaBooking->pandit_id === (int) $user->id;
        }
        if (!$isAuthorized && (
                (int) $pujaBooking->created_by === (int) $user->id ||
                (int) $pujaBooking->updated_by === (int) $user->id ||
                (int) $pujaBooking->user_id === (int) $user->id
            )
        ) {
            $isAuthorized = true;
        }
        if (!$isAuthorized) {
            abort(403, 'You are not authorized to view this booking.');
        }
        $pujaBooking->load([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pujaSchedule',
            'pandit',
            'createdBy',
            'updatedBy',
        ]);
        $bookingPayments = collect();
        $paymentModel = new PujaBookingPayment();
        $paymentTable = $paymentModel->getTable();
        if (Schema::hasTable($paymentTable)) {
            $paymentForeignKey = Schema::hasColumn($paymentTable, 'puja_booking_id')
                ? 'puja_booking_id'
                : (Schema::hasColumn($paymentTable, 'booking_id') ? 'booking_id' : null);
            if ($paymentForeignKey) {
                $bookingPayments = PujaBookingPayment::where(
                    $paymentForeignKey,
                    $pujaBooking->id
                )->latest()->get();
            }
        }
        $assignments = collect();
        return view('admin.booking-puja-order.show', compact(
            'pujaBooking',
            'bookingPayments',
            'assignments'
        ));
    }

    public function successful()
    {
        $user = auth()->user();
        $query = PujaBooking::with([
            'user',
            'temple',
            'deity',
            'puja',
            'pujaPackage',
            'pujaSchedule',
            'pandit',
            'createdBy',
            'updatedBy',
            'payments'
        ])->where('payment_status', 'paid');
        if ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            // Show all successful bookings.
        } elseif ($user->hasRole('Temple Manager')) {
            $panditIds = \App\Models\User::where('created_by', $user->id)
                ->where('role_id', 5)
                ->pluck('id');
            $query->whereIn('pandit_id', $panditIds);
        } elseif ($user->hasRole('Pandit')) {
            $query->where('pandit_id', $user->id);
        } else {
            $query->where('user_id', $user->id);
        }
        $bookings = $query->latest()->paginate(10);
        return view('admin.booking-puja-order.successful', compact('bookings'));
    }

    public function failed()
    {
        $user = auth()->user();
        $query = PujaBookingPayment::with([
            'user',
            'puja',
            'package',
            'booking',
        ])->where('status', 'failed');
        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where('user_id', $user->id);
        }
        $payments = $query->latest()->paginate(10);
        return view('admin.booking-puja-order.failed', compact('payments')
        );
    }
    /**
     * Show cancelled bookings.
     */
    public function cancelled()
    {
        $user = auth()->user();
        $query = PujaBooking::with([
            'user', 'temple', 'deity', 'puja', 'pujaPackage',
            'pujaSchedule', 'createdBy', 'updatedBy'
        ])->where('booking_status', 'cancelled');
        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('updated_by', $user->id);
            });
        }
        $bookings = $query->latest()->paginate(10);
        return view('admin.booking-puja-order.cancelled', compact('bookings'));
    }
    public function destroy(PujaBooking $pujaBooking)
    {
        $user = auth()->user();
        if (
            !$user->hasAnyRole(['Admin', 'Super Admin']) &&
            $pujaBooking->created_by != $user->id &&
            $pujaBooking->updated_by != $user->id
        ) {
            abort(403, 'You are not authorized to cancel this booking.');
        }
        $pujaBooking->update([
            'booking_status' => 'cancelled',
            'updated_by' => $user->id,
        ]);
        return redirect()->route('booking-puja-order.index')->with('success', 'Puja booking cancelled successfully.');
    }
}