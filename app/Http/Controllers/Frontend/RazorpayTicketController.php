<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TempleDailyTicketLimit;
use App\Models\TicketBooking;
use App\Models\TicketCart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Throwable;
use DomainException;

class RazorpayTicketController extends Controller
{
    private function razorpay(): Api
    {
        return new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Create a Razorpay order for all active cart members.
     */
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

        // Do not create a payment order if any cart member has no visit time.
        if ($cartItems->contains(fn ($item) => blank($item->booking_time))) {
            return response()->json([
                'success' => false,
                'message' => 'A visit time is missing from your cart. Please remove the affected member and add it again with a visit time.',
            ], 422);
        }

        try {
            $this->checkCartAvailability($cartItems, true);

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
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create payment order. Please try again.',
            ], 500);
        }
    }

    /**
     * Verify payment and save all members for one Razorpay order.
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $paymentCaptured = false;

        try {
            $api = $this->razorpay();

            // Verify the Razorpay payment signature.
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            // Verify the order belongs to the logged-in customer.
            $order = $api->order->fetch(
                $validated['razorpay_order_id']
            );

            $orderUserId = (int) ($order['notes']['user_id'] ?? 0);

            if ($orderUserId !== (int) auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This payment order does not belong to your account.',
                ], 403);
            }

            $expectedMembers = (int) (
                $order['notes']['member_count'] ?? 0
            );

            if ($expectedMembers < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'The original order has no valid member count.',
                ], 422);
            }

            // Fetch and verify the actual payment.
            $payment = $api->payment->fetch(
                $validated['razorpay_payment_id']
            );

            if (
                $payment['order_id'] !== $validated['razorpay_order_id'] ||
                $payment['currency'] !== 'INR'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment details do not match the order.',
                ], 422);
            }

            if ($payment['status'] !== 'captured') {
                return response()->json([
                    'success' => false,
                    'message' => 'Razorpay has not confirmed this payment as captured.',
                    'payment_captured' => false,
                ], 422);
            }

            $paymentCaptured = true;

            if ((int) $payment['amount'] !== (int) $order['amount']) {
                throw new DomainException(
                    'The captured payment amount does not match the Razorpay order. Contact support for reconciliation.'
                );
            }

            DB::transaction(function () use (
                $validated,
                $payment,
                $order,
                $expectedMembers
            ) {
                // Lock any bookings already created for this order.
                $existingBookings = TicketBooking::where(
                    'razorpay_order_id',
                    $validated['razorpay_order_id']
                )
                    ->lockForUpdate()
                    ->get();

                foreach ($existingBookings as $existing) {
                    if (
                        $existing->razorpay_payment_id !==
                        $validated['razorpay_payment_id']
                    ) {
                        throw new DomainException(
                            'This order is already associated with a different payment. Contact support.'
                        );
                    }
                }

                // A retry after successful booking should not create duplicates.
                $cartItems = TicketCart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->orderBy('temple_id')
                    ->orderBy('booking_date')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    if ($existingBookings->count() === $expectedMembers) {
                        // Idempotent retry: bookings were already created and cart rows removed.
                        return;
                    }

                    throw new DomainException(
                        'The payment is captured, but the complete booking could not be found. Contact support before retrying.'
                    );
                }

                // Never create a confirmed booking with a missing visit time.
                foreach ($cartItems as $cartItem) {
                    if (blank($cartItem->booking_time)) {
                        throw new DomainException(
                            'A visit time is missing from your cart. The payment was captured, but the booking was not saved. Contact support.'
                        );
                    }
                }

                // Validate the original order amount against the complete cart.
                if ($cartItems->count() !== $expectedMembers) {
                    throw new DomainException(
                        'The active cart member count differs from the original payment order. Contact support before retrying.'
                    );
                }

                $totalPaise = (int) round(
                    $cartItems->sum(fn ($item) => (float) $item->price) * 100
                );

                if (
                    $totalPaise !== (int) $payment['amount'] ||
                    $totalPaise !== (int) $order['amount']
                ) {
                    throw new DomainException(
                        'The payment amount does not match the original cart total. Contact support for reconciliation.'
                    );
                }

                // Find members already saved for this order.
                // This also permits recovery from an older partial booking.
                $matchedBookingIds = [];
                $missingItems = [];

                foreach ($cartItems as $item) {
                    $matched = $existingBookings->first(
                        function ($booking) use ($item, $matchedBookingIds) {
                            if (in_array(
                                $booking->id,
                                $matchedBookingIds,
                                true
                            )) {
                                return false;
                            }

                            return
                                (int) $booking->temple_id === (int) $item->temple_id &&
                                (string) $booking->booking_date === (string) $item->booking_date &&
                                (
                                    blank($booking->booking_time) ||
                                    $this->normalizeBookingTime($booking->booking_time) ===
                                        $this->normalizeBookingTime($item->booking_time)
                                ) &&
                                trim((string) $booking->member_name) === trim((string) $item->member_name) &&
                                (string) $booking->aadhaar_number === (string) $item->aadhaar_number &&
                                strtolower(trim((string) $booking->ticket_type)) ===
                                    strtolower(trim((string) $item->ticket_type)) &&
                                strtolower(trim((string) $booking->type)) ===
                                    strtolower(trim((string) $item->type)) &&
                                (int) $booking->age === (int) $item->age &&
                                (int) $booking->ticket_price_id === (int) $item->ticket_price_id &&
                                (int) round((float) $booking->price * 100) ===
                                    (int) round((float) $item->price * 100);
                        }
                    );

                    if ($matched) {
                        // Recover an older partial booking whose visit time was not saved.
                        if (blank($matched->booking_time)) {
                            $matched->booking_time = $this->normalizeBookingTime($item->booking_time);
                            $matched->save();
                        }

                        $matchedBookingIds[] = $matched->id;
                    } else {
                        $missingItems[] = $item;
                    }
                }

                // Do not silently accept a mismatched or oversized existing booking.
                if (
                    $existingBookings->count() > $expectedMembers ||
                    count($missingItems) + count($matchedBookingIds) !== $expectedMembers
                ) {
                    throw new DomainException(
                        'Existing booking records do not match the original order. Contact support for reconciliation.'
                    );
                }

                // Availability is checked only for members that still need saving.
                // Existing confirmed members already count toward capacity.
                if (!empty($missingItems)) {
                    $this->checkCartAvailability(
                        collect($missingItems),
                        true
                    );
                }

                // All members of one order share one booking reference.
                $bookingReference = $existingBookings->first()?->booking_reference
                    ?? ('TKT-' . strtoupper(Str::random(12)));

                foreach ($missingItems as $item) {
                    $booking = TicketBooking::create([
                        'booking_reference' => $bookingReference,
                        'user_id' => auth()->id(),
                        'temple_id' => $item->temple_id,
                        'booking_date' => $item->booking_date,
                        'booking_time' => $this->normalizeBookingTime($item->booking_time),
                        'member_name' => $item->member_name,
                        'aadhaar_number' => $item->aadhaar_number,
                        'aadhaar_photo' => $item->aadhaar_photo,
                        'ticket_type' => strtolower(trim($item->ticket_type)),
                        'type' => $item->type,
                        'age' => $item->age,
                        'ticket_price_id' => $item->ticket_price_id,
                        'price' => $item->price,
                        'razorpay_order_id' => $validated['razorpay_order_id'],
                        'razorpay_payment_id' => $validated['razorpay_payment_id'],
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed',
                    ]);

                    if (blank($booking->booking_time)) {
                        throw new DomainException(
                            'The visit time could not be saved to the booking. Contact support for payment reconciliation.'
                        );
                    }
                }

                // Remove cart rows only after every missing member is saved.
                TicketCart::whereIn('id', $cartItems->pluck('id'))->delete();
            }, 3);

            session()->forget([
                'ticket_payment_order_id',
                'ticket_payment_user_id',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified. All ticket members have been booked.',
                'redirect_url' => route('frontend.ticket-booking.success'),
            ]);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'payment_captured' => $paymentCaptured,
                'refund_required' => $paymentCaptured,
            ], 409);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $paymentCaptured
                    ? 'Payment is captured, but booking could not be completed. Contact support before retrying.'
                    : 'Payment verification failed. Please check your payment status.',
                'payment_captured' => $paymentCaptured,
            ], 500);
        }
    }

    /**
     * Check availability for the requested member rows.
     */
    private function checkCartAvailability(
        $cartItems,
        bool $lockLimits = false
    ): void {
        $groups = $cartItems->groupBy(function ($item) {
            return $item->temple_id . '|' .
                $item->booking_date . '|' .
                strtolower(trim($item->ticket_type));
        });

        foreach ($groups as $group) {
            $item = $group->first();

            $templeId = (int) $item->temple_id;
            $bookingDate = (string) $item->booking_date;
            $ticketType = strtolower(trim($item->ticket_type));
            $requested = $group->count();

            if (!in_array($ticketType, ['normal', 'vip'], true)) {
                throw new DomainException(
                    'Invalid ticket type in your cart.'
                );
            }

            $limitQuery = TempleDailyTicketLimit::where(
                'temple_id',
                $templeId
            )->whereRaw(
                'LOWER(TRIM(ticket_type)) = ?',
                [$ticketType]
            );

            if ($lockLimits) {
                $limitQuery->lockForUpdate();
            }

            $limit = $limitQuery->value('daily_limit');

            $confirmed = TicketBooking::where('temple_id', $templeId)
                ->whereDate('booking_date', $bookingDate)
                ->whereRaw(
                    'LOWER(TRIM(ticket_type)) = ?',
                    [$ticketType]
                )
                ->where('payment_status', 'paid')
                ->where('booking_status', 'confirmed')
                ->count();

            $otherCarts = TicketCart::where('temple_id', $templeId)
                ->whereDate('booking_date', $bookingDate)
                ->whereRaw(
                    'LOWER(TRIM(ticket_type)) = ?',
                    [$ticketType]
                )
                ->where('status', 'active')
                ->where('user_id', '!=', auth()->id())
                ->count();

            if (
                $limit === null ||
                ($confirmed + $otherCarts + $requested) > (int) $limit
            ) {
                $remaining = max(
                    0,
                    (int) ($limit ?? 0) - $confirmed - $otherCarts
                );

                throw new DomainException(
                    strtoupper($ticketType) .
                    ' tickets are no longer available for ' .
                    $bookingDate .
                    '. Remaining tickets: ' . $remaining .
                    '. If your payment was captured, contact support for refund assistance.'
                );
            }
        }
    }

    /**
     * Normalize a database/input time value to MySQL TIME format.
     */
    private function normalizeBookingTime($time): ?string
    {
        if (blank($time)) {
            return null;
        }

        try {
            return Carbon::parse($time)->format('H:i:s');
        } catch (Throwable $exception) {
            report($exception);

            return null;
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
            ->with(
                'error',
                'Payment was not completed. Your cart is still available.'
            );
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