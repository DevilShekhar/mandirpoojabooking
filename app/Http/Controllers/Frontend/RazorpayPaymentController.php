<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PujaBookingPayment;
use App\Models\PujaPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;

class RazorpayPaymentController extends Controller
{
    private function razorpay(): Api
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (!$key || !$secret) {
            throw new \RuntimeException(
                'Razorpay credentials are not configured.'
            );
        }

        return new Api($key, $secret);
    }

    public function createOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $package = PujaPackage::with('puja')
                ->findOrFail($validated['package_id']);

            $puja = $package->puja;

            if (!$puja || (int) $puja->status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected puja is not available.',
                ], 422);
            }

            $baseAmount = (float) $package->price;

            $finalAmount = (
                $package->discount_price !== null &&
                $package->discount_price !== '' &&
                (float) $package->discount_price < $baseAmount
            )
                ? (float) $package->discount_price
                : $baseAmount;

            $amountPaise = (int) round($finalAmount * 100);

            if ($amountPaise < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'The package amount must be at least ₹1.00.',
                ], 422);
            }

            $api = $this->razorpay();

            $order = $api->order->create([
                'receipt' => 'puja_' . Str::lower(Str::random(20)),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'notes' => [
                    'user_id' => (string) $request->user()->id,
                    'puja_id' => (string) $puja->id,
                    'package_id' => (string) $package->id,
                ],
            ]);

            PujaBookingPayment::create([
                'user_id' => $request->user()->id,
                'puja_id' => $puja->id,
                'puja_package_id' => $package->id,
                'amount_paise' => $amountPaise,
                'currency' => 'INR',
                'razorpay_order_id' => $order['id'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'key' => config('services.razorpay.key'),
                'order_id' => $order['id'],
                'amount' => $amountPaise,
                'currency' => 'INR',
                'name' => config('app.name', 'Puja Booking'),
                'description' => $puja->name . ' - ' . $package->name,
                'prefill' => [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'contact' => $request->user()->phone ?? '',
                ],
                'notes' => [
                    'puja' => $puja->name,
                    'package' => $package->name,
                ],
            ]);
        } catch (Throwable $exception) {
            Log::error('Razorpay order creation failed.', [
                'user_id' => $request->user()?->id,
                'exception' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to start payment right now. Please try again later.',
            ], 500);
        }
    }

    public function verifyPayment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $bookingPayment = PujaBookingPayment::where(
            'razorpay_order_id',
            $validated['razorpay_order_id']
        )
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $secret = config('services.razorpay.secret');

            if (!$secret) {
                throw new \RuntimeException(
                    'Razorpay secret is not configured.'
                );
            }

            $expectedSignature = hash_hmac(
                'sha256',
                $validated['razorpay_order_id'] . '|' .
                    $validated['razorpay_payment_id'],
                (string) $secret
            );

            if (!hash_equals(
                $expectedSignature,
                $validated['razorpay_signature']
            )) {
                Log::warning('Razorpay signature verification failed.', [
                    'payment_record_id' => $bookingPayment->id,
                    'order_id' => $validated['razorpay_order_id'],
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed. If money was deducted, please contact support.',
                ], 422);
            }

            $razorpayPayment = $this->razorpay()
                ->payment
                ->fetch($validated['razorpay_payment_id']);

            if (
                ($razorpayPayment['order_id'] ?? null) !==
                    $bookingPayment->razorpay_order_id ||
                (int) ($razorpayPayment['amount'] ?? 0) !==
                    (int) $bookingPayment->amount_paise ||
                strtoupper((string) ($razorpayPayment['currency'] ?? '')) !==
                    strtoupper($bookingPayment->currency) ||
                ($razorpayPayment['status'] ?? '') !== 'captured'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has not been confirmed as captured. Please wait or contact support.',
                ], 422);
            }

            $existingPayment = PujaBookingPayment::where(
                'razorpay_payment_id',
                $validated['razorpay_payment_id']
            )
                ->where('id', '!=', $bookingPayment->id)
                ->exists();

            if ($existingPayment) {
                throw new \RuntimeException(
                    'This Razorpay payment ID is already associated with another payment record.'
                );
            }

            $bookingId = DB::transaction(function () use (
                $bookingPayment,
                $validated,
                $request
            ) {
                $lockedPayment = PujaBookingPayment::whereKey(
                    $bookingPayment->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedPayment->puja_booking_id) {
                    $lockedPayment->update([
                        'razorpay_payment_id' =>
                            $validated['razorpay_payment_id'],
                        'razorpay_signature' =>
                            $validated['razorpay_signature'],
                        'status' => 'paid',
                    ]);

                    return (int) $lockedPayment->puja_booking_id;
                }

                /*
                 * Recover a booking created earlier for this already-paid
                 * payment, but not linked to its payment record.
                 */
                if ($lockedPayment->status === 'paid') {
                    $paymentCreatedAt = $lockedPayment->created_at;

                    $matchingBookings = DB::table('puja_bookings')
                        ->where('user_id', $lockedPayment->user_id)
                        ->where('puja_id', $lockedPayment->puja_id)
                        ->where(
                            'puja_package_id',
                            $lockedPayment->puja_package_id
                        )
                        ->where('payment_status', 'paid')
                        ->where(
                            'total_amount',
                            number_format(
                                $lockedPayment->amount_paise / 100,
                                2,
                                '.',
                                ''
                            )
                        )
                        ->whereBetween('created_at', [
                            $paymentCreatedAt,
                            $paymentCreatedAt->copy()->addMinutes(15),
                        ])
                        ->lockForUpdate()
                        ->get();

                    if ($matchingBookings->count() > 1) {
                        throw new \RuntimeException(
                            'Multiple existing bookings match this paid payment. Manual reconciliation is required.'
                        );
                    }

                    if ($matchingBookings->count() === 1) {
                        $existingBooking = $matchingBookings->first();

                        $lockedPayment->update([
                            'puja_booking_id' => $existingBooking->id,
                            'razorpay_payment_id' =>
                                $validated['razorpay_payment_id'],
                            'razorpay_signature' =>
                                $validated['razorpay_signature'],
                            'status' => 'paid',
                        ]);

                        return (int) $existingBooking->id;
                    }
                }

                $package = PujaPackage::with('puja')
                    ->findOrFail($lockedPayment->puja_package_id);

                $puja = $package->puja;
                $user = $request->user();

                if (!$puja) {
                    throw new \RuntimeException(
                        'The puja associated with this payment could not be found.'
                    );
                }

                /*
                 * Use the amount stored when the Razorpay order was created.
                 * Do not reject a captured payment just because the package
                 * price changed after the order was created.
                 */
                $paidAmount = $lockedPayment->amount_paise / 100;
                $currentBaseAmount = (float) $package->price;

                $baseAmount = max(
                    $currentBaseAmount,
                    $paidAmount
                );

                $discountAmount = max(
                    0,
                    $baseAmount - $paidAmount
                );

                $bookingNumber = 'PB' .
                    now()->format('YmdHis') .
                    Str::upper(Str::random(5));

                while (
                    DB::table('puja_bookings')
                        ->where('booking_number', $bookingNumber)
                        ->exists()
                ) {
                    $bookingNumber = 'PB' .
                        now()->format('YmdHis') .
                        Str::upper(Str::random(5));
                }

                $bookingId = DB::table('puja_bookings')
                    ->insertGetId([
                        'booking_number' => $bookingNumber,
                        'user_id' => $user->id,
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                        'temple_id' => $puja->temple_id
                            ?? $puja->temple?->id,
                        'deity_id' => $puja->deity_id
                            ?? $puja->deity?->id,
                        'puja_id' => $puja->id,
                        'puja_package_id' => $package->id,
                        'puja_schedule_id' => null,
                        'customer_name' => $user->name,
                        'customer_email' => $user->email,
                        'customer_mobile' => $user->phone ?? null,
                        'address_id' => null,
                        'package_name' => $package->name,
                        'person_count' => (int) (
                            $package->person_count ?? 1
                        ),
                        'base_amount' => $baseAmount,
                        'discount_amount' => $discountAmount,
                        'coupon_discount' => 0,
                        'addon_amount' => 0,
                        'shipping_amount' => 0,
                        'tax_amount' => 0,
                        'total_amount' => $paidAmount,
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                        'sankalp_status' => 'pending',
                        'puja_status' => 'pending',
                        'video_status' => 'pending',
                        'prasad_status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                $lockedPayment->update([
                    'puja_booking_id' => $bookingId,
                    'razorpay_payment_id' =>
                        $validated['razorpay_payment_id'],
                    'razorpay_signature' =>
                        $validated['razorpay_signature'],
                    'status' => 'paid',
                ]);

                return (int) $bookingId;
            });

            $bookingPayment->refresh();

            if (!$bookingPayment->puja_booking_id) {
                throw new \RuntimeException(
                    'Booking was not linked to the payment record.'
                );
            }

            return response()->json([
                'success' => true,
                'booking_id' => $bookingId,
                'redirect_url' => route(
                    'razorpay.success',
                    ['payment' => $bookingPayment->id]
                ),
            ]);
        } catch (Throwable $exception) {
            Log::error(
                'Razorpay payment verification or puja booking creation failed.',
                [
                    'payment_record_id' => $bookingPayment->id,
                    'razorpay_order_id' =>
                        $validated['razorpay_order_id'],
                    'razorpay_payment_id' =>
                        $validated['razorpay_payment_id'],
                    'user_id' => $request->user()?->id,
                    'exception' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Payment confirmation could not be completed. Please do not pay again. Contact support with Razorpay payment ID: ' .
                    $validated['razorpay_payment_id'],
            ], 500);
        }
    }

    public function success(PujaBookingPayment $payment)
    {
        abort_unless(
            (int) $payment->user_id === (int) auth()->id() &&
            $payment->status === 'paid' &&
            $payment->puja_booking_id !== null,
            404
        );

        $payment->load([
            'puja',
            'package',
            'booking',
        ]);

        $booking = $payment->booking;
        $puja = $payment->puja;
        $package = $payment->package;

        abort_unless($booking, 404);

        return view(
            'frontend.checkout.payment-success',
            compact(
                'payment',
                'booking',
                'puja',
                'package'
            )
        );
    }
    public function failed(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'error_code' => ['nullable', 'string', 'max:100'],
            'error_description' => ['nullable', 'string', 'max:1000'],
            'error_source' => ['nullable', 'string', 'max:100'],
            'error_step' => ['nullable', 'string', 'max:100'],
            'error_reason' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $payment = DB::transaction(function () use (
                $validated,
                $request
            ) {
                $payment = PujaBookingPayment::where(
                    'razorpay_order_id',
                    $validated['razorpay_order_id']
                )
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->first();

                if (!$payment) {
                    return null;
                }

                // Never change a paid payment to failed.
                if ($payment->status === 'paid') {
                    return $payment;
                }

                $payment->update([
                    'status' => 'failed',
                    'error_code' => $validated['error_code'] ?? null,
                    'error_description' =>
                        $validated['error_description'] ?? null,
                    'error_source' => $validated['error_source'] ?? null,
                    'error_step' => $validated['error_step'] ?? null,
                    'error_reason' => $validated['error_reason'] ?? null,
                ]);

                return $payment;
            });

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment record not found.',
                ], 404);
            }

            if ($payment->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment has already been completed.',
                ], 422);
            }

            Log::warning('Razorpay payment failed.', [
                'payment_record_id' => $payment->id,
                'user_id' => $request->user()->id,
                'razorpay_order_id' => $payment->razorpay_order_id,
                'error_code' => $validated['error_code'] ?? null,
                'error_description' =>
                    $validated['error_description'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route(
                    'razorpay.failed-page',
                    ['payment' => $payment->id]
                ),
            ]);

        } catch (Throwable $exception) {
            Log::error('Razorpay failure handling failed.', [
                'user_id' => $request->user()?->id,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process payment failure.',
            ], 500);
        }
    }


    /**
     * Show payment failed page.
     */
    public function failedPage(PujaBookingPayment $payment)
    {
        abort_unless(
            (int) $payment->user_id === (int) auth()->id() &&
            $payment->status === 'failed',
            404
        );

        $payment->load(['puja', 'package']);

        return view(
            'frontend.checkout.payment-failed',
            compact('payment')
        );
    }
}