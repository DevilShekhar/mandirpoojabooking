<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TempleDailyTicketLimit;
use App\Models\TicketBooking;
use App\Models\TicketCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use DomainException;
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

    /**
     * Create Razorpay order after checking ticket availability.
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

        try {
            // Check only paid and confirmed records in ticket_bookings.
            $this->checkCartAvailability($cartItems);

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
     * Verify captured payment and create confirmed bookings.
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        if (
            session('ticket_payment_order_id') !==
                $validated['razorpay_order_id'] ||
            (int) session('ticket_payment_user_id') !==
                (int) auth()->id()
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

            $result = DB::transaction(function () use (
                $validated,
                $payment
            ) {
                /*
                 * Prevent duplicate booking creation if Razorpay
                 * verification is submitted more than once.
                 */
                $existingBookings = TicketBooking::where(
                    'razorpay_order_id',
                    $validated['razorpay_order_id']
                )->lockForUpdate()->get();

                if ($existingBookings->isNotEmpty()) {
                    $samePayment = $existingBookings->every(
                        fn ($booking) =>
                            $booking->razorpay_payment_id ===
                            $validated['razorpay_payment_id']
                    );

                    if (!$samePayment) {
                        throw new DomainException(
                            'This order is associated with a different payment.'
                        );
                    }

                    return ['already_booked' => true];
                }

                /*
                 * Cart rows are used to identify the purchaser's
                 * members and calculate the payment amount only.
                 */
                $cartItems = TicketCart::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->orderBy('temple_id')
                    ->orderBy('booking_date')
                    ->lockForUpdate()
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new DomainException(
                        'No active ticket cart items were found. ' .
                        'If payment was captured, contact support.'
                    );
                }

                $totalPaise = (int) round(
                    $cartItems->sum(
                        fn ($item) => (float) $item->price
                    ) * 100
                );

                if ((int) $payment['amount'] !== $totalPaise) {
                    throw new DomainException(
                        'Payment amount does not match your cart total. ' .
                        'Contact support if payment was captured.'
                    );
                }

                /*
                 * Recheck availability immediately before booking.
                 * This reads ticket_bookings, never other carts.
                 */
                $this->checkCartAvailability($cartItems, true);

                foreach ($cartItems as $item) {
                    TicketBooking::create([
                        'booking_reference' =>
                            'TKT-' . strtoupper(Str::random(12)),

                        'user_id' => auth()->id(),
                        'temple_id' => $item->temple_id,
                        'booking_date' => $item->booking_date,
                        'member_name' => $item->member_name,
                        'aadhaar_number' => $item->aadhaar_number,
                        'aadhaar_photo' => $item->aadhaar_photo,
                        'ticket_type' => strtolower(trim($item->ticket_type)),
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

                // Remove cart rows only after bookings are created.
                TicketCart::whereIn(
                    'id',
                    $cartItems->pluck('id')
                )->delete();

                return ['already_booked' => false];
            }, 3);

            session()->forget([
                'ticket_payment_order_id',
                'ticket_payment_user_id',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Your tickets are booked.',
                'redirect_url' => route(
                    'frontend.ticket-booking.success'
                ),
            ]);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'payment_captured' => true,
                'refund_required' => true,
            ], 409);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Payment verification or booking failed. ' .
                    'If payment was captured, contact support before retrying.',
                'payment_captured' => true,
            ], 500);
        }
    }

    /**
     * Validate capacity using paid and confirmed ticket bookings only.
     *
     * When $lockLimits is true, lock the daily limit rows inside
     * the current database transaction.
     */
    private function checkCartAvailability(
        $cartItems,
        bool $lockLimits = false
    ): void {
        $groups = $cartItems->groupBy(function ($item) {
            return (int) $item->temple_id . '|' .
                \Carbon\Carbon::parse($item->booking_date)->toDateString() . '|' .
                strtolower(trim($item->ticket_type));
        });

        foreach ($groups as $group) {
            $item = $group->first();

            $templeId = (int) $item->temple_id;

            $bookingDate = \Carbon\Carbon::parse(
                $item->booking_date
            )->toDateString();

            $ticketType = strtolower(trim($item->ticket_type));
            $requested = $group->count();

            if (!in_array($ticketType, ['normal', 'vip'], true)) {
                throw new DomainException(
                    'Invalid ticket type in your cart.'
                );
            }

            $limitQuery = TempleDailyTicketLimit::query()
                ->where('temple_id', $templeId)
                ->whereRaw(
                    'LOWER(TRIM(ticket_type)) = ?',
                    [$ticketType]
                );

            if ($lockLimits) {
                $limitQuery->lockForUpdate();
            }

            $limit = $limitQuery->value('daily_limit');

            /*
             * IMPORTANT:
             * Do not count TicketCart records here.
             * Only paid + confirmed TicketBooking records consume capacity.
             */
            $confirmed = TicketBooking::query()
                ->where('temple_id', $templeId)
                ->whereDate('booking_date', $bookingDate)
                ->whereRaw(
                    'LOWER(TRIM(ticket_type)) = ?',
                    [$ticketType]
                )
                ->where('payment_status', 'paid')
                ->where('booking_status', 'confirmed')
                ->count();

            if ($limit === null) {
                throw new DomainException(
                    'The daily limit is not configured for ' .
                    strtoupper($ticketType) . ' tickets.'
                );
            }

            $remaining = max(0, (int) $limit - $confirmed);

            if (($confirmed + $requested) > (int) $limit) {
                throw new DomainException(
                    strtoupper($ticketType) .
                    ' tickets are no longer available for ' .
                    $bookingDate .
                    '. Daily limit: ' . (int) $limit .
                    ', confirmed bookings: ' . $confirmed .
                    ', remaining tickets: ' . $remaining . '.'
                );
            }
        }
    }

    /**
     * Handle failed/cancelled checkout navigation.
     */
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

    /**
     * Show the current user's confirmed bookings.
     */
    public function success()
    {
        $bookings = TicketBooking::with('temple')
            ->where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->latest()
            ->get();

        return view(
            'frontend.ticket-booking.success',
            compact('bookings')
        );
    }
}