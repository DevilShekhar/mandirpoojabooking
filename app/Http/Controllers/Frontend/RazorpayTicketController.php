<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TicketBooking;
use App\Models\TicketCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;

class RazorpayTicketController extends Controller
{
    private function razorpay(): Api
    {
        return new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder(Request $request)
    {
        $cartItems = TicketCart::where('user_id', auth()->id())
            ->where('status', 'active')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your ticket cart is empty.',
            ], 422);
        }

        $totalAmount = round(
            $cartItems->sum(fn ($item) => (float) $item->price),
            2
        );

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment amount.',
            ], 422);
        }

        try {
            $order = $this->razorpay()->order->create([
                'receipt' => 'ticket_' . Str::uuid(),
                'amount' => (int) round($totalAmount * 100),
                'currency' => 'INR',
                'notes' => [
                    'user_id' => (string) auth()->id(),
                    'member_count' => (string) $cartItems->count(),
                ],
            ]);

            session([
                'ticket_payment_order_id' => $order['id'],
                'ticket_payment_user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'key' => config('services.razorpay.key'),
                'order_id' => $order['id'],
                'amount' => (int) $order['amount'],
                'currency' => $order['currency'],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create payment order. Please try again.',
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        if (
            session('ticket_payment_order_id') !== $validated['razorpay_order_id'] ||
            (int) session('ticket_payment_user_id') !== (int) auth()->id()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Your payment session has expired. Please try again.',
            ], 422);
        }

        try {
            $api = $this->razorpay();

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            $payment = $api->payment->fetch(
                $validated['razorpay_payment_id']
            );

            if (
                $payment['order_id'] !== $validated['razorpay_order_id'] ||
                $payment['status'] !== 'captured' ||
                $payment['currency'] !== 'INR'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has not been confirmed as captured.',
                ], 422);
            }

            DB::transaction(function () use ($validated, $payment) {
                $existingBooking = TicketBooking::where(
                    'razorpay_order_id',
                    $validated['razorpay_order_id']
                )->lockForUpdate()->first();

                if ($existingBooking) {
                    if (
                        $existingBooking->razorpay_payment_id !==
                        $validated['razorpay_payment_id']
                    ) {
                        throw new \RuntimeException(
                            'This order is associated with a different payment.'
                        );
                    }

                    return;
                }

                $cartItems = TicketCart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \RuntimeException(
                        'No active ticket cart items were found.'
                    );
                }

                $totalPaise = (int) round(
                    $cartItems->sum(fn ($item) => (float) $item->price) * 100
                );

                if ((int) $payment['amount'] !== $totalPaise) {
                    throw new \RuntimeException(
                        'The payment amount does not match the current cart total.'
                    );
                }

                foreach ($cartItems as $item) {
                    TicketBooking::create([
                        'booking_reference' => 'TKT-' .
                            strtoupper(Str::random(12)),
                        'user_id' => auth()->id(),
                        'temple_id' => $item->temple_id,
                        'booking_date' => $item->booking_date,
                        'member_name' => $item->member_name,
                        'aadhaar_number' => $item->aadhaar_number,
                        'aadhaar_photo' => $item->aadhaar_photo,
                        'ticket_type' => $item->ticket_type,
                        'type' => $item->type,
                        'age' => $item->age,
                        'ticket_price_id' => $item->ticket_price_id,
                        'price' => $item->price,
                        'razorpay_order_id' =>
                            $validated['razorpay_order_id'],
                        'razorpay_payment_id' =>
                            $validated['razorpay_payment_id'],
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                    ]);
                }

                TicketCart::whereIn(
                    'id',
                    $cartItems->pluck('id')
                )->delete();
            });

            session()->forget([
                'ticket_payment_order_id',
                'ticket_payment_user_id',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Your tickets are booked.',
                'redirect_url' => route('frontend.ticket-booking.success'),
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification or booking failed. Please contact support before retrying payment.',
            ], 500);
        }
    }

    public function paymentFailed()
    {
        session()->forget([
            'ticket_payment_order_id',
            'ticket_payment_user_id',
        ]);

        return redirect()
            ->route('frontend.ticket-cart.index')
            ->with('error', 'Payment was not completed. Your cart is still available.');
    }

    public function success()
    {
        $bookings = TicketBooking::with('temple')
            ->where('user_id', auth()->id())
            ->where('booking_status', 'confirmed')
            ->latest()
            ->get();

        return view(
            'frontend.ticket-booking.success',
            compact('bookings')
        );
    }
}