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
            ->leftJoin('users as completed_users', 'completed_users.id', '=', 'bookings.completed_by')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'event_creators.name as event_creator_name',
                'cancelled_users.name as cancelled_by_name',
                'completed_users.name as completed_by_name'
            );
        // Role-based access
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
        // Get all booking statuses
        $bookings = $query->orderByDesc('bookings.created_at')->get();
        return view('admin.sanatanevents.bookings.index',compact('bookings'));
    }

    /**
     * Display completed bookings according to logged-in user role.
     */
    public function completed()
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
            ->leftJoin('users as completed_users', 'completed_users.id', '=', 'bookings.completed_by')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'event_creators.name as event_creator_name',
                'cancelled_users.name as cancelled_by_name',
                'completed_users.name as completed_by_name'
            )
            ->where('bookings.booking_status', 'completed');

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

        $bookings = $query
            ->orderByDesc('bookings.completed_at')
            ->get();

        return view(
            'admin.sanatanevents.bookings.index',
            compact('bookings')
        );
    }

    /**
     * Display individual booking details.
     */
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
            ->leftJoin('users as completed_users', 'completed_users.id', '=', 'bookings.completed_by')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'event_creators.name as event_creator_name',
                'cancelled_users.name as cancelled_by_name',
                'completed_users.name as completed_by_name'
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

        $canManageBooking = false; // Enable only after verifying the temple/pandit assignment relationship.

        return view('admin.sanatanevents.bookings.show', compact('booking', 'canManageBooking'));
    }

    /**
     * Mark a confirmed and paid booking as completed.
     */
    public function complete($id)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole([
            'Admin',
            'Super Admin',
            'Event Creator'
        ])) {
            abort(403, 'You are not authorized to complete bookings.');
        }

        try {

            DB::transaction(function () use ($id, $user) {

                $query = DB::table('sanatan_event_orders as bookings')
                    ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
                    ->where('bookings.id', $id);

                // Event Creator can complete only their own event bookings.
                // Admin and Super Admin can complete any booking.
                if (!$user->hasAnyRole(['Admin', 'Super Admin'])) {
                    $query->where('events.created_by', $user->id);
                }

                $booking = $query
                    ->select('bookings.*')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    throw new RuntimeException(
                        'Booking not found or you are not authorized to complete it.'
                    );
                }

                if ($booking->booking_status !== 'confirmed') {
                    throw new RuntimeException(
                        'Only confirmed bookings can be marked as completed.'
                    );
                }

                if ($booking->payment_status !== 'paid') {
                    throw new RuntimeException(
                        'Only successfully paid bookings can be completed.'
                    );
                }

                if (!empty($booking->completed_at)) {
                    throw new RuntimeException(
                        'This booking has already been completed.'
                    );
                }

                if (!empty($booking->cancelled_at)) {
                    throw new RuntimeException(
                        'Cancelled bookings cannot be completed.'
                    );
                }

                DB::table('sanatan_event_orders')
                    ->where('id', $booking->id)
                    ->update([
                        'booking_status' => 'completed',
                        'completed_by' => $user->id,
                        'completed_at' => now(),
                        'updated_at' => now(),
                    ]);
            });

            return redirect()
                ->route('customer.sanatan-event-bookings.show', $id)
                ->with('success', 'Booking marked as completed successfully.');

        } catch (RuntimeException $e) {

            return back()->withErrors([
                'booking' => $e->getMessage(),
            ]);

        } catch (Throwable $e) {

            Log::error('Sanatan event booking completion failed.', [
                'booking_id' => $id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'booking' => 'Unable to comple te this booking. Please try again.',
            ]);
        }
    }
    /**
     * Cancel a Sanatan event booking and issue the applicable Razorpay refund.
     * Customer cancellation is permitted only within 72 hours of booking creation.
     * Customer refund percentage is based on elapsed time since bookings.created_at.
     * Admin/Super Admin (platform) and Event Creator (event/temple side) receive 100%.
     */
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole(['Customer', 'Event Creator', 'Admin', 'Super Admin'])) {
            abort(403, 'You are not authorized to cancel this booking.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        try {
            $result = DB::transaction(function () use ($id, $user, $validated) {
                $query = DB::table('sanatan_event_orders as bookings')
                    ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
                    ->where('bookings.id', $id);

                $isPlatformSide = $user->hasAnyRole(['Admin', 'Super Admin']);
                $isCustomer = $user->hasRole('Customer');
                $isEventCreator = $user->hasRole('Event Creator');

                if (!$isPlatformSide) {
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
                        throw new RuntimeException('You are not authorized to cancel this booking.');
                    }
                }

                $booking = $query->select('bookings.*', 'events.created_by as event_created_by')
                    ->lockForUpdate()
                    ->first();

                if (!$booking) {
                    throw new RuntimeException('Booking not found or you are not authorized to cancel it.');
                }
                if ($booking->booking_status !== 'confirmed') {
                    throw new RuntimeException('Only confirmed bookings can be cancelled.');
                }
                if ($booking->payment_status !== 'paid') {
                    throw new RuntimeException('A successful payment is required for cancellation and refund.');
                }
                if (!empty($booking->completed_at) || !empty($booking->cancelled_at)) {
                    throw new RuntimeException('Completed or already cancelled bookings cannot be cancelled.');
                }
                if (!in_array($booking->refund_status ?? 'not_requested', ['not_requested', 'not_required'], true)
                    || !empty($booking->razorpay_refund_id)) {
                    throw new RuntimeException('A cancellation or refund has already been requested.');
                }

                $eventDateTime = \Carbon\Carbon::parse(
                    $booking->event_date . ' ' . $booking->event_time,
                    'Asia/Kolkata'
                );
                $now = now('Asia/Kolkata');
                if ($now->greaterThanOrEqualTo($eventDateTime)) {
                    throw new RuntimeException('This event has already started or ended. Cancellation is not allowed.');
                }

                $isEventCreatorForThisBooking = $user->hasRole('Event Creator')
                    && (int) $user->id === (int) ($booking->event_created_by ?? 0);
                $isCustomerCancellation = $user->hasRole('Customer')
                    && !$user->hasAnyRole(['Admin', 'Super Admin'])
                    && !$isEventCreatorForThisBooking;

                if ($isCustomerCancellation) {
                    if (empty($booking->created_at)) {
                        throw new RuntimeException('Booking creation time is unavailable. Please contact support.');
                    }
                    $bookingCreatedAt = \Carbon\Carbon::parse($booking->created_at, 'Asia/Kolkata');
                    $customerDeadline = $bookingCreatedAt->copy()->addHours(72);

                    // Allow cancellation through the exact 72-hour deadline; block after it.
                    if ($now->greaterThan($customerDeadline)) {
                        throw new RuntimeException('Customer cancellation is allowed only within 72 hours after booking creation. The 72-hour cancellation window has expired.');
                    }

                    $refundPercentage = $this->calculateCustomerRefundPercentage($bookingCreatedAt, $now);
                } else {
                    // Event Creator = event/temple side; Admin/Super Admin = platform side.
                    $refundPercentage = 100;
                }

                $bookingAmount = (float) $booking->amount;
                $refundAmount = round(($bookingAmount * $refundPercentage) / 100, 2);
                if ($bookingAmount < 0) {
                    throw new RuntimeException('The booking amount is invalid.');
                }
                if ($refundPercentage > 0 && empty($booking->razorpay_payment_id)) {
                    throw new RuntimeException('Razorpay payment ID is missing. Please contact support.');
                }

                DB::table('sanatan_event_orders')->where('id', $booking->id)->update([
                    'booking_status' => 'cancelled',
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'cancelled_by' => $user->id,
                    'cancelled_at' => now(),
                    'refund_percentage' => $refundPercentage,
                    'refund_amount' => $refundAmount,
                    'refund_status' => $refundPercentage > 0 ? 'processing' : 'not_required',
                    'updated_at' => now(),
                ]);

                return (object) [
                    'booking' => $booking,
                    'refund_percentage' => $refundPercentage,
                    'refund_amount' => $refundAmount,
                ];
            });
        } catch (RuntimeException $e) {
            return back()->withErrors(['booking' => $e->getMessage()])->withInput();
        } catch (Throwable $e) {
            Log::error('Sanatan event booking cancellation failed.', [
                'booking_id' => $id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['booking' => 'Unable to process cancellation. Please try again or contact support.']);
        }

        $booking = $result->booking;
        $refundPercentage = $result->refund_percentage;
        $refundAmount = $result->refund_amount;

        // A 0% refund still cancels the booking; no Razorpay refund call is needed.
        if ($refundPercentage === 0) {
            return redirect()->route('customer.sanatan-event-bookings.show', $booking->id)
                ->with('success', 'Booking cancelled successfully. As per the cancellation policy, no refund is applicable.');
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $refundAmountPaise = (int) round($refundAmount * 100);
            $refund = $api->payment->fetch($booking->razorpay_payment_id)->refund([
                'amount' => $refundAmountPaise,
                'notes' => [
                    'booking_id' => (string) $booking->id,
                    'cancelled_by' => (string) $user->id,
                    'refund_percentage' => (string) $refundPercentage,
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
            DB::table('sanatan_event_orders')->where('id', $booking->id)->update($updates);

            return redirect()->route('customer.sanatan-event-bookings.show', $booking->id)
                ->with('success', "Booking cancelled. {$refundPercentage}% refund (" . number_format($refundAmount, 2) . ") submitted to Razorpay.");
        } catch (Throwable $e) {
            Log::error('Razorpay refund request could not be confirmed.', [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'razorpay_payment_id' => $booking->razorpay_payment_id,
                'refund_percentage' => $refundPercentage,
                'error' => $e->getMessage(),
            ]);
            DB::table('sanatan_event_orders')->where('id', $booking->id)
                ->where('refund_status', 'processing')->update([
                    'refund_status' => 'pending',
                    'updated_at' => now(),
                ]);
            return redirect()->route('customer.sanatan-event-bookings.show', $booking->id)
                ->withErrors(['booking' => 'Booking cancelled, but Razorpay refund status could not be confirmed. Please verify the refund in Razorpay before retrying.']);
        }
    }

    /**
     * Customer refund bands are based on elapsed time since booking creation.
     * Cancellation eligibility (maximum 72 hours) is checked separately in cancel().
     */
    private function calculateCustomerRefundPercentage($bookingCreatedAt, $now): int
    {
        $elapsedSeconds = $bookingCreatedAt->diffInSeconds($now);

        if ($elapsedSeconds >= 48 * 3600) return 10; // 48–72 hours
        if ($elapsedSeconds >= 24 * 3600) return 20; // 24–48 hours
        if ($elapsedSeconds >= 12 * 3600) return 30; // 12–24 hours
        if ($elapsedSeconds >= 6 * 3600) return 40;  // 6–12 hours
        if ($elapsedSeconds >= 3 * 3600) return 60;  // 3–6 hours
        if ($elapsedSeconds >= 1 * 3600) return 80;  // 1–3 hours
        return 100;                                  // Less than 1 hour
    }
    /**
     * Display cancelled event bookings.
     */
    public function cancelled()
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
            )
            ->where('bookings.booking_status', 'cancelled');
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
        $bookings = $query->orderByDesc('bookings.cancelled_at')->paginate(10);
        return view('admin.sanatanevents.bookings.cancelled',compact('bookings'));
    }
    /**
     * Display confirmed event bookings.
     */
    public function confirmed()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }
        $isAdmin = $user->hasRole('Admin');
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isCustomer = $user->hasRole('Customer');
        $isEventCreator = $user->hasRole('Event Creator');
        $query = DB::table('sanatan_event_orders as bookings')
            ->join('sanatans as events', 'events.id', '=', 'bookings.sanatan_id')
            ->leftJoin('users as customers', 'customers.id', '=', 'bookings.user_id')
            ->leftJoin('users as event_creators', 'event_creators.id', '=', 'events.created_by')
            ->select(
                'bookings.*',
                'events.name as event_name',
                'events.description as event_description',
                'events.created_by as event_created_by',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'customers.phone as customer_phone',
                'event_creators.name as event_creator_name'
            )
            ->where('bookings.booking_status', 'confirmed');
        // Role-based access
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
        $bookings = $query->orderByDesc('bookings.created_at')->paginate(10)->withQueryString();
        return view('admin.sanatanevents.bookings.confirmed',compact('bookings'));
    }
}