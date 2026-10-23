<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerBookingCancellation;
use App\Models\PujaBooking;
use App\Models\PujaBookingPayment;
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

        $subject = 'Puja Booking Cancellation Request';
        $message = 'A cancellation request has been submitted for this puja booking. The request is awaiting admin review. The booking is not cancelled yet.';

        $recipients = [
            [
                'email' => $customerEmail,
                'name' => $customerName,
                'role' => 'Customer',
            ],
            [
                'email' => $panditEmail,
                'name' => $panditName,
                'role' => 'Pandit',
            ],
        ];

        $sentAddresses = [];

        foreach ($recipients as $recipient) {
            $email = $recipient['email'];

            if (!$email || in_array(strtolower($email), $sentAddresses, true)) {
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

                $sentAddresses[] = strtolower($email);
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
     * Notify the customer after admin approval or rejection.
     */
    private function notifyCustomer(
        CustomerBookingCancellation $cancellation,
        string $subject,
        string $message
    ): void {
        $cancellation->loadMissing([
            'booking.user',
            'booking.puja',
            'booking.pandit',
            'customer',
        ]);

        $booking = $cancellation->booking;

        if (!$booking) {
            return;
        }

        $email = $booking->customer_email
            ?: $cancellation->customer?->email
            ?: $booking->user?->email;

        if (!$email) {
            Log::warning('Customer notification skipped: email missing.', [
                'cancellation_id' => $cancellation->id,
            ]);

            return;
        }

        try {
            $this->sendCancellationEmail(
                $cancellation,
                $email,
                $booking->customer_name
                    ?: $cancellation->customer?->name
                    ?: $booking->user?->name
                    ?: 'Customer',
                'Customer',
                $subject,
                $message
            );
        } catch (Throwable $e) {
            Log::error('Cancellation customer email failed.', [
                'cancellation_id' => $cancellation->id,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function customerIndex()
    {
        $cancellations = CustomerBookingCancellation::with('booking')
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('admin.cancel-booking.index', compact('cancellations'));
    }

    /**
     * Customer submits a cancellation request.
     */
    public function store(Request $request, $bookingId)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:2000',
        ]);

        $user = Auth::user();

        $cancellation = DB::transaction(function () use (
            $bookingId,
            $user,
            $request
        ) {
            $booking = PujaBooking::whereKey($bookingId)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                (int) $booking->user_id === (int) $user->id,
                403,
                'You are not authorized to cancel this booking.'
            );

            if (in_array(
                strtolower((string) $booking->booking_status),
                ['cancelled', 'completed'],
                true
            )) {
                abort(422, 'This booking cannot be cancelled.');
            }

            $existing = CustomerBookingCancellation::where(
                'puja_booking_id',
                $booking->id
            )
                ->where('customer_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                abort(
                    422,
                    'A cancellation request already exists for this booking.'
                );
            }

            return CustomerBookingCancellation::create([
                'puja_booking_id' => $booking->id,
                'customer_id' => $user->id,
                'cancellation_reason' => $request->cancellation_reason,
                'status' => 'pending',
                'refund_status' => 'not_required',
                'refund_amount' => 0,
            ]);
        });

        // Send email notifications to customer and assigned Pandit.
        $this->notifyCustomerAndPandit($cancellation);

        return back()->with(
            'success',
            'Cancellation request submitted successfully. Email notifications have been attempted.'
        );
    }

    /**
     * Admin cancellation request list.
     */
    public function staffIndex()
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $cancellations = CustomerBookingCancellation::with([
            'booking.user',
            'booking.puja',
            'booking.pandit',
            'customer',
            'reviewer',
        ])->latest()->paginate(10);

        return view(
            'admin.customer-cancel-booking.index',
            compact('cancellations')
        );
    }

    /**
     * Admin views cancellation details.
     */
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
            'reviewer',
        ]);

        return view(
            'admin.customer-cancel-booking.show',
            compact('cancellation')
        );
    }

    /**
     * Admin approves the cancellation.
     */
    public function approve(
        Request $request,
        CustomerBookingCancellation $cancellation
    ) {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($cancellation, $validated) {
            $locked = CustomerBookingCancellation::whereKey($cancellation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'pending') {
                abort(
                    422,
                    'This cancellation request has already been reviewed.'
                );
            }

            $locked->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'review_notes' => $validated['review_notes'] ?? null,
                'reviewed_at' => now(),
                'refund_status' => 'processing',
            ]);
        });

        try {
            $this->processRefund($cancellation->fresh());
        } catch (Throwable $e) {
            Log::error('Puja booking cancellation refund failed.', [
                'cancellation_id' => $cancellation->id,
                'exception' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.customer-cancel-booking.show', $cancellation)
                ->with(
                    'error',
                    'Cancellation approved, but refund status could not be confirmed. Check the Razorpay dashboard before retrying.'
                );
        }

        $cancellation->refresh();

        $message = match ($cancellation->refund_status) {
            'not_required' => 'Your cancellation request has been approved. No refund was required.',
            'processed' => 'Your cancellation request has been approved and your refund has been processed.',
            default => 'Your cancellation request has been approved. Your refund is being processed.',
        };

        $this->notifyCustomer(
            $cancellation,
            'Puja Booking Cancellation Approved',
            $message
        );

        return redirect()
            ->route('admin.customer-cancel-booking.show', $cancellation)
            ->with('success', 'Cancellation approved. Check the refund status below.');
    }

    /**
     * Process Razorpay refund.
     */
    private function processRefund(
        CustomerBookingCancellation $cancellation
    ): void {
        $cancellation->refresh();

        if ($cancellation->refund_status === 'processed') {
            return;
        }

        if ($cancellation->razorpay_refund_id) {
            throw new \RuntimeException(
                'A Razorpay refund ID already exists. Verify its status before attempting another refund.'
            );
        }

        $booking = PujaBooking::findOrFail(
            $cancellation->puja_booking_id
        );

        $payment = PujaBookingPayment::where(
            'puja_booking_id',
            $booking->id
        )
            ->whereIn('status', ['paid', 'success', 'captured'])
            ->whereNotNull('razorpay_payment_id')
            ->latest()
            ->first();

        if (!$payment) {
            DB::transaction(function () use ($cancellation, $booking) {
                $cancellation->update([
                    'refund_amount' => 0,
                    'refund_status' => 'not_required',
                ]);

                $booking->update([
                    'booking_status' => 'cancelled',
                ]);
            });

            return;
        }

        $amountPaise = (int) $payment->amount_paise;

        if ($amountPaise <= 0) {
            throw new \RuntimeException(
                'The payment amount is invalid. Please verify the payment record.'
            );
        }

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $refund = $api->payment
            ->fetch($payment->razorpay_payment_id)
            ->refund([
                'amount' => $amountPaise,
            ]);

        $refundId = $refund['id'] ?? null;
        $refundStatus = strtolower(
            (string) ($refund['status'] ?? 'pending')
        );

        if (!$refundId) {
            throw new \RuntimeException(
                'Razorpay did not return a refund ID.'
            );
        }

        $processed = in_array(
            $refundStatus,
            ['processed', 'completed'],
            true
        );

        DB::transaction(function () use (
            $cancellation,
            $booking,
            $payment,
            $refundId,
            $refundStatus,
            $amountPaise,
            $processed
        ) {
            $cancellation->update([
                'refund_amount' => $amountPaise / 100,
                'razorpay_refund_id' => $refundId,
                'refund_status' => $processed ? 'processed' : 'processing',
                'refunded_at' => $processed ? now() : null,
                'refund_error' => null,
            ]);

            if ($processed) {
                $booking->update([
                    'booking_status' => 'cancelled',
                    'payment_status' => 'refunded',
                ]);

                $payment->update([
                    'status' => 'refunded',
                ]);
            }
        });
    }

    /**
     * Admin rejects the cancellation.
     */
    public function reject(
        Request $request,
        CustomerBookingCancellation $cancellation
    ) {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $validated = $request->validate([
            'review_notes' => 'required|string|min:3|max:2000',
        ]);

        DB::transaction(function () use ($cancellation, $validated) {
            $locked = CustomerBookingCancellation::whereKey($cancellation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'pending') {
                abort(
                    422,
                    'This cancellation request has already been reviewed.'
                );
            }

            $locked->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'review_notes' => $validated['review_notes'],
                'reviewed_at' => now(),
                'refund_status' => 'not_required',
            ]);
        });

        $this->notifyCustomer(
            $cancellation->fresh(),
            'Puja Booking Cancellation Rejected',
            'Unfortunately, your cancellation request has been rejected. Please review the admin notes or contact support.'
        );

        return redirect()
            ->route('admin.customer-cancel-booking.show', $cancellation)
            ->with('success', 'Cancellation request rejected.');
    }
}