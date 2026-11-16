<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlinePujaOrder;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;

class MyOnlinePujaBookingController extends Controller
{
    /**
     * Display all bookings created by the logged-in user.
     */
    public function index()
    {
        $bookings = OnlinePujaOrder::with(['puja', 'package'])
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return view(
            'admin.my-online-pujabooking.index',
            compact('bookings')
        );
    }

    /**
     * Display booking details.
     */
    public function show($id)
    {
        
        $booking = OnlinePujaOrder::with([
            'puja',
            'package',
            'cancelledBy'
        ])
        ->where('created_by', auth()->id())
        ->findOrFail($id);

        return view(
            'admin.my-online-pujabooking.show',
            compact('booking')
        );
    }

    /**
     * Cancel the specified booking with time-based fee logic.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $booking = OnlinePujaOrder::with('puja')
            ->where('created_by', auth()->id())
            ->findOrFail($id);

        $restrictedStatuses = ['cancelled', 'completed', 'rejected'];
        if (in_array(strtolower($booking->booking_status), $restrictedStatuses)) {
            return redirect()->back()->with('error', 'This booking cannot be cancelled.');
        }

        $refundPercentage = 100; // Default fallback

        if ($booking->created_at) {
            $now = Carbon::now();
            $createdAt = Carbon::parse($booking->created_at);
            
            // Calculate hours elapsed SINCE creation
            $hoursElapsed = $createdAt->floatDiffInHours($now);

            // Time-elapsed refund tier logic
            if ($hoursElapsed <= 1) {
                $refundPercentage = 80;   // Less than 1 hr
            } elseif ($hoursElapsed <= 3) {
                $refundPercentage = 80;   // 1–3 hours: 80% refund
            } elseif ($hoursElapsed <= 6) {
                $refundPercentage = 60;   // 3–6 hours: 60% refund
            } elseif ($hoursElapsed <= 12) {
                $refundPercentage = 40;   // 6–12 hours: 40% refund
            } elseif ($hoursElapsed <= 24) {
                $refundPercentage = 30;   // 12–24 hours: 30% refund
            } elseif ($hoursElapsed <= 48) {
                $refundPercentage = 20;   // 24–48 hours: 20% refund
            } elseif ($hoursElapsed <= 72) {
                $refundPercentage = 10;   // 48–72 hours: 10% refund
            } else {
                $refundPercentage = 0;    // After 72 hours: 0% refund
            }
        }

        $cancellationFeePercentage = 100 - $refundPercentage;

        $totalAmount = (float) ($booking->amount ?? 0);
        $refundAmount = ($totalAmount * $refundPercentage) / 100;

        $razorpayRefundId = null;
        $refundStatus = 'not_initiated';

        // Process Razorpay Automatic Refund if payment was successful and refund amount > 0
        if ($refundAmount > 0 && !empty($booking->razorpay_payment_id) && strtolower($booking->payment_status) === 'paid') {
            try {
                $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                
                // Razorpay accepts amount in paise (multiply INR amount by 100)
                $amountInPaise = (int) round($refundAmount * 100);

                $refund = $api->payment->fetch($booking->razorpay_payment_id)->refund([
                    'amount' => $amountInPaise
                ]);

                $razorpayRefundId = $refund->id ?? null;
                $refundStatus = 'processed';

            } catch (Exception $e) {
                $refundStatus = 'failed';
                
                return redirect()
                    ->back()
                    ->with('error', 'Cancellation failed during payment gateway refund: ' . $e->getMessage());
            }
        } else {
            $refundStatus = $refundAmount > 0 ? 'manual_required' : 'no_refund';
        }

        // Update Booking Record
        $booking->update([
            'booking_status'              => 'cancelled',
            'cancellation_reason'         => $request->cancellation_reason,
            'cancelled_at'                => Carbon::now(),
            'cancelled_by'                => auth()->id(),
            'cancellation_fee_percentage' => $cancellationFeePercentage,
            'refund_amount'               => $refundAmount,
            'razorpay_refund_id'          => $razorpayRefundId,
            'refund_status'               => $refundStatus,
        ]);

        // Decrement the booked seat count on the OnlinePuja model safely
        if ($booking->puja) {
            $booking->puja()->decrement('booked_seat');
            // Prevent negative seats just in case
            if ($booking->puja->booked_seat < 0) {
                $booking->puja()->update(['booked_seat' => 0]);
            }
        }

        $currencySymbol = $booking->currency ?? 'INR';

        return redirect()
            ->route('admin.my-online-pujabooking.show', $booking->id)
            ->with('success', "Booking cancelled successfully. Refund: {$refundPercentage}% ({$currencySymbol} " . number_format($refundAmount, 2) . "). Gateway Status: {$refundStatus}");
    }
}