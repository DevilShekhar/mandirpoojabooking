<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;
use Throwable;

class TicketBookingHistoryController extends Controller
{
    public function myBookings(Request $request)
    {
        $query = DB::table('ticket_bookings')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
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

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('temples.name', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.razorpay_order_id', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.booking_reference', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderByDesc('latest_id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ticket-booking.my-index', compact('bookings'));
    }
    public function cancelledBookings(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $isAdmin = $user->hasRole('Admin');
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isCustomer = $user->hasRole('Customer');

        if (!$isAdmin && !$isSuperAdmin && !$isCustomer) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $query = DB::table('ticket_bookings')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
            ->where('ticket_bookings.booking_status', 'cancelled')
            ->whereIn('ticket_bookings.payment_status', [
                'paid',
                'refunded',
                'partially_refunded'
            ]);

        // Customer: show only their own cancelled bookings
        if ($isCustomer) {
            $query->where('ticket_bookings.user_id', $user->id);
        }

        // Admin and Super Admin: show all customers' cancelled bookings

        $query->select(
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

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('temples.name', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.razorpay_order_id', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.booking_reference', 'like', "%{$search}%");
            });
        }

        $bookings = $query
            ->orderByDesc('latest_id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ticket-booking.cancel', compact('bookings'));
    }
    public function myBookingDetails(string $orderId)
    {
        $bookings = TicketBooking::with('temple')
            ->where('user_id', Auth::id())
            ->where('razorpay_order_id', $orderId)
            ->whereIn('payment_status', ['paid', 'refunded', 'partially_refunded'])
            ->whereIn('booking_status', ['confirmed', 'cancel_requested', 'cancelled'])
            ->orderBy('id')
            ->get();

        abort_if($bookings->isEmpty(), 404);

        $firstBooking = $bookings->first();
        $totalAmount = (float) $bookings->sum('price');

        $cancellationPolicy = $this->calculateCancellationPolicy(
            $firstBooking,
            $totalAmount
        );

        $existingCancellation = DB::table('ticket_booking_cancellations')
            ->where('razorpay_order_id', $orderId)
            ->where('user_id', Auth::id())
            ->whereIn('cancellation_status', ['pending', 'approved', 'cancelled'])
            ->latest('id')
            ->first();

        if (
            $existingCancellation &&
            !empty($existingCancellation->razorpay_refund_id) &&
            in_array($existingCancellation->refund_status, ['pending', 'processing'], true)
        ) {
            try {
                $api = new Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );
                $refund = $api->refund->fetch($existingCancellation->razorpay_refund_id);
                $latestRefundStatus = strtolower((string) ($refund['status'] ?? $existingCancellation->refund_status));
                $refundUpdates = [
                    'refund_status' => $latestRefundStatus,
                    'updated_at' => now(),
                ];
                if ($latestRefundStatus === 'processed' && Schema::hasColumn('ticket_booking_cancellations', 'refunded_at')) {
                    $refundUpdates['refunded_at'] = now();
                }
                DB::table('ticket_booking_cancellations')
                    ->where('id', $existingCancellation->id)
                    ->update($refundUpdates);
                $existingCancellation = DB::table('ticket_booking_cancellations')
                    ->where('id', $existingCancellation->id)
                    ->first();
                if ($latestRefundStatus === 'processed') {
                    TicketBooking::where('user_id', Auth::id())
                        ->where('razorpay_order_id', $orderId)
                        ->update([
                            'payment_status' => (float) $existingCancellation->refund_amount >= $totalAmount ? 'refunded' : 'partially_refunded',
                            'updated_at' => now(),
                        ]);
                    $bookings = TicketBooking::with('temple')
                        ->where('user_id', Auth::id())
                        ->where('razorpay_order_id', $orderId)
                        ->whereIn('booking_status', ['cancelled'])
                        ->orderBy('id')
                        ->get();
                }
            } catch (Throwable $e) {
                Log::warning('Unable to refresh ticket booking Razorpay refund status.', [
                    'order_id' => $orderId,
                    'refund_id' => $existingCancellation->razorpay_refund_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $canCancel = $cancellationPolicy['allowed']
            && !$existingCancellation;

        return view('admin.ticket-booking.my-details', compact(
            'bookings',
            'totalAmount',
            'cancellationPolicy',
            'existingCancellation',
            'canCancel'
        ));
    }

    public function managerHistory(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        $templeIds = DB::table('temple_user')
            ->where('user_id', $user->id)
            ->where('approval_status', 'approved')
            ->pluck('temple_id');

        if ($templeIds->isEmpty()) {
            $bookings = DB::table('ticket_bookings')
                ->whereRaw('1 = 0')
                ->paginate(15)
                ->withQueryString();

            return view('admin.ticket-booking.manager-index', compact('bookings'));
        }

        $query = DB::table('ticket_bookings')
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

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('temples.name', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.booking_reference', 'like', "%{$search}%")
                    ->orWhere('ticket_bookings.razorpay_order_id', 'like', "%{$search}%");
            });
        }

        $bookings = $query
            ->orderByDesc('latest_id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ticket-booking.manager-index', compact('bookings'));
    }

    public function managerBookingDetails(string $orderId)
    {
        $templeIds = DB::table('temple_user')
            ->where('user_id', Auth::id())
            ->where('approval_status', 'approved')
            ->pluck('temple_id');

        $bookings = TicketBooking::with(['temple', 'user'])
            ->where('razorpay_order_id', $orderId)
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

    public function storeCancellationRequest(Request $request, string $orderId)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:3|max:1000',
        ]);

        $cancellationId = null;
        $paymentId = null;
        $refundAmountPaise = 0;
        $capturedAmountPaise = 0;
        $policy = null;

        try {
            DB::transaction(function () use ($orderId, $validated, &$cancellationId, &$paymentId, &$refundAmountPaise, &$capturedAmountPaise, &$policy) {
                $bookings = TicketBooking::where('user_id', Auth::id())
                    ->where('razorpay_order_id', $orderId)
                    ->where('payment_status', 'paid')
                    ->where('booking_status', 'confirmed')
                    ->lockForUpdate()
                    ->get();

                if ($bookings->isEmpty()) {
                    throw ValidationException::withMessages([
                        'cancellation' => 'Booking not found or cancellation is not available.',
                    ]);
                }

                $existingRequest = DB::table('ticket_booking_cancellations')
                    ->where('razorpay_order_id', $orderId)
                    ->where('user_id', Auth::id())
                    ->whereIn('cancellation_status', ['pending', 'approved', 'cancelled'])
                    ->exists();

                if ($existingRequest) {
                    throw ValidationException::withMessages([
                        'cancellation' => 'A cancellation request already exists for this booking.',
                    ]);
                }

                $firstBooking = $bookings->first();
                $totalAmount = (float) $bookings->sum('price');
                $policy = $this->calculateCancellationPolicy($firstBooking, $totalAmount);

                if (!$policy['allowed']) {
                    throw ValidationException::withMessages([
                        'cancellation' => $policy['message'],
                    ]);
                }

                $paymentId = $bookings->pluck('razorpay_payment_id')->filter()->first();
                if ($policy['refund_amount'] > 0 && empty($paymentId)) {
                    throw ValidationException::withMessages([
                        'cancellation' => 'Razorpay payment ID is missing. Please contact support before cancellation.',
                    ]);
                }

                $insert = [
                    'razorpay_order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'temple_id' => $firstBooking->temple_id,
                    'booking_date' => $firstBooking->booking_date,
                    'total_amount' => $totalAmount,
                    'deduction_percentage' => $policy['deduction_percentage'],
                    'refund_percentage' => $policy['refund_percentage'],
                    'refund_amount' => $policy['refund_amount'],
                    'cancellation_status' => 'approved',
                    'refund_status' => $policy['refund_amount'] > 0 ? 'processing' : 'not_required',
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'requested_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (Schema::hasColumn('ticket_booking_cancellations', 'razorpay_payment_id')) {
                    $insert['razorpay_payment_id'] = $paymentId;
                }
                if (Schema::hasColumn('ticket_booking_cancellations', 'razorpay_refund_id')) {
                    $insert['razorpay_refund_id'] = null;
                }
                if (Schema::hasColumn('ticket_booking_cancellations', 'refund_error')) {
                    $insert['refund_error'] = null;
                }
                if (Schema::hasColumn('ticket_booking_cancellations', 'refunded_at')) {
                    $insert['refunded_at'] = null;
                }

                $cancellationId = DB::table('ticket_booking_cancellations')->insertGetId($insert);

                TicketBooking::where('user_id', Auth::id())
                    ->where('razorpay_order_id', $orderId)
                    ->where('booking_status', 'confirmed')
                    ->update([
                        'booking_status' => 'cancelled',
                        'updated_at' => now(),
                    ]);

                if ($policy['refund_amount'] <= 0) {
                    return;
                }
            });

            if ($policy['refund_amount'] > 0) {
                $api = new Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $payment = $api->payment->fetch($paymentId);
                $capturedAmountPaise = (int) ($payment['amount'] ?? 0);
                $alreadyRefundedPaise = (int) ($payment['amount_refunded'] ?? 0);
                $availableRefundPaise = max(0, $capturedAmountPaise - $alreadyRefundedPaise);
                $refundAmountPaise = (int) round((float) $policy['refund_amount'] * 100);

                if ($capturedAmountPaise <= 0 || $refundAmountPaise <= 0 || $refundAmountPaise > $availableRefundPaise) {
                    throw new \RuntimeException('Refund amount is invalid or exceeds the remaining captured payment amount.');
                }

                $refund = $api->payment->fetch($paymentId)->refund([
                    'amount' => $refundAmountPaise,
                    'notes' => [
                        'ticket_order_id' => (string) $orderId,
                        'cancellation_id' => (string) $cancellationId,
                        'customer_id' => (string) Auth::id(),
                        'cancellation_reason' => $validated['cancellation_reason'],
                    ],
                ]);

                $refundId = $refund['id'] ?? null;
                $refundStatus = strtolower((string) ($refund['status'] ?? 'pending'));
                if (!$refundId) {
                    throw new \RuntimeException('Razorpay did not return a refund ID. Verify the refund in Razorpay Dashboard before retrying.');
                }

                $processed = in_array($refundStatus, ['processed', 'completed'], true);
                $failed = $refundStatus === 'failed';
                $updates = [
                    'refund_status' => $processed ? 'processed' : ($failed ? 'failed' : 'processing'),
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('ticket_booking_cancellations', 'razorpay_refund_id')) {
                    $updates['razorpay_refund_id'] = $refundId;
                }
                if (Schema::hasColumn('ticket_booking_cancellations', 'refunded_at')) {
                    $updates['refunded_at'] = $processed ? now() : null;
                }
                if (Schema::hasColumn('ticket_booking_cancellations', 'refund_error')) {
                    $updates['refund_error'] = $failed ? 'Razorpay returned a failed refund status. Check Razorpay Dashboard.' : null;
                }
                DB::table('ticket_booking_cancellations')->where('id', $cancellationId)->update($updates);

                if ($processed) {
                    TicketBooking::where('user_id', Auth::id())
                        ->where('razorpay_order_id', $orderId)
                        ->update([
                            'payment_status' => $refundAmountPaise >= $capturedAmountPaise ? 'refunded' : 'partially_refunded',
                            'updated_at' => now(),
                        ]);
                }
            }

            return redirect()->route('admin.my-tickit-booking')->with(
                'success',
                $policy['refund_amount'] > 0
                    ? 'Booking cancelled. Refund request of ₹' . number_format((float) $policy['refund_amount'], 2) . ' has been submitted to Razorpay. The amount will be credited to the original payment method after Razorpay processes it.'
                    : 'Booking cancelled successfully. No refund is applicable under the cancellation policy.'
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Ticket booking cancellation or Razorpay refund failed.', [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'cancellation_id' => $cancellationId,
                'razorpay_payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);

            if ($cancellationId) {
                $updates = [
                    'refund_status' => 'failed',
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('ticket_booking_cancellations', 'refund_error')) {
                    $updates['refund_error'] = $e->getMessage();
                }
                DB::table('ticket_booking_cancellations')->where('id', $cancellationId)->update($updates);
            }

            return redirect()->route('admin.my-tickit-booking')->withErrors([
                'cancellation' => 'The booking has been cancelled, but the refund could not be confirmed. Please check Razorpay Dashboard before any manual retry.',
            ]);
        }
    }

    private function calculateCancellationPolicy(
        TicketBooking $booking,
        float $totalAmount
    ): array {
        if (empty($booking->created_at)) {
            return [
                'allowed' => false,
                'message' => 'Cancellation is unavailable because the booking creation time could not be determined.',
                'booking_created_at' => 'N/A',
                'elapsed_hours' => 0,
                'policy_slab' => 'Not available',
                'deduction_percentage' => 0,
                'refund_percentage' => 0,
                'deduction_amount' => 0,
                'refund_amount' => 0,
            ];
        }

        $createdAt = Carbon::parse($booking->created_at);
        $now = now();
        $elapsedSeconds = max(0, $createdAt->diffInSeconds($now, false));
        $elapsedHours = $elapsedSeconds / 3600;

        $result = [
            'allowed' => false,
            'message' => '',
            'booking_created_at' => $createdAt->format('d-m-Y h:i A'),
            'elapsed_hours' => round($elapsedHours, 2),
            'policy_slab' => '',
            'deduction_percentage' => 0,
            'refund_percentage' => 0,
            'deduction_amount' => 0,
            'refund_amount' => 0,
        ];
        if ($elapsedSeconds >= 72 * 3600) {
            $result['message'] = 'Cancellation is not allowed after 72 hours from booking creation.';
            $result['policy_slab'] = '72 hours or more';

            return $result;
        }
        if ($elapsedHours < 1) {
            $refundPercentage = 100;
            $slab = 'Less than 1 hour';
        } elseif ($elapsedHours < 3) {
            $refundPercentage = 80;
            $slab = '1–3 hours';
        } elseif ($elapsedHours < 6) {
            $refundPercentage = 60;
            $slab = '3–6 hours';
        } elseif ($elapsedHours < 12) {
            $refundPercentage = 40;
            $slab = '6–12 hours';
        } elseif ($elapsedHours < 24) {
            $refundPercentage = 30;
            $slab = '12–24 hours';
        } elseif ($elapsedHours < 48) {
            $refundPercentage = 20;
            $slab = '24–48 hours';
        } else {
            $refundPercentage = 10;
            $slab = '48–72 hours';
        }

        $deductionPercentage = 100 - $refundPercentage;
        $deductionAmount = round($totalAmount * $deductionPercentage / 100, 2);
        $refundAmount = round($totalAmount * $refundPercentage / 100, 2);

        $result['allowed'] = true;
        $result['message'] = 'Cancellation is available.';
        $result['policy_slab'] = $slab;
        $result['deduction_percentage'] = $deductionPercentage;
        $result['refund_percentage'] = $refundPercentage;
        $result['deduction_amount'] = $deductionAmount;
        $result['refund_amount'] = $refundAmount;

        return $result;
    }
}
