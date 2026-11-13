<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\OnlinePuja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;

class FrontendOnlinePujaCartController extends Controller
{
    public function selectPackage(Request $request)
    {
        $validated = $request->validate([
            'puja_id' => 'required|integer',
            'package_id' => 'required|integer',
        ]);
        $puja = OnlinePuja::with('packages')
            ->where('id', $validated['puja_id'])
            ->where('status', 1)
            ->whereNotNull('puja_datetime')
            ->where('puja_datetime', '>=', now())
            ->firstOrFail();
        $package = $puja->packages()
            ->where('id', $validated['package_id'])
            ->where('status', 1)
            ->firstOrFail();
        $availableSeats = max(0, (int) $puja->total_seat - (int) $puja->booked_seat);
        if ($puja->total_seat && $availableSeats <= 0) {
            return back()->with('error', 'No seats available.');
        }
        if ($puja->total_seat && (int) $package->devotees_count > $availableSeats
        ) {
            return back()->with('error', 'Not enough seats available for this package.'
            );
        }
        session()->put('online_puja_cart', [
            'puja_id' => $puja->id,
            'package_id' => $package->id,
            'created_at' => now()->timestamp,
        ]);
        if (!Auth::check()) {session()->put('url.intended', route('frontend.online-puja.cart')            );
            return redirect()->route('login');
        }
        return redirect()->route('frontend.online-puja.cart');
    }

    public function cart()
    {
        $cart = session('online_puja_cart');
        if (!$cart) {
            return redirect()
                ->route('frontend.puja')
                ->with('error', 'Please select a puja package first.');
        }
        $puja = OnlinePuja::with(['category', 'packages'])
            ->where('id', $cart['puja_id'])
            ->where('status', 1)
            ->first();
        if (!$puja) {
            session()->forget('online_puja_cart');
            return redirect()
                ->route('frontend.puja')
                ->with('error', 'Selected puja is no longer available.');
        }
        $package = $puja->packages()
            ->where('id', $cart['package_id'])
            ->where('status', 1)
            ->first();
        if (!$package) {
            session()->forget('online_puja_cart');
            return redirect()
                ->route('frontend.online-puja.show', $puja->slug)
                ->with('error', 'Selected package is no longer available.');
        }
        $availableSeats = max(
            0,
            (int) $puja->total_seat - (int) $puja->booked_seat
        );
        if (
            $puja->total_seat &&
            (
                $availableSeats <= 0 ||
                (int) $package->devotees_count > $availableSeats
            )
        ) {
            session()->forget('online_puja_cart');
            return redirect()
                ->route('frontend.online-puja.show', $puja->slug)
                ->with('error', 'Required seats are no longer available.');
        }
        return view('frontend.online-puja.cart', compact(
            'puja',
            'package'
        ));
    }

    public function checkout()
    {
        $cart = session('online_puja_cart');
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }
        $puja = OnlinePuja::with('category')
            ->where('id', $cart['puja_id'])
            ->where('status', 1)
            ->whereNotNull('puja_datetime')
            ->where('puja_datetime', '>=', now())
            ->first();
        if (!$puja) {
            session()->forget('online_puja_cart');
            return response()->json([
                'success' => false,
                'message' => 'This puja is no longer available.',
            ], 422);
        }
        $package = $puja->packages()
            ->where('id', $cart['package_id'])
            ->where('status', 1)
            ->first();
        if (!$package) {
            session()->forget('online_puja_cart');
            return response()->json([
                'success' => false,
                'message' => 'This package is no longer available.',
            ], 422);
        }
        $availableSeats = max(
            0,
            (int) $puja->total_seat - (int) $puja->booked_seat
        );
        if (
            $puja->total_seat &&
            (
                $availableSeats <= 0 ||
                (int) $package->devotees_count > $availableSeats
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Required seats are no longer available.',
            ], 422);
        }
        $amount = (float) $package->price;
        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid package amount.',
            ], 422);
        }
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        if (!$key || !$secret) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway is not configured.',
            ], 500);
        }
        $user = Auth::user();
        $orderNumber = 'OPU' . now()->format('YmdHis') . Str::upper(Str::random(5));
        try {
            $api = new Api($key, $secret);
            $razorpayOrder = $api->order->create([
                'receipt' => $orderNumber,
                'amount' => (int) round($amount * 100),
                'currency' => 'INR',
                'notes' => [
                    'order_number' => $orderNumber,
                    'puja_id' => (string) $puja->id,
                    'package_id' => (string) $package->id,
                ],
            ]);
            DB::table('online_puja_orders')->insert([
                'order_number' => $orderNumber,
                'customer_id' => $user->id,
                'customer_name' => $user->getAttribute('name') ?: 'Customer',
                'customer_email' => $user->getAttribute('email'),
                'customer_phone' => $user->getAttribute('phone')
                    ?: $user->getAttribute('mobile'),
                'online_puja_id' => $puja->id,
                'online_puja_cat_id' => $puja->online_puja_category_id,
                'package_id' => $package->id,
                'package_name' => $package->package_name,
                'amount' => $amount,
                'currency' => 'INR',
                'payment_status' => 'pending',
                'razorpay_order_id' => $razorpayOrder['id'],
                'booking_status' => 'pending',
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => (int) round($amount * 100),
                'currency' => 'INR',
                'key' => $key,
                'customer_name' => $user->getAttribute('name'),
                'customer_email' => $user->getAttribute('email'),
                'customer_phone' => $user->getAttribute('phone')
                    ?: $user->getAttribute('mobile'),
                'puja_title' => $puja->title,
                'package_name' => $package->package_name,
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
            'order_number' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);
        $order = DB::table('online_puja_orders')
            ->where('order_number', $validated['order_number'])
            ->where('customer_id', Auth::id())
            ->first();

        if (
            !$order ||
            $order->razorpay_order_id !== $validated['razorpay_order_id']
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order details.',
            ], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already verified.',
                'redirect_url' => route('frontend.online-puja.index'),
            ]);
        }

        $secret = config('services.razorpay.secret');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification is not configured.',
            ], 500);
        }

        $expectedSignature = hash_hmac(
            'sha256',
            $validated['razorpay_order_id'] . '|' .
                $validated['razorpay_payment_id'],
            $secret
        );

        if (!hash_equals($expectedSignature, $validated['razorpay_signature'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed.',
            ], 422);
        }

        try {
            $api = new Api(config('services.razorpay.key'), $secret);
            $payment = $api->payment->fetch(
                $validated['razorpay_payment_id']
            );

            if ($payment['order_id'] !== $validated['razorpay_order_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment does not match this order.',
                ], 422);
            }

            if ($payment['status'] !== 'captured') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has not been captured yet. Please contact support if money was deducted.',
                ], 422);
            }

            if (
                (int) $payment['amount'] !==
                (int) round((float) $order->amount * 100)
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount does not match the booking amount.',
                ], 422);
            }

            $bookingResult = DB::transaction(function () use (
                $order,
                $validated
            ) {
                $lockedOrder = DB::table('online_puja_orders')
                    ->where('id', $order->id)
                    ->lockForUpdate()
                    ->first();

                if ($lockedOrder->payment_status === 'paid') {
                    return 'already_paid';
                }

                $puja = OnlinePuja::where('id', $lockedOrder->online_puja_id)
                    ->lockForUpdate()
                    ->first();

                if (!$puja) {
                    DB::table('online_puja_orders')
                        ->where('id', $lockedOrder->id)
                        ->update([
                            'razorpay_payment_id' => $validated['razorpay_payment_id'],
                            'razorpay_signature' => $validated['razorpay_signature'],
                            'payment_status' => 'paid',
                            'booking_status' => 'pending_review',
                            'updated_at' => now(),
                        ]);

                    return 'pending_review';
                }

                $package = $puja->packages()
                    ->where('id', $lockedOrder->package_id)
                    ->first();

                if (!$package) {
                    DB::table('online_puja_orders')
                        ->where('id', $lockedOrder->id)
                        ->update([
                            'razorpay_payment_id' => $validated['razorpay_payment_id'],
                            'razorpay_signature' => $validated['razorpay_signature'],
                            'payment_status' => 'paid',
                            'booking_status' => 'pending_review',
                            'updated_at' => now(),
                        ]);

                    return 'pending_review';
                }

                $requiredSeats = max(1, (int) $package->devotees_count);
                $totalSeats = (int) ($puja->total_seat ?? 0);
                $bookedSeats = (int) ($puja->booked_seat ?? 0);
                $availableSeats = max(0, $totalSeats - $bookedSeats);

                if ($totalSeats > 0 && $requiredSeats > $availableSeats) {
                    DB::table('online_puja_orders')
                        ->where('id', $lockedOrder->id)
                        ->update([
                            'razorpay_payment_id' => $validated['razorpay_payment_id'],
                            'razorpay_signature' => $validated['razorpay_signature'],
                            'payment_status' => 'paid',
                            'booking_status' => 'pending_review',
                            'updated_at' => now(),
                        ]);

                    return 'pending_review';
                }

                $puja->booked_seat = $bookedSeats + $requiredSeats;
                $puja->save();

                DB::table('online_puja_orders')
                    ->where('id', $lockedOrder->id)
                    ->update([
                        'razorpay_payment_id' => $validated['razorpay_payment_id'],
                        'razorpay_signature' => $validated['razorpay_signature'],
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                        'updated_at' => now(),
                    ]);

                return 'confirmed';
            });

            session()->forget('online_puja_cart');

            if ($bookingResult === 'pending_review') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment received successfully, but seat availability needs verification. Our team will contact you shortly.',
                    'redirect_url' => route('frontend.online-puja.index'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $bookingResult === 'already_paid'
                    ? 'Payment already verified.'
                    : 'Payment successful. Your puja booking is confirmed.',
                'redirect_url' => route('frontend.online-puja.index'),
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to verify payment at the moment. Please contact support before attempting another payment.',
            ], 500);
        }
    }

    public function remove()
    {
        session()->forget('online_puja_cart');

        return redirect()
            ->route('frontend.online-puja.index')
            ->with('success', 'Package removed from cart.');
    }
}