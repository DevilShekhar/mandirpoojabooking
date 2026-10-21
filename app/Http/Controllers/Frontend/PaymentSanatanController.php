<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Sanatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;

class PaymentSanatanController extends Controller
{
    private function razorpay(): Api
    {
        return new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function createOrder(Request $request, string $slug)
    {
        $validated = $request->validate([
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
        ]);

        $sanatan = Sanatan::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        if (!Auth::user()->hasRole('Customer')) {
            abort(403, 'Only customers can book events.');
        }

        $amount = (float) $sanatan->price;

        if ($amount <= 0) {
            return response()->json([
                'message' => 'This event has an invalid booking price.',
            ], 422);
        }

        try {
            $receipt = 'SE' . strtoupper(Str::random(12));

            $order = $this->razorpay()->order->create([
                'receipt' => $receipt,
                'amount' => (int) round($amount * 100),
                'currency' => 'INR',
                'notes' => [
                    'sanatan_id' => (string) $sanatan->id,
                    'user_id' => (string) Auth::id(),
                ],
            ]);

            $bookingId = DB::table('sanatan_event_orders')->insertGetId([
                'sanatan_id' => $sanatan->id,
                'user_id' => Auth::id(),
                'created_by' => $sanatan->created_by,
                'event_date' => $validated['event_date'],
                'event_time' => $validated['event_time'],
                'amount' => $amount,
                'currency' => 'INR',
                'razorpay_order_id' => $order['id'],
                'payment_status' => 'pending',
                'booking_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'booking_id' => $bookingId,
                'key' => config('services.razorpay.key'),
                'order_id' => $order['id'],
                'amount' => (int) round($amount * 100),
                'currency' => 'INR',
                'event_name' => $sanatan->name,
                'customer_name' => Auth::user()->name,
                'customer_email' => Auth::user()->email,
            ]);
        } catch (Throwable $e) {
            Log::error('Sanatan Razorpay order creation failed.', [
                'user_id' => Auth::id(),
                'sanatan_id' => $sanatan->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to start payment. Please try again.',
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

        $booking = DB::table('sanatan_event_orders')
            ->where('razorpay_order_id', $validated['razorpay_order_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return response()->json([
                'message' => 'Booking order not found.',
            ], 404);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'message' => 'This booking is already paid.',
                'redirect_url' => route(
                    'frontend.sanatanevents.booking.success',
                    ['booking_id' => $booking->id]
                ),
            ]);
        }

        try {
            $api = $this->razorpay();

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $booking->razorpay_order_id,
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            $payment = $api->payment->fetch(
                $validated['razorpay_payment_id']
            );

            if (
                $payment['order_id'] !== $booking->razorpay_order_id ||
                (int) $payment['amount'] !== (int) round((float) $booking->amount * 100) ||
                $payment['currency'] !== 'INR'
            ) {
                Log::warning('Sanatan payment details mismatch.', [
                    'booking_id' => $booking->id,
                    'payment_id' => $validated['razorpay_payment_id'],
                ]);

                return response()->json([
                    'message' => 'Payment details could not be verified. Please contact support.',
                    'redirect_url' => route(
                        'frontend.sanatanevents.booking.failed',
                        ['booking_id' => $booking->id]
                    ),
                ], 422);
            }

            if ($payment['status'] !== 'captured') {
                if ($payment['status'] === 'failed') {
                    DB::table('sanatan_event_orders')
                        ->where('id', $booking->id)
                        ->where('payment_status', '!=', 'paid')
                        ->update([
                            'payment_status' => 'failed',
                            'booking_status' => 'failed',
                            'updated_at' => now(),
                        ]);

                    return response()->json([
                        'message' => 'Payment failed. Please try again.',
                        'redirect_url' => route(
                            'frontend.sanatanevents.booking.failed',
                            ['booking_id' => $booking->id]
                        ),
                    ], 422);
                }

                return response()->json([
                    'message' => 'Payment is still processing. Please check its status before retrying.',
                    'redirect_url' => route(
                        'frontend.sanatanevents.booking.failed',
                        [
                            'booking_id' => $booking->id,
                            'status' => 'pending',
                        ]
                    ),
                ], 422);
            }

            DB::transaction(function () use ($booking, $validated) {
                $currentBooking = DB::table('sanatan_event_orders')
                    ->where('id', $booking->id)
                    ->lockForUpdate()
                    ->first();

                if (!$currentBooking) {
                    abort(404, 'Booking not found.');
                }

                if ($currentBooking->payment_status === 'paid') {
                    return;
                }

                DB::table('sanatan_event_orders')
                    ->where('id', $booking->id)
                    ->update([
                        'razorpay_payment_id' => $validated['razorpay_payment_id'],
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                        'updated_at' => now(),
                    ]);
            });

            return response()->json([
                'message' => 'Payment verified and booking confirmed.',
                'redirect_url' => route(
                    'frontend.sanatanevents.booking.success',
                    ['booking_id' => $booking->id]
                ),
            ]);
        } catch (Throwable $e) {
            Log::error('Sanatan Razorpay payment verification failed.', [
                'user_id' => Auth::id(),
                'booking_id' => $booking->id,
                'order_id' => $validated['razorpay_order_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Payment could not be verified. Do not pay again until you check your payment status.',
                'redirect_url' => route(
                    'frontend.sanatanevents.booking.failed',
                    [
                        'booking_id' => $booking->id,
                        'status' => 'pending',
                    ]
                ),
            ], 422);
        }
    }

    public function bookingSuccess(Request $request)
    {
        $bookingId = $request->query('booking_id');

        $booking = DB::table('sanatan_event_orders')
            ->join('sanatans', 'sanatan_event_orders.sanatan_id', '=', 'sanatans.id')
            ->where('sanatan_event_orders.id', $bookingId)
            ->where('sanatan_event_orders.user_id', Auth::id())
            ->where('sanatan_event_orders.payment_status', 'paid')
            ->where('sanatan_event_orders.booking_status', 'confirmed')
            ->select(
                'sanatan_event_orders.*',
                'sanatans.name as event_name'
            )
            ->first();

        if (!$booking) {
            return redirect()
                ->route('frontend.sanatanevents.booking.failed')
                ->with('error', 'The booking could not be verified.');
        }

        return view('frontend.sanatanevents.booking-success', compact('booking'));
    }

    public function bookingFailed(Request $request)
    {
        $bookingId = $request->query('booking_id');

        $booking = null;

        if ($bookingId) {
            $booking = DB::table('sanatan_event_orders')
                ->join('sanatans', 'sanatan_event_orders.sanatan_id', '=', 'sanatans.id')
                ->where('sanatan_event_orders.id', $bookingId)
                ->where('sanatan_event_orders.user_id', Auth::id())
                ->select(
                    'sanatan_event_orders.*',
                    'sanatans.name as event_name'
                )
                ->first();

            if (!$booking) {
                abort(404, 'Booking not found.');
            }

            if (
                $booking->payment_status === 'paid' &&
                $booking->booking_status === 'confirmed'
            ) {
                return redirect()->route(
                    'frontend.sanatanevents.booking.success',
                    ['booking_id' => $booking->id]
                );
            }
        }

        $status = $request->query('status', 'failed');

        $message = $status === 'pending'
            ? 'Your payment status is not confirmed yet. Please check your payment status before trying again.'
            : 'Your payment was not completed. Please check the booking and payment status before retrying.';

        return view(
            'frontend.sanatanevents.booking-failed',
            compact('booking', 'message', 'status')
        );
    }
}