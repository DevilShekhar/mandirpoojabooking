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
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to view ticket bookings.');
        }

        $query = DB::table('ticket_bookings')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
            ->where('ticket_bookings.booking_status', 'confirmed');

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            // Admin and Super Admin can view all confirmed bookings.
        } elseif ($user->hasRole('Temple Manager')) {

            $templeIds = DB::table('temple_user')
                ->where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->pluck('temple_id');

            $query->whereIn('ticket_bookings.temple_id', $templeIds);

        } elseif ($user->hasRole('Customer')) {

            $query->where('ticket_bookings.user_id', $user->id);

        } else {
            abort(403, 'You do not have permission to view ticket bookings.');
        }

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
        )->groupBy(
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

        return view('admin.ticket-booking.my-index', compact('bookings'));
    }

    public function cancelledBookings(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to view cancelled bookings.');
        }

        $query = DB::table('ticket_bookings')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
            ->where('ticket_bookings.booking_status', 'cancelled')
            ->whereIn('ticket_bookings.payment_status', [
                'paid',
                'refunded',
                'partially_refunded'
            ]);

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            // View all cancelled bookings.
        } elseif ($user->hasRole('Temple Manager')) {

            $templeIds = DB::table('temple_user')
                ->where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->pluck('temple_id');

            $query->whereIn('ticket_bookings.temple_id', $templeIds);

        } elseif ($user->hasRole('Customer')) {

            $query->where('ticket_bookings.user_id', $user->id);

        } else {
            abort(403, 'You do not have permission to view cancelled bookings.');
        }

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
        )->groupBy(
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
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to view ticket booking details.');
        }

        $bookings = TicketBooking::with('temple')
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
                $refund = $api->refund->fetch(
                    $existingCancellation->razorpay_refund_id
                );
                $latestRefundStatus = strtolower(
                    (string) ($refund['status'] ?? $existingCancellation->refund_status)
                );
                $refundUpdates = [
                    'refund_status' => $latestRefundStatus,
                    'updated_at' => now(),
                ];
                if (
                    $latestRefundStatus === 'processed' &&
                    Schema::hasColumn('ticket_booking_cancellations', 'refunded_at')
                ) {
                    $refundUpdates['refunded_at'] = now();
                }
                DB::table('ticket_booking_cancellations')
                    ->where('id', $existingCancellation->id)
                    ->update($refundUpdates);
                $existingCancellation = DB::table('ticket_booking_cancellations')
                    ->where('id', $existingCancellation->id)
                    ->first();
                if ($latestRefundStatus === 'processed') {
                    TicketBooking::where('razorpay_order_id', $orderId)
                        ->update([
                            'payment_status' => (float) $existingCancellation->refund_amount >= $totalAmount
                                ? 'refunded'
                                : 'partially_refunded',
                            'updated_at' => now(),
                        ]);
                    $bookings = TicketBooking::with('temple')
                        ->where('razorpay_order_id', $orderId)
                        ->where('booking_status', 'cancelled')
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

        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to view ticket booking history.');
        }

        $bookings = DB::table('ticket_bookings')
            ->join('users', 'users.id', '=', 'ticket_bookings.user_id')
            ->join('temples', 'temples.id', '=', 'ticket_bookings.temple_id')
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
            )
            ->orderByDesc('latest_id')
            ->get();

        return view(
            'admin.ticket-booking.manager-index',
            compact('bookings')
        );
    }

    public function managerBookingDetails(string $orderId)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You are not authorized to view these bookings.');
        }

        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to view ticket booking details.');
        }

        $bookings = TicketBooking::with(['temple', 'user'])
            ->where('razorpay_order_id', $orderId)
            ->orderBy('id')
            ->get();

        abort_if($bookings->isEmpty(), 404);

        return view(
            'admin.ticket-booking.manager-details',
            compact('bookings')
        );
    }

    public function storeCancellationRequest( Request $request, string $orderId ) 
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'You are not authorized to cancel bookings.');
        }
        if (!$user->can('all-tickit-booking.index')) {
            abort(403, 'You do not have permission to cancel bookings.');
        }
        $actorType = $this->getCancellationActorType($user);
        if (!$actorType) {
            abort(403, 'You are not authorized to cancel bookings.');
        }
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:3|max:1000',
        ]);

        $cancellationId = null;
        $paymentId = null;
        $refundAmountPaise = 0;
        $capturedAmountPaise = 0;
        $policy = null;

        // Get approved temples for Temple Manager.
        $templeIds = collect();
        if ($actorType === 'temple_manager') {
            $templeIds = DB::table('temple_user')
                ->where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->pluck('temple_id');
            if ($templeIds->isEmpty()) {
                abort(403, 'No approved temples are assigned to you.');
            }
        }
        try {
            DB::transaction(function () use (
                $orderId,
                $validated,
                $user,
                $actorType,
                $templeIds,
                &$cancellationId,
                &$paymentId,
                &$policy
            ) {
                $query = TicketBooking::where(
                    'razorpay_order_id',
                    $orderId
                )
                    ->where('payment_status', 'paid')
                    ->where('booking_status', 'confirmed');
                // Customer can cancel only their own booking.
                if ($actorType === 'customer') {
                    $query->where('user_id', $user->id);
                }
                // Temple Manager can cancel only assigned temple bookings.
                if ($actorType === 'temple_manager') {
                    $query->whereIn('temple_id', $templeIds);
                }
                // Admin and Super Admin have unrestricted access.
                $bookings = $query
                    ->lockForUpdate()
                    ->get();

                if ($bookings->isEmpty()) {
                    throw ValidationException::withMessages([
                        'cancellation' =>
                            'Booking not found or cancellation is not available.',
                    ]);
                }

                // Prevent duplicate cancellation requests.
                $existingRequest = DB::table('ticket_booking_cancellations')
                    ->where('razorpay_order_id', $orderId)
                    ->whereIn('cancellation_status', [
                        'pending',
                        'approved',
                        'cancelled'
                    ])
                    ->exists();

                if ($existingRequest) {
                    throw ValidationException::withMessages([
                        'cancellation' =>
                            'A cancellation request already exists for this booking.',
                    ]);
                }

                $firstBooking = $bookings->first();
                $totalAmount = round(
                    (float) $bookings->sum('price'),
                    2
                );
                // Calculate role-based refund.
                $policy = $this->calculateCancellationPolicy(
                    $firstBooking,
                    $totalAmount,
                    $user
                );

                if (!$policy['allowed']) {
                    throw ValidationException::withMessages([
                        'cancellation' => $policy['message'],
                    ]);
                }

                $paymentId = $bookings
                    ->pluck('razorpay_payment_id')
                    ->filter()
                    ->first();
                if (
                    $policy['refund_amount'] > 0 &&
                    empty($paymentId)
                ) {
                    throw ValidationException::withMessages([
                        'cancellation' =>
                            'Razorpay payment ID is missing. Please contact support.',
                    ]);
                }

                $insert = [
                    'razorpay_order_id' => $orderId,
                    'user_id' => $firstBooking->user_id,
                    'temple_id' => $firstBooking->temple_id,
                    'booking_date' => $firstBooking->booking_date,
                    'total_amount' => $totalAmount,
                    'deduction_percentage' =>
                        $policy['deduction_percentage'],
                    'refund_percentage' =>
                        $policy['refund_percentage'],
                    'refund_amount' =>
                        $policy['refund_amount'],
                    'cancellation_status' => 'approved',
                    'refund_status' =>
                        $policy['refund_amount'] > 0
                            ? 'processing'
                            : 'not_required',
                    'cancellation_reason' =>
                        $validated['cancellation_reason'],
                    'requested_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'razorpay_payment_id'
                    )
                ) {
                    $insert['razorpay_payment_id'] = $paymentId;
                }
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'razorpay_refund_id'
                    )
                ) {
                    $insert['razorpay_refund_id'] = null;
                }
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'refund_error'
                    )
                ) {
                    $insert['refund_error'] = null;
                }
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'refunded_at'
                    )
                ) {
                    $insert['refunded_at'] = null;
                }

                $cancellationId = DB::table(
                    'ticket_booking_cancellations'
                )->insertGetId($insert);

                // Cancel the bookings belonging to this order.
                TicketBooking::whereIn(
                    'id',
                    $bookings->pluck('id')
                )->update([
                    'booking_status' => 'cancelled',
                    'updated_at' => now(),
                ]);
            });

            // Submit refund to Razorpay.
            if ($policy['refund_amount'] > 0) {
                $api = new Api(
                    config('services.razorpay.key'),
                    config('services.razorpay.secret')
                );

                $payment = $api->payment->fetch($paymentId);
                $capturedAmountPaise = (int) (
                    $payment['amount'] ?? 0
                );
                $alreadyRefundedPaise = (int) (
                    $payment['amount_refunded'] ?? 0
                );
                $availableRefundPaise = max(
                    0,
                    $capturedAmountPaise - $alreadyRefundedPaise
                );
                $refundAmountPaise = (int) round(
                    (float) $policy['refund_amount'] * 100
                );

                if (
                    $capturedAmountPaise <= 0 ||
                    $refundAmountPaise <= 0 ||
                    $refundAmountPaise > $availableRefundPaise
                ) {
                    throw new \RuntimeException(
                        'Refund amount is invalid or exceeds the remaining captured payment amount.'
                    );
                }

                $refund = $api->payment
                    ->fetch($paymentId)
                    ->refund([
                        'amount' => $refundAmountPaise,
                        'notes' => [
                            'ticket_order_id' => (string) $orderId,
                            'cancellation_id' => (string) $cancellationId,
                            'customer_id' => (string) $user->id,
                            'cancelled_by' => $actorType,
                            'cancellation_reason' =>
                                $validated['cancellation_reason'],
                        ],
                    ]);

                $refundId = $refund['id'] ?? null;
                $refundStatus = strtolower(
                    (string) ($refund['status'] ?? 'pending')
                );
                if (!$refundId) {
                    throw new \RuntimeException(
                        'Razorpay did not return a refund ID. Verify the refund in Razorpay Dashboard.'
                    );
                }

                $processed = in_array(
                    $refundStatus,
                    ['processed', 'completed'],
                    true
                );
                $failed = $refundStatus === 'failed';
                $updates = [
                    'refund_status' =>
                        $processed
                            ? 'processed'
                            : ($failed ? 'failed' : 'processing'),

                    'updated_at' => now(),
                ];
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'razorpay_refund_id'
                    )
                ) {
                    $updates['razorpay_refund_id'] = $refundId;
                }
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'refunded_at'
                    )
                ) {
                    $updates['refunded_at'] =
                        $processed ? now() : null;
                }
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'refund_error'
                    )
                ) {
                    $updates['refund_error'] = $failed
                        ? 'Razorpay returned a failed refund status. Check Razorpay Dashboard.'
                        : null;
                }
                DB::table('ticket_booking_cancellations')
                    ->where('id', $cancellationId)
                    ->update($updates);

                if ($processed) {
                    TicketBooking::where('razorpay_order_id', $orderId)
                        ->update([
                            'payment_status' =>
                                $refundAmountPaise >= $capturedAmountPaise
                                    ? 'refunded'
                                    : 'partially_refunded',
                            'updated_at' => now(),
                        ]);
                }
            }

            return redirect()
                ->route('admin.my-tickit-booking')
                ->with(
                    'success',
                    $policy['refund_amount'] > 0
                        ? 'Booking cancelled successfully. Refund of ₹'
                            . number_format($policy['refund_amount'], 2)
                            . ' (' . $policy['refund_percentage']
                            . '%) has been submitted to Razorpay.'
                        : 'Booking cancelled successfully. No refund is applicable.'
                );
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error(
                'Ticket booking cancellation or Razorpay refund failed.',
                [
                    'order_id' => $orderId,
                    'user_id' => $user->id,
                    'actor_type' => $actorType,
                    'cancellation_id' => $cancellationId,
                    'razorpay_payment_id' => $paymentId,
                    'error' => $e->getMessage(),
                ]
            );

            if ($cancellationId) {
                $updates = [
                    'refund_status' => 'failed',
                    'updated_at' => now(),
                ];
                if (
                    Schema::hasColumn(
                        'ticket_booking_cancellations',
                        'refund_error'
                    )
                ) {
                    $updates['refund_error'] = $e->getMessage();
                }
                DB::table('ticket_booking_cancellations')
                    ->where('id', $cancellationId)
                    ->update($updates);
            }

            return redirect()
                ->route('admin.my-tickit-booking')
                ->withErrors([
                    'cancellation' =>
                        'Booking cancellation was recorded, but the refund could not be confirmed. Please verify the refund in Razorpay Dashboard before retrying.',
                ]);
        }
    }
    private function calculateCancellationPolicy(TicketBooking $booking, float $totalAmount, $user = null ): array 
    {
        $user = $user ?? Auth::user();
        $actorType = $this->getCancellationActorType($user);

        $result = [
            'allowed' => false,
            'message' => '',
            'actor_type' => $actorType,
            'booking_created_at' => 'N/A',
            'elapsed_hours' => 0,
            'policy_slab' => '',
            'deduction_percentage' => 0,
            'refund_percentage' => 0,
            'deduction_amount' => 0,
            'refund_amount' => 0,
        ];
        if (!$booking->created_at) {
            $result['message'] =
                'Cancellation is unavailable because booking creation time could not be determined.';
            return $result;
        }
        $createdAt = Carbon::parse($booking->created_at);
        $elapsedSeconds = max(
            0,
            $createdAt->diffInSeconds(now(), false)
        );
        $elapsedHours = $elapsedSeconds / 3600;
        $result['booking_created_at'] =
            $createdAt->format('d-m-Y h:i A');
        $result['elapsed_hours'] = round($elapsedHours, 2);
        if (in_array(
            $actorType,
            ['admin', 'super_admin', 'temple_manager'],
            true
        )) {

            $result['allowed'] = true;

            $result['message'] =
                'Cancellation is allowed. You are eligible for a 100% refund.';

            $result['policy_slab'] =
                'Administrative Cancellation - 100% Refund';

            $result['deduction_percentage'] = 0;

            $result['refund_percentage'] = 100;

            $result['deduction_amount'] = 0;

            $result['refund_amount'] = round($totalAmount, 2);

            return $result;
        }

        /*
        |--------------------------------------------------------------------------
        | Customer Cancellation
        |--------------------------------------------------------------------------
        | Cancellation allowed only within 72 hours.
        */

        if ($actorType !== 'customer') {

            $result['message'] =
                'You are not authorized to cancel this booking.';

            return $result;
        }

        if ($elapsedSeconds >= 72 * 3600) {
            $result['message'] =
                'Customer cancellation is not allowed after 72 hours from booking creation.';
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
        $deductionAmount = round(
            $totalAmount * $deductionPercentage / 100,
            2
        );
        $refundAmount = round(
            $totalAmount * $refundPercentage / 100,
            2
        );

        $result['allowed'] = true;
        $result['message'] =
            'Customer cancellation is available within 72 hours.';
        $result['policy_slab'] = $slab;
        $result['deduction_percentage'] =
            $deductionPercentage;
        $result['refund_percentage'] =
            $refundPercentage;
        $result['deduction_amount'] =
            $deductionAmount;
        $result['refund_amount'] =
            $refundAmount;

        return $result;
    }
    private function getCancellationActorType($user): ?string
    {
        if (!$user) {
            return null;
        }
        if ($user->hasRole('Super Admin')) {
            return 'super_admin';
        }
        if ($user->hasRole('Admin')) {
            return 'admin';
        }
        if ($user->hasRole('Temple Manager')) {
            return 'temple_manager';
        }
        if ($user->hasRole('Customer')) {
            return 'customer';
        }
        return null;
    }
}
