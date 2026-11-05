<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerBookingCancellation;
use App\Models\PujaBooking;
use App\Models\PujaBookingPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use Throwable;

class CustomerBookingCancellationController extends Controller
{
    private function isAdmin($user): bool
    {
        return $user && $user->hasAnyRole(['Admin', 'Super Admin']);
    }
    private function getCustomerCancellationPercentage(float $elapsedHours): int
    {
        abort_if(
            $elapsedHours > 72,
            422,
            'Cancellation is not allowed because more than 72 hours have passed since the booking was created.'
        );

        if ($elapsedHours >= 48) return 10;
        if ($elapsedHours >= 24) return 20;
        if ($elapsedHours >= 12) return 30;
        if ($elapsedHours >= 6) return 40;
        if ($elapsedHours >= 3) return 60;
        if ($elapsedHours >= 1) return 80;

        return 100;
    }
    /**
     * Send a cancellation email to a recipient.
     */
    private function sendCancellationEmail(
        CustomerBookingCancellation $cancellation,
        string $email,
        string $recipientName,
        string $recipientRole,
        string $subject,
        string $messageText
    ): void {
        if (!$email) {
            return;
        }

        $cancellation->loadMissing([
            'booking.user',
            'booking.puja',
            'booking.pandit',
            'customer',
        ]);

        $booking = $cancellation->booking;

        if (!$booking) {
            Log::warning('Cancellation email skipped: booking not found.', [
                'cancellation_id' => $cancellation->id,
            ]);

            return;
        }

        Mail::send('emails.booking-cancellation', [
            'subject' => $subject,
            'recipientName' => $recipientName,
            'recipientRole' => $recipientRole,
            'messageText' => $messageText,
            'bookingNumber' => $booking->booking_number
             ?: '#' . $booking->id,
            'customerName' => $booking->customer_name
                ?: $cancellation->customer?->name
                ?: $booking->user?->name
                ?: 'Customer',
            'panditName' => $booking->pandit?->name ?? 'Pandit',
            'pujaName' => $booking->puja?->name ?? 'Puja Booking',
            'status' => $cancellation->status,
            'reason' => $cancellation->cancellation_reason,
            'reviewNotes' => $cancellation->review_notes,
            'refundAmount' => (float) $cancellation->refund_amount,
            'refundStatus' => $cancellation->refund_status,
        ], function ($mail) use ($email, $subject) {
            $mail->to($email)->subject($subject);
        });
    }

    /**
     * Notify both customer and assigned Pandit.
     */
    private function notifyCustomerAndPandit(
        CustomerBookingCancellation $cancellation
    ): void {
        $cancellation->load([
            'booking.user',
            'booking.puja',
            'booking.pandit',
            'customer',
        ]);

        $booking = $cancellation->booking;

        if (!$booking) {
            Log::warning('Cancellation notifications skipped: booking not found.', [
                'cancellation_id' => $cancellation->id,
            ]);

            return;
        }

        $customerEmail = $booking->customer_email
            ?: $cancellation->customer?->email
            ?: $booking->user?->email;

        $customerName = $booking->customer_name
            ?: $cancellation->customer?->name
            ?: $booking->user?->name
            ?: 'Customer';

        $panditEmail = $booking->pandit?->email;
        $panditName = $booking->pandit?->name ?? 'Pandit';

        $subject = 'Puja Booking Cancelled';
        $message = 'This Puja booking has been cancelled successfully. The applicable refund has been initiated according to the cancellation policy.';

        $recipients = [
            ['email' => $customerEmail, 'name' => $customerName, 'role' => 'Customer'],
            ['email' => $panditEmail, 'name' => $panditName, 'role' => 'Pandit'],
        ];

        $sentAddresses = [];

        foreach ($recipients as $recipient) {
            $email = $recipient['email'];
            $normalizedEmail = strtolower((string) $email);

            if (!$email || in_array($normalizedEmail, $sentAddresses, true)) {
                continue;
            }

            try {
                $this->sendCancellationEmail(
                    $cancellation,
                    $email,
                    $recipient['name'],
                    $recipient['role'],
                    $subject,
                    $message
                );

                $sentAddresses[] = $normalizedEmail;
            } catch (Throwable $e) {
                Log::error('Cancellation email failed.', [
                    'cancellation_id' => $cancellation->id,
                    'recipient' => $email,
                    'exception' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Customer submits cancellation; authorized Temple/Platform staff can
     * also cancel directly. Booking cancellation is immediate.
     */
    public function store(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:2000',
        ]);

        $user = Auth::user();
        abort_unless($user, 401, 'Please log in to cancel this booking.');

        $cancellation = DB::transaction(function () use ($bookingId, $user, $validated) {
            $booking = PujaBooking::whereKey($bookingId)
                ->lockForUpdate()
                ->firstOrFail();

            $bookingStatus = strtolower((string) $booking->booking_status);

            abort_if(
                in_array($bookingStatus, ['cancelled', 'completed'], true),
                422,
                'This booking is already cancelled or completed.'
            );

            // Do not allow duplicate cancellation records.
            $existing = CustomerBookingCancellation::where('puja_booking_id', $booking->id)
                ->lockForUpdate()
                ->first();

            abort_if($existing, 422, 'Cancellation already exists for this booking.');

            $role = null;
            $percentage = 0;
            if ($user->hasRole('Customer')) {
                abort_unless(
                    (int) $booking->user_id === (int) $user->id,
                    403,
                    'You are not authorized to cancel this booking.'
                );

                $role = 'Customer';
                abort_unless(
                    $booking->created_at,
                    422,
                    'Booking creation time is missing. Please contact support.'
                );

                $createdAt = Carbon::parse($booking->created_at)->timezone(config('app.timezone'));
                $elapsedHours = (
                    now(config('app.timezone'))->getTimestamp() - $createdAt->getTimestamp()
                ) / 3600;

                abort_if(
                    $elapsedHours < 0,
                    422,
                    'Invalid booking creation time. Please contact support.'
                );

                $percentage = $this->getCustomerCancellationPercentage($elapsedHours);
            } elseif ($user->hasRole('Temple Manager')) {
                $hasTempleAccess = DB::table('temple_user')
                    ->where('temple_id', $booking->temple_id)
                    ->where('user_id', $user->id)
                    ->where('approval_status', 'approved')
                    ->exists();

                abort_unless(
                    $hasTempleAccess,
                    403,
                    'You are not authorized to cancel this temple booking.'
                );

                $role = 'Temple Manager';
                $percentage = 0; // Temple-side cancellation: 100% refund.
            } elseif ($user->hasRole('Pandit')) {
                abort_unless(
                    (int) $booking->pandit_id === (int) $user->id,
                    403,
                    'You are not authorized to cancel this booking.'
                );

                $role = 'Pandit';
                $percentage = 0; // Retains the existing temple-side staff rule.
            } elseif ($user->hasRole('Super Admin')) {
                $role = 'Super Admin';
                $percentage = 0; // Platform-side cancellation: 100% refund.
            } elseif ($user->hasRole('Admin')) {
                $role = 'Admin';
                $percentage = 0; // Platform-side cancellation: 100% refund.
            } else {
                abort(403, 'You are not authorized to cancel this booking.');
            }

            // Use the captured Razorpay payment record as the source of truth.
            $isBookingPaid = in_array(
                strtolower((string) $booking->payment_status),
                ['paid', 'success', 'captured'],
                true
            );

            $payment = null;
            $paidAmount = 0.00;

            if ($isBookingPaid) {
                $payment = PujaBookingPayment::where('puja_booking_id', $booking->id)
                    ->whereIn('status', ['paid', 'success', 'captured'])
                    ->whereNotNull('razorpay_payment_id')
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                abort_unless(
                    $payment && (int) $payment->amount_paise > 0,
                    422,
                    'A successful captured payment record was not found. Please contact support before cancelling.'
                );

                $paidAmount = round(((int) $payment->amount_paise) / 100, 2);
            }

            $cancellationCharge = round(($paidAmount * $percentage) / 100, 2);
            $refundAmount = round(max(0, $paidAmount - $cancellationCharge), 2);

            $refundStatus = $refundAmount > 0 ? 'pending' : 'not_required';

            $cancellation = CustomerBookingCancellation::create([
                'puja_booking_id' => $booking->id,
                'customer_id' => $booking->user_id,
                'cancelled_by' => $user->id,
                'cancellation_role' => $role,
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancellation_percentage' => $percentage,
                'cancellation_charge' => $cancellationCharge,
                'status' => 'cancelled',
                'refund_status' => $refundStatus,
                'refund_amount' => $refundAmount,
                'cancelled_at' => now(),
            ]);

            $booking->update([
                'booking_status' => 'cancelled',
                'updated_by' => $user->id,
            ]);

            return $cancellation;
        });

        // Submit the refund after the database transaction commits.
        if ((float) $cancellation->refund_amount > 0) {
            try {
                $this->processRefund($cancellation);
            } catch (Throwable $e) {
                $cancellation->refresh();

                // If the request reached Razorpay, its result may be unknown
                // (for example, a network timeout). Keep it in processing so
                // the same refund is not submitted again without reconciliation.
                $cancellation->update([
                    'refund_status' => $cancellation->refund_status === 'processing'
                        ? 'processing'
                        : 'failed',
                    'refund_error' => $e->getMessage(),
                ]);

                Log::error('Automatic Puja booking refund could not be confirmed.', [
                    'cancellation_id' => $cancellation->id,
                    'refund_status' => $cancellation->refund_status,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        try {
            $this->notifyCustomerAndPandit($cancellation->fresh());
        } catch (Throwable $e) {
            Log::error('Cancellation notification failed.', [
                'cancellation_id' => $cancellation->id,
                'exception' => $e->getMessage(),
            ]);
        }

        $cancellation->refresh();

        if (in_array($cancellation->refund_status, ['failed', 'processing'], true)) {
            return back()->with(
                'warning',
                'Booking cancelled successfully, but the refund is not yet confirmed. Please contact support. Check the refund in Razorpay before attempting any retry.'
            );
        }

        return back()->with(
            'success',
            'Booking cancelled successfully. The applicable refund has been initiated.'
        );
    }

    /** Customer and Temple Manager cancellation history. */
    public function customerIndex()
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $query = CustomerBookingCancellation::with([
            'booking.user',
            'booking.temple',
            'booking.puja',
            'booking.pujaPackage',
            'customer',
            'cancelledBy',
        ]);

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            // Admins can see all cancellation records.
        } elseif ($user->hasRole('Temple Manager')) {
            $templeIds = $user->temples()
                ->wherePivot('approval_status', 'approved')
                ->pluck('temples.id');

            $query->whereHas('booking', function ($bookingQuery) use ($templeIds) {
                $bookingQuery->whereIn('temple_id', $templeIds);
            });
        } elseif ($user->hasRole('Pandit')) {
            $query->whereHas('booking', function ($bookingQuery) use ($user) {
                $bookingQuery->where('pandit_id', $user->id);
            });
        } else {
            $query->where('customer_id', $user->id);
        }

        $cancellationCount = (clone $query)->count();
        $pendingCancellationCount = (clone $query)->where('status', 'pending')->count();
        $approvedCancellationCount = (clone $query)->where('status', 'approved')->count();
        $rejectedCancellationCount = (clone $query)->where('status', 'rejected')->count();
        $cancelledCancellationCount = (clone $query)->where('status', 'cancelled')->count();

        $cancellations = (clone $query)->latest()->paginate(10)->withQueryString();

        return view('admin.cancel-booking.index', compact(
            'cancellations',
            'cancellationCount',
            'pendingCancellationCount',
            'approvedCancellationCount',
            'rejectedCancellationCount',
            'cancelledCancellationCount'
        ));
    }

    /** Admin-only read access to cancellation history. */
    public function staffIndex()
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $cancellations = CustomerBookingCancellation::with([
            'booking.user',
            'booking.temple',
            'booking.puja',
            'booking.pandit',
            'customer',
            'cancelledBy',
        ])->latest()->paginate(10)->withQueryString();

        return view('admin.customer-cancel-booking.index', compact('cancellations'));
    }

    /** Admin-only read access to one cancellation record. */
    public function show(CustomerBookingCancellation $cancellation)
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $cancellation->load([
            'booking.user',
            'booking.temple',
            'booking.deity',
            'booking.puja',
            'booking.pujaPackage',
            'booking.pandit',
            'customer',
            'cancelledBy',
        ]);

        return view('admin.customer-cancel-booking.show', compact('cancellation'));
    }

    /**
     * Submit the calculated partial/full refund to Razorpay.
     * A refund already carrying a Razorpay ID is never submitted again.
     */
    private function processRefund(CustomerBookingCancellation $cancellation): void
    {
        $cancellation->refresh();

        if ($cancellation->razorpay_refund_id) {
            return;
        }

        if (in_array($cancellation->refund_status, ['processed', 'not_required'], true)) {
            return;
        }

        if ($cancellation->refund_status === 'processing') {
            throw new \RuntimeException(
                'Refund is already marked as processing. Verify its status in Razorpay before retrying.'
            );
        }

        $refundAmountPaise = (int) round((float) $cancellation->refund_amount * 100);

        if ($refundAmountPaise <= 0) {
            $cancellation->update(['refund_status' => 'not_required']);
            return;
        }

        $booking = PujaBooking::findOrFail($cancellation->puja_booking_id);

        $payment = PujaBookingPayment::where('puja_booking_id', $booking->id)
            ->whereIn('status', ['paid', 'success', 'captured'])
            ->whereNotNull('razorpay_payment_id')
            ->latest('id')
            ->first();

        if (!$payment) {
            throw new \RuntimeException('Successful Razorpay payment not found for this booking.');
        }

        $capturedAmountPaise = (int) $payment->amount_paise;

        if ($capturedAmountPaise <= 0 || $refundAmountPaise > $capturedAmountPaise) {
            throw new \RuntimeException('Refund amount is invalid or exceeds the captured payment amount.');
        }

        // Mark processing before making the external API request. If the API
        // times out, reconcile against Razorpay before any manual retry.
        $cancellation->update([
            'refund_status' => 'processing',
            'refund_error' => null,
        ]);

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $refund = $api->payment
            ->fetch($payment->razorpay_payment_id)
            ->refund([
                'amount' => $refundAmountPaise,
                'notes' => [
                    'cancellation_id' => (string) $cancellation->id,
                    'puja_booking_id' => (string) $booking->id,
                ],
            ]);

        $refundId = $refund['id'] ?? null;
        $refundStatus = strtolower((string) ($refund['status'] ?? 'pending'));

        if (!$refundId) {
            throw new \RuntimeException('Razorpay did not return a refund ID.');
        }

        $processed = in_array($refundStatus, ['processed', 'completed'], true);
        $failed = $refundStatus === 'failed';

        DB::transaction(function () use (
            $cancellation,
            $booking,
            $payment,
            $refundId,
            $refundAmountPaise,
            $capturedAmountPaise,
            $processed,
            $failed
        ) {
            $lockedCancellation = CustomerBookingCancellation::whereKey($cancellation->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedCancellation->update([
                'razorpay_refund_id' => $refundId,
                'refund_amount' => $refundAmountPaise / 100,
                'refund_status' => $processed ? 'processed' : ($failed ? 'failed' : 'processing'),
                'refunded_at' => $processed ? now() : null,
                'refund_error' => $failed
                    ? 'Razorpay returned a failed refund status. Check Razorpay Dashboard for details.'
                    : null,
            ]);

            // Do not mark a partially refunded payment as fully refunded.
            if ($processed) {
                if ($refundAmountPaise >= $capturedAmountPaise) {
                    $payment->update(['status' => 'refunded']);
                    $booking->update(['payment_status' => 'refunded']);
                } else {
                    // Keep partial refunds explicit instead of showing the payment as fully paid.
                    $payment->update(['status' => 'partially_refunded']);
                    $booking->update(['payment_status' => 'partially_refunded']);
                }
            }
        });
    }
}