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
        $pujaBooking = PujaBooking::query()->where('id', $id)->orWhere('booking_number', $id)->firstOrFail();
        if (
            !$user->hasAnyRole(['Admin', 'Super Admin']) &&
            (int) $pujaBooking->created_by !== (int) $user->id &&
            (int) $pujaBooking->updated_by !== (int) $user->id
        ) {
            abort(403, 'You are not authorized to view this booking.');
        }
        $pujaBooking->load(['user', 'temple', 'deity', 'puja', 'pujaPackage', 'pujaSchedule']);
        $bookingPayments = collect();
        $paymentModel = new PujaBookingPayment();
        $paymentTable = $paymentModel->getTable();
        if (Schema::hasTable($paymentTable)) {
            $paymentForeignKey = Schema::hasColumn($paymentTable, 'puja_booking_id') ? 'puja_booking_id' : (Schema::hasColumn($paymentTable, 'booking_id') ? 'booking_id' : null);
            if ($paymentForeignKey) {
                $bookingPayments = PujaBookingPayment::where($paymentForeignKey, $pujaBooking->id)->latest()->get();
            }
        }
        $assignments = collect();
        $assignmentModel = new PujaAssignment();
        $assignmentTable = $assignmentModel->getTable();
        if (Schema::hasTable($assignmentTable)) {
            $assignmentForeignKey = Schema::hasColumn($assignmentTable, 'puja_booking_id') ? 'puja_booking_id' : (Schema::hasColumn($assignmentTable, 'booking_id') ? 'booking_id' : null);
            if ($assignmentForeignKey) {
                $assignmentQuery = PujaAssignment::query();
                $assignmentRelations = [];
                if (method_exists($assignmentModel, 'pandit')) {
                    $assignmentRelations[] = 'pandit';
                }
                if (method_exists($assignmentModel, 'user')) {
                    $assignmentRelations[] = 'user';
                }
                if (!empty($assignmentRelations)) {
                    $assignmentQuery->with($assignmentRelations);
                }
                $assignments = $assignmentQuery->where($assignmentForeignKey, $pujaBooking->id)->latest()->get();
            }
        }
        return view('admin.booking-puja-order.show', compact('pujaBooking', 'bookingPayments', 'assignments'));
    }
    /**
     * Show bookings with successful payments.
     */
    public function successful()
    {
        $user = auth()->user();
        $query = PujaBooking::with([
            'user', 'temple', 'deity', 'puja', 'pujaPackage',
            'pujaSchedule', 'createdBy', 'updatedBy'
        ])->where('payment_status', 'paid');
        if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('updated_by', $user->id);
            });
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