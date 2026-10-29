<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\PujaBookingCustomerMail;
use App\Mail\PujaBookingPanditMail;
use App\Models\Puja;
use App\Models\PujaBookingPayment;
use App\Models\PujaPackage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'product_ids' => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'distinct', 'min:1'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
        ]);
        try {
            $user = $request->user();

            if (blank($user->phone)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please add your mobile number to your account before booking a puja.',
                ], 422);
            }

            $package = PujaPackage::query()->where('status', 1)->with('puja')->findOrFail($validated['package_id']);
            $puja = $package->puja;
            if (!$puja || (int) $puja->status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected puja is not available.',
                ], 422);
            }
            $snapshot = $package->products;
            if (is_string($snapshot)) {
                $snapshot = json_decode($snapshot, true);
            }
            $snapshot = is_array($snapshot) ? $snapshot : [];
            $packageProducts = collect($snapshot)->map(function ($item) {
                $item = is_array($item) ? $item : [];
                $price = max(0, (float) ($item['price'] ?? 0));
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $productId = (int) ($item['product_id'] ?? 0);
                return [
                    'product_id' => $productId > 0 ? $productId : null,
                    'name' => (string) ($item['name'] ?? 'Puja Samagri'),
                    'unit' => (string) ($item['unit'] ?? 'Item'),
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => round($price * $quantity, 2),
                ];
            })->values();
            $selectedIds = collect($validated['product_ids'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();
            $availableIds = $packageProducts
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->values();
            if ($selectedIds->diff($availableIds)->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A selected product does not belong to this package.',
                ], 422);
            }
            $selectedProducts = $packageProducts
                ->filter(fn ($product) =>
                    $product['product_id'] !== null
                    && $selectedIds->contains($product['product_id'])
                )
                ->values();
            $originalPrice = max(0, (float) $package->price);
            $allProductTotal = round(
                (float) $packageProducts->sum('line_total'),
                2
            );
            $basePackagePrice = max(0, round($originalPrice - $allProductTotal, 2));

            $discountPrice = $package->discount_price;
            $hasDiscount = $discountPrice !== null
                && $discountPrice !== ''
                && (float) $discountPrice >= 0
                && (float) $discountPrice < $originalPrice;

            $discountFactor = $hasDiscount && $originalPrice > 0
                ? (float) $discountPrice / $originalPrice
                : 1;

            $finalPackagePrice = round($basePackagePrice * $discountFactor, 2);
            $selectedProductsTotal = round(
                (float) $selectedProducts->sum('line_total'),
                2
            );
            $total = round($finalPackagePrice + $selectedProductsTotal, 2);
            $amountPaise = (int) round($total * 100);

            if ($amountPaise < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'The total amount must be at least ₹1.00.',
                ], 422);
            }

            $selectedProductsSnapshot = $selectedProducts->map(fn ($product) => [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'unit' => $product['unit'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'line_total' => $product['line_total'],
            ])->values()->all();

            $api = $this->razorpay();
            $order = $api->order->create([
                'receipt' => 'puja_' . Str::lower(Str::random(20)),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'notes' => [
                    'user_id' => (string) $request->user()->id,
                    'puja_id' => (string) $puja->id,
                    'package_id' => (string) $package->id,
                    'booking_date' => $validated['booking_date'],
                    'booking_time' => $validated['booking_time'],
                ],
            ]);
            $paymentRecord = PujaBookingPayment::create([
                'user_id' => $request->user()->id,
                'puja_id' => $puja->id,
                'puja_package_id' => $package->id,
                'amount_paise' => $amountPaise,
                'currency' => 'INR',
                'razorpay_order_id' => $order['id'],
                'status' => 'pending',
            ]);

            // Persist the selected product details against this exact Razorpay order.
            DB::table('puja_booking_payments')
                ->where('id', $paymentRecord->id)
                ->update([
                    'selected_products' => json_encode(
                        $selectedProductsSnapshot,
                        JSON_THROW_ON_ERROR
                    ),
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
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
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
                    $this->assignPanditToBooking(
                        (int) $lockedPayment->puja_booking_id
                    );
                    return (int) $lockedPayment->puja_booking_id;
                }
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
                        $this->assignPanditToBooking(
                            (int) $existingBooking->id
                        );
                        return (int) $existingBooking->id;
                    }
                }
                $package = PujaPackage::with('puja')->findOrFail($lockedPayment->puja_package_id);
                $puja = $package->puja;
                $user = $request->user();
                if (!$puja) {
                    throw new \RuntimeException(
                        'The puja associated with this payment could not be found.'
                    );
                }
                $paidAmount = round($lockedPayment->amount_paise / 100, 2);

                // Use the snapshot saved at createOrder; never trust product_ids
                // submitted again by the browser during payment verification.
                $selectedProducts = $lockedPayment->selected_products ?? [];
                if (is_string($selectedProducts)) {
                    $selectedProducts = json_decode($selectedProducts, true);
                }
                $selectedProducts = is_array($selectedProducts)
                    ? array_values($selectedProducts)
                    : [];

                $selectedProductsTotal = round(
                    (float) collect($selectedProducts)->sum('line_total'),
                    2
                );

                $packageSnapshot = $package->products;
                if (is_string($packageSnapshot)) {
                    $packageSnapshot = json_decode($packageSnapshot, true);
                }
                $packageSnapshot = is_array($packageSnapshot)
                    ? $packageSnapshot
                    : [];

                $allProductTotal = collect($packageSnapshot)->sum(
                    fn ($item) => max(0, (float) ($item['price'] ?? 0))
                        * max(1, (int) ($item['quantity'] ?? 1))
                );

                // The base amount is the pandit/service component, excluding
                // all products bundled into the original package price.
                $baseAmount = max(
                    0,
                    round((float) $package->price - $allProductTotal, 2)
                );

                $discountPrice = $package->discount_price;
                $hasDiscount = $discountPrice !== null
                    && $discountPrice !== ''
                    && (float) $discountPrice >= 0
                    && (float) $discountPrice < (float) $package->price;
                $discountFactor = $hasDiscount && (float) $package->price > 0
                    ? (float) $discountPrice / (float) $package->price
                    : 1;
                $finalPackageAmount = round($baseAmount * $discountFactor, 2);
                $discountAmount = max(
                    0,
                    round($baseAmount - $finalPackageAmount, 2)
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
                        'schedule_date' => $validated['booking_date'],
                        'schedule_time' => $validated['booking_time'],
                        'customer_name' => $user->name,
                        'customer_email' => $user->email,
                        'customer_mobile' => $user->phone,
                        'address_id' => null,
                        'package_name' => $package->name,
                        'person_count' => (int) (
                            $package->person_count ?? 1
                        ),
                        'selected_products' => json_encode(
                            $selectedProducts,
                            JSON_THROW_ON_ERROR
                        ),
                        'base_amount' => $baseAmount,
                        'discount_amount' => $discountAmount,
                        'coupon_discount' => 0,
                        'addon_amount' => $selectedProductsTotal,
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
                $this->assignPanditToBooking((int) $bookingId);
                return (int) $bookingId;
            });
            $bookingPayment->refresh();
            if (!$bookingPayment->puja_booking_id) {
                throw new \RuntimeException(
                    'Booking was not linked to the payment record.'
                );
            }
            try {
                $claimed = DB::table('puja_booking_payments')
                    ->where('id', $bookingPayment->id)
                    ->where('status', 'paid')
                    ->whereNotNull('puja_booking_id')
                    ->whereNull('emails_sent_at')
                    ->update([
                        'emails_sent_at' => now(),
                    ]);

                if ($claimed === 1) {
                    $booking = DB::table('puja_bookings')
                        ->where('id', $bookingPayment->puja_booking_id)
                        ->first();

                    if (!$booking) {
                        throw new \RuntimeException(
                            'Booking not found while preparing email notifications.'
                        );
                    }

                    $customer = User::find($booking->user_id);
                    $pandit = $booking->pandit_id
                        ? User::find($booking->pandit_id)
                        : null;

                    // Load the puja and package details required by both emails.
                    $puja = Puja::find($booking->puja_id);
                    $package = PujaPackage::with('features')
                        ->find($booking->puja_package_id);

                    // Convert package features into a simple array for the Blade email.
                    // The fallback field names help accommodate common PackageFeature schemas.
                    $packageFeatures = $package
                        ? $package->features->map(function ($feature) {
                            $name = $feature->getAttribute('name')
                                ?? $feature->getAttribute('title')
                                ?? $feature->getAttribute('feature_name')
                                ?? $feature->getAttribute('feature')
                                ?? 'Feature';

                            $description = $feature->getAttribute('description')
                                ?? $feature->getAttribute('details')
                                ?? null;

                            return [
                                'name' => $name,
                                'description' => $description,
                            ];
                        })->values()->toArray()
                        : [];

                    $details = [
                        // Booking and customer details
                        'booking_number' => $booking->booking_number,
                        'customer_name' => $booking->customer_name,
                        'customer_email' => $booking->customer_email,
                        'customer_mobile' => $booking->customer_mobile,
                        'booking_date' => $booking->schedule_date,
                        'booking_time' => $booking->schedule_time,
                        'amount' => (float) $booking->total_amount,
                        'payment_id' => $validated['razorpay_payment_id'],

                        // Puja details
                        'puja_name' => $puja?->name ?? 'Puja',
                        'puja_description' => $puja?->description,
                        'puja_benefits' => $puja?->benefits,
                        'ritual_details' => $puja?->ritual_details,
                        'puja_samagri_details' => $puja?->samagri_details,
                        'puja_duration' => $puja?->duration,

                        // Package details
                        'package_name' => $booking->package_name
                            ?? $package?->name
                            ?? 'Package',
                        'package_short_description' => $package?->short_description,
                        'package_description' => $package?->description,
                        'person_count' => $booking->person_count
                            ?? $package?->person_count,
                        'package_price' => $package?->price,
                        'discount_price' => $package?->discount_price,
                        'includes_sankalp' => (bool) ($package?->includes_sankalp ?? false),
                        'includes_prasad' => (bool) ($package?->includes_prasad ?? false),
                        'includes_video' => (bool) ($package?->includes_video ?? false),
                        'includes_certificate' => (bool) ($package?->includes_certificate ?? false),
                        'package_samagri' => $package?->puja_samagri ?? [],
                        'package_features' => $packageFeatures,
                    ];

                    // Send the customer confirmation email.
                    if ($customer && filled($customer->email)) {
                        Mail::to($customer->email)->send(
                            new PujaBookingCustomerMail($details)
                        );
                    } else {
                        Log::warning(
                            'Customer booking email skipped: customer email is missing.',
                            ['booking_id' => $booking->id]
                        );
                    }

                    // Send a separate notification to the assigned pandit.
                    if ($pandit && filled($pandit->email)) {
                        Mail::to($pandit->email)->send(
                            new PujaBookingPanditMail(array_merge(
                                $details,
                                ['pandit_name' => $pandit->name]
                            ))
                        );
                    } else {
                        Log::warning(
                            'Pandit booking email skipped: no assigned pandit or pandit email.',
                            [
                                'booking_id' => $booking->id,
                                'pandit_id' => $booking->pandit_id,
                            ]
                        );
                    }
                }
            } catch (Throwable $mailException) {
                // Do not turn a verified, paid booking into a failed payment
                // response merely because email delivery failed.
                Log::error('Puja booking email notification failed.', [
                    'payment_record_id' => $bookingPayment->id,
                    'booking_id' => $bookingPayment->puja_booking_id,
                    'exception' => $mailException->getMessage(),
                ]);
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
        return view('frontend.checkout.payment-success', compact('payment','booking','puja','package'));
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
    private function assignPanditToBooking(int $bookingId): void
    {
        $booking = DB::table('puja_bookings')
            ->where('id', $bookingId)
            ->lockForUpdate()
            ->first();
        if (!$booking || $booking->pandit_id || !$booking->temple_id) {
            return;
        }
        // Serialize Pandit assignment for bookings belonging to this Temple.
        DB::table('temples')
            ->where('id', $booking->temple_id)
            ->lockForUpdate()
            ->first();
        $booking = DB::table('puja_bookings')
            ->where('id', $bookingId)
            ->lockForUpdate()
            ->first();
        if (!$booking || $booking->pandit_id || !$booking->temple_id) {
            return;
        }
        $panditIds = User::query()
            ->where('status', true)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Pandit')
                    ->where('guard_name', 'web');
            })
            ->whereHas('temples', function ($query) use ($booking) {
                $query->where('temples.id', $booking->temple_id)
                    ->where('temple_user.approval_status', 'approved');
            })
            ->orderBy('users.id')
            ->pluck('users.id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        if (empty($panditIds)) {
            Log::warning('No eligible Pandit found for booking.', [
                'booking_id' => $bookingId,
                'temple_id' => $booking->temple_id,
            ]);
            return;
        }
        $lastPanditId = DB::table('puja_bookings')
            ->where('temple_id', $booking->temple_id)
            ->whereNotNull('pandit_id')
            ->where('id', '!=', $bookingId)
            ->orderByDesc('id')
            ->value('pandit_id');
        $nextPanditId = $panditIds[0];
        if ($lastPanditId !== null) {
            $lastIndex = array_search(
                (int) $lastPanditId,
                $panditIds,
                true
            );
            if ($lastIndex !== false) {
                $nextPanditId = $panditIds[
                    ($lastIndex + 1) % count($panditIds)
                ];
            }
        }
        DB::table('puja_bookings')
            ->where('id', $bookingId)
            ->whereNull('pandit_id')
            ->update([
                'pandit_id' => $nextPanditId,
                'updated_at' => now(),
            ]);
        Log::info('Pandit assigned to Puja booking.', [
            'booking_id' => $bookingId,
            'temple_id' => $booking->temple_id,
            'pandit_id' => $nextPanditId,
        ]);
    }
}