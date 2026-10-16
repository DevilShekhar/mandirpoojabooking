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
use Razorpay\Api\Api;
use Throwable;

class CustomerBookingCancellationController extends Controller
{
    private function isAdmin($user): bool
    {
        return $user && $user->hasAnyRole(['Admin', 'Super Admin']);
    }

    public function customerIndex()
    {
        $cancellations = CustomerBookingCancellation::with('booking')
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('admin.cancel-booking.index', compact('cancellations'));
    }

    public function store(Request $request, $bookingId)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|min:5|max:2000',
        ]);

        $user = Auth::user();

        $cancellation = DB::transaction(function () use ($bookingId, $user, $request) {
            $booking = PujaBooking::whereKey($bookingId)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless((int) $booking->user_id === (int) $user->id, 403, 'You are not authorized to cancel this booking.');

            if (in_array(strtolower((string) $booking->booking_status), ['cancelled', 'completed'], true)) {
                abort(422, 'This booking cannot be cancelled.');
            }

            $existing = CustomerBookingCancellation::where('puja_booking_id', $booking->id)
                ->where('customer_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                if (in_array($existing->status, ['pending', 'approved'], true)) {
                    abort(422, 'A cancellation request already exists for this booking.');
                }

                abort(422, 'A cancellation request already exists. Please contact support if you need further assistance.');
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

        return back()->with('success', 'Cancellation request submitted successfully.');
    }

    public function staffIndex()
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $cancellations = CustomerBookingCancellation::with([
            'booking.user',
            'customer',
            'reviewer',
        ])->latest()->paginate(10);

        return view('admin.customer-cancel-booking.index', compact('cancellations'));
    }

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

        return view('admin.customer-cancel-booking.show', compact('cancellation'));
    }

    public function approve(Request $request, CustomerBookingCancellation $cancellation)
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($cancellation, $validated) {
            $cancellation = CustomerBookingCancellation::whereKey($cancellation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($cancellation->status !== 'pending') {
                abort(422, 'This cancellation request has already been reviewed.');
            }

            $cancellation->update([
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
                ->with('error', 'Cancellation approved, but refund status could not be confirmed. Check the Razorpay dashboard before retrying.');
        }

        return redirect()
            ->route('admin.customer-cancel-booking.show', $cancellation)
            ->with('success', 'Cancellation approved. Check the refund status below.');
    }

    private function processRefund(CustomerBookingCancellation $cancellation): void
    {
        $cancellation->refresh();

        if ($cancellation->refund_status === 'processed') {
            return;
        }

        if ($cancellation->razorpay_refund_id) {
            throw new \RuntimeException('A Razorpay refund ID already exists. Verify its status before attempting another refund.');
        }

        $booking = PujaBooking::findOrFail($cancellation->puja_booking_id);

        $payment = PujaBookingPayment::where('puja_booking_id', $booking->id)
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
            throw new \RuntimeException('The payment amount is invalid. Please verify the payment record.');
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
        $refundStatus = strtolower((string) ($refund['status'] ?? 'pending'));

        if (!$refundId) {
            throw new \RuntimeException('Razorpay did not return a refund ID.');
        }

        DB::transaction(function () use ($cancellation, $booking, $payment, $refundId, $refundStatus, $amountPaise) {
            $cancellation->update([
                'refund_amount' => $amountPaise / 100,
                'razorpay_refund_id' => $refundId,
                'refund_status' => in_array($refundStatus, ['processed', 'completed'], true) ? 'processed' : 'processing',
                'refunded_at' => in_array($refundStatus, ['processed', 'completed'], true) ? now() : null,
                'refund_error' => null,
            ]);

            if (in_array($refundStatus, ['processed', 'completed'], true)) {
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

    public function reject(Request $request, CustomerBookingCancellation $cancellation)
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $validated = $request->validate([
            'review_notes' => 'required|string|min:3|max:2000',
        ]);

        DB::transaction(function () use ($cancellation, $validated) {
            $cancellation = CustomerBookingCancellation::whereKey($cancellation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($cancellation->status !== 'pending') {
                abort(422, 'This cancellation request has already been reviewed.');
            }

            $cancellation->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'review_notes' => $validated['review_notes'],
                'reviewed_at' => now(),
                'refund_status' => 'not_required',
            ]);
        });

        return redirect()
            ->route('admin.customer-cancel-booking.show', $cancellation)
            ->with('success', 'Cancellation request rejected.');
    }
}