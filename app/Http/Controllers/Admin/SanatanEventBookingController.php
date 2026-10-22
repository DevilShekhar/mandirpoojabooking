<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use RuntimeException;
use Throwable;

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
        $isEventCreator = $user->hasRole('Event Creator');

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
            );
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
                } else {
                    abort(403, 'You are not authorized to view these bookings.');
                }
            }
        $bookings = $query->orderByDesc('bookings.created_at')->paginate(10);
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
        $isEventCreator = $user->hasRole('Event Creator');

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
            ->where('bookings.id', $id);

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
            } else {
                abort(403, 'You are not authorized to view this booking.');
            }
        }

        $booking = $query->first();

        if (!$booking) {
            abort(404, 'Booking not found or you are not authorized to view it.');
        }

        $refreshableStatuses = ['pending', 'processing', 'created'];

        if (
            in_array($booking->refund_status ?? '', $refreshableStatuses, true) &&
            !empty($booking->razorpay_refund_id)
        ) {
            try {
                $api = new Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $refund = $api->refund->fetch($booking->razorpay_refund_id);
                $refundStatus = $refund->status ?? ($booking->refund_status ?? 'pending');

                $updates = [
                    'refund_status' => $refundStatus,
                    'updated_at' => now(),
                ];

                if ($refundStatus === 'processed' && empty($booking->refunded_at)) {
                    $updates['refunded_at'] = now();
                }

                DB::table('sanatan_event_orders')
                    ->where('id', $booking->id)
                    ->update($updates);

                $booking->refund_status = $refundStatus;

                if (array_key_exists('refunded_at', $updates)) {
                    $booking->refunded_at = $updates['refunded_at'];
                }
            } catch (Throwable $e) {
                Log::warning('Unable to refresh Razorpay refund status.', [
                    'booking_id' => $booking->id,
                    'refund_id' => $booking->razorpay_refund_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('admin.sanatanevents.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole(['Customer', 'Event Creator'])) {
            abort(403, 'Only customers and event creators can cancel bookings.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        try {
            $booking = DB::transaction(function () use ($id, $user, $validated) {
                $bookingQuery = DB::table('sanatan_event_orders as bookings')
                    ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
                    ->where('bookings.id', $id);

                $isCustomer = $user->hasRole('Customer');
                $isEventCreator = $user->hasRole('Event Creator');

                if ($isCustomer && $isEventCreator) {
                    $bookingQuery->where(function ($q) use ($user) {
                        $q->where('bookings.user_id', $user->id)
                            ->orWhere('events.created_by', $user->id);
                    });
                } elseif ($isCustomer) {
                    $bookingQuery->where('bookings.user_id', $user->id);
                } elseif ($isEventCreator) {
                    $bookingQuery->where('events.created_by', $user->id);
                }

                $booking = $bookingQuery
                    ->select('bookings.*')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    throw new RuntimeException('Booking not found or you are not authorized to cancel it.');
                }

                if ($booking->booking_status !== 'confirmed') {
                    throw new RuntimeException('Only confirmed bookings can be cancelled.');
                }

                if ($booking->payment_status !== 'paid') {
                    throw new RuntimeException('A successful payment is required for a refund.');
                }

                if (
                    ($booking->refund_status ?? 'not_requested') !== 'not_requested' ||
                    !empty($booking->razorpay_refund_id) ||
                    !empty($booking->cancelled_at)
                ) {
                    throw new RuntimeException('Cancellation or refund has already been requested.');
                }

                if (empty($booking->razorpay_payment_id)) {
                    throw new RuntimeException('Razorpay payment ID is missing. Please contact support.');
                }

                $refundAmount = (int) round((float) $booking->amount * 100);

                if ($refundAmount <= 0) {
                    throw new RuntimeException('The booking amount is invalid for a refund.');
                }

                DB::table('sanatan_event_orders')
                    ->where('id', $booking->id)
                    ->update([
                        'booking_status' => 'cancelled',
                        'cancellation_reason' => $validated['cancellation_reason'],
                        'cancelled_by' => $user->id,
                        'cancelled_at' => now(),
                        'refund_status' => 'processing',
                        'refund_amount' => $booking->amount,
                        'updated_at' => now(),
                    ]);

                return $booking;
            });
        } catch (RuntimeException $e) {
            return back()->withErrors(['booking' => $e->getMessage()]);
        } catch (Throwable $e) {
            Log::error('Sanatan event booking cancellation failed.', [
                'booking_id' => $id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'booking' => 'Unable to process the cancellation. Please try again or contact support.',
            ]);
        }

        try {
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $refundAmount = (int) round((float) $booking->amount * 100);

            $refund = $api->payment
                ->fetch($booking->razorpay_payment_id)
                ->refund([
                    'amount' => $refundAmount,
                    'notes' => [
                        'booking_id' => (string) $booking->id,
                        'cancelled_by' => (string) $user->id,
                        'cancellation_reason' => $validated['cancellation_reason'],
                    ],
                ]);

            $refundStatus = $refund->status ?? 'pending';

            $updates = [
                'refund_status' => $refundStatus,
                'razorpay_refund_id' => $refund->id ?? null,
                'updated_at' => now(),
            ];

            if ($refundStatus === 'processed') {
                $updates['refunded_at'] = now();
            }

            DB::table('sanatan_event_orders')
                ->where('id', $booking->id)
                ->update($updates);

            return redirect()
                ->route('customer.sanatan-event-bookings.show', $booking->id)
                ->with('success', 'Booking cancelled and refund request submitted successfully.');
        } catch (Throwable $e) {
            Log::error('Razorpay refund request could not be confirmed.', [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'razorpay_payment_id' => $booking->razorpay_payment_id,
                'error' => $e->getMessage(),
            ]);

            DB::table('sanatan_event_orders')
                ->where('id', $booking->id)
                ->where('refund_status', 'processing')
                ->update([
                    'refund_status' => 'pending',
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('customer.sanatan-event-bookings.show', $booking->id)
                ->withErrors([
                    'booking' => 'The booking has been cancelled, but the refund status could not be confirmed. Please check Razorpay before retrying or issuing a manual refund.',
                ]);
        }
    }
}