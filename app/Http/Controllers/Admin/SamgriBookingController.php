<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PujaBooking;
use App\Models\SamgriBookingVerification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\PujaCompletedOtpMail;

class SamgriBookingController extends Controller
{
    private function applyTempleAccess(Builder $query): Builder
    {
        $user = Auth::user();
        if ($user->hasRole(['Admin', 'Super Admin'])) {
            return $query;
        }
        if ($user->hasRole('Store Manager')) {
            if (!$user->temple_id) {
                abort(403);
            }
            return $query->where('temple_id', $user->temple_id);
        }
        abort(403);
    }
    public function index()
    {
        $query = PujaBooking::with(['user','temple','deity','puja','pujaPackage',])
            ->whereNotNull('selected_products')
            ->whereJsonLength('selected_products', '>', 0)
            ->where('samgri_otp_verified', 0)
            ->whereDate('schedule_date', '>=', today());
        $samgriBookings = $this->applyTempleAccess($query)
            ->orderBy('schedule_date', 'asc')
            ->orderBy('schedule_time', 'asc')
            ->get();
        return view('admin.samgri-booking.index', compact('samgriBookings'));
    }
    public function show($id)
    {
        $query = PujaBooking::with([
            'user','temple','deity','puja','pujaPackage',
            'payments' => function ($query) {
                $query->latest();
            },
            'samgriVerification.verifiedBy',
        ])->whereNotNull('selected_products')
            ->whereJsonLength('selected_products', '>', 0)
            ->where('samgri_otp_verified', 0);
        $booking = $this->applyTempleAccess($query)->findOrFail($id);
        $products = $booking->selected_products ?? [];
        if (is_string($products)) {
            $products = json_decode($products, true) ?? [];
        }
        $products = is_array($products) ? $products : [];
        return view('admin.samgri-booking.show',compact('booking', 'products'));
    }
    
    
    public function verifyOtp(Request $request, $id)
    {
        $request->validate([
            'otp' => ['required', 'string', 'max:10'],
        ]);

        $result = DB::transaction(function () use ($request, $id) {

            // Load booking relationships
            $query = PujaBooking::with([
                'user',
                'temple',
                'puja',
            ]);

            $booking = $this->applyTempleAccess($query)
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Already verified check
            if ($booking->samgri_otp_verified) {
                throw ValidationException::withMessages([
                    'otp' => 'This Samagri booking has already been verified.',
                ]);
            }

            // Get verification record
            $verification = SamgriBookingVerification::where(
                'puja_booking_id',
                $booking->id
            )->lockForUpdate()->first();

            if ($verification && $verification->is_verified) {
                throw ValidationException::withMessages([
                    'otp' => 'This Samagri booking has already been verified.',
                ]);
            }

            // Verify existing Samagri OTP
            $storedOtp = $verification->otp ?? $booking->samgri_otp;

            if (
                empty($storedOtp) ||
                !hash_equals((string) $storedOtp, (string) $request->otp)
            ) {
                throw ValidationException::withMessages([
                    'otp' => 'Invalid OTP. Please enter the correct OTP.',
                ]);
            }

            $user = Auth::user();
            $verifiedAt = now();

            // Customer email
            $customerEmail = $booking->customer_email
                ?: ($booking->user?->email);

            if (empty($customerEmail)) {
                throw ValidationException::withMessages([
                    'otp' => 'Customer email address is not available.',
                ]);
            }

            // Generate new 6-digit Puja Completion OTP
            $completionOtp = (string) random_int(100000, 999999);

            // Create verification record if not exists
            if (!$verification) {
                $verification = new SamgriBookingVerification();

                $verification->puja_booking_id = $booking->id;
                $verification->created_by = $user->id;
            }

            // Save Samagri verification details
            $verification->temple_id = $booking->temple_id;
            $verification->puja_id = $booking->puja_id;
            $verification->user_id = $booking->user_id;

            $verification->customer_name = $booking->customer_name
                ?? $booking->user?->name
                ?? null;

            $verification->customer_mobile = $booking->customer_mobile
                ?? $booking->user?->phone
                ?? null;

            $verification->otp = $storedOtp;
            $verification->is_verified = 1;
            $verification->verified_by = $user->id;
            $verification->verified_at = $verifiedAt;
            $verification->updated_by = $user->id;

            $verification->save();

            // Update Puja Booking
            $booking->samgri_otp_verified = 1;
            $booking->samgri_otp_verified_at = $verifiedAt;

            // Generate Puja Completion OTP
            $booking->puja_completed_otp = $completionOtp;
            $booking->puja_completed_otp_at = $verifiedAt;

            // Authenticated officer ID
            $booking->puja_completed_otp_verify_by = $user->id;

            $booking->updated_by = $user->id;
            $booking->save();

            // -----------------------------------
            // Temple and Puja Details
            // -----------------------------------

            $templeName = $booking->temple?->name ?? 'N/A';

            // Your pujas table uses the name column
            $pujaName = $booking->puja?->name ?? 'N/A';

            // -----------------------------------
            // Selected Samagri Products
            // -----------------------------------

            $selectedProducts = $booking->selected_products ?? [];

            if (!is_array($selectedProducts)) {
                $selectedProducts = [];
            }

            $products = collect($selectedProducts)->map(function ($product) {

                if (!is_array($product)) {
                    return null;
                }

                $quantity = (float) (
                    $product['quantity'] ?? 1
                );

                $price = (float) (
                    $product['price']
                    ?? $product['unit_price']
                    ?? 0
                );

                $amount = isset($product['amount'])
                    ? (float) $product['amount']
                    : $price * $quantity;

                return [
                    'name' => $product['name']
                        ?? $product['product_name']
                        ?? 'Samagri Product',

                    'price' => $price,

                    'quantity' => $quantity,

                    'unit' => $product['unit'] ?? '',

                    'amount' => $amount,
                ];

            })->filter()->values()->toArray();

            $productsTotal = collect($products)->sum('amount');

            // -----------------------------------
            // Email Details
            // -----------------------------------

            return [
                'email' => $customerEmail,

                'details' => [
                    'booking_id' => $booking->booking_number
                        ?? $booking->id,

                    'customer_name' => $booking->customer_name
                        ?? $booking->user?->name
                        ?? 'Customer',

                    'customer_mobile' => $booking->customer_mobile
                        ?? $booking->user?->phone
                        ?? 'N/A',

                    'puja_name' => $pujaName,

                    'temple_name' => $templeName,

                    'otp' => $completionOtp,

                    'generated_at' => $verifiedAt->format('d-m-Y h:i A'),

                    'products' => $products,

                    'products_total' => $productsTotal,
                ],
            ];
        });

        // -----------------------------------
        // Send Customer Email After DB Commit
        // -----------------------------------

        Mail::to($result['email'])->send(
            new PujaCompletedOtpMail($result['details'])
        );

        return redirect()
            ->route('completed-samgri-booking.show', $id)
            ->with(
                'success',
                'Samagri OTP verified successfully. Puja completion OTP generated and sent to customer email.'
            );
    }

    public function completed()
    {
        $query = PujaBooking::with(['user','temple','deity','puja','pujaPackage','samgriVerification.verifiedBy',])
            ->whereNotNull('selected_products')
            ->whereJsonLength('selected_products', '>', 0)
            ->where('samgri_otp_verified', 1);
        $samgriBookings = $this->applyTempleAccess($query)->latest()->get();
        return view('admin.samgri-booking.completed',compact('samgriBookings'));
    }

    public function completedShow($id)
    {
        $query = PujaBooking::with([
            'user','temple','deity','puja','pujaPackage',
            'payments' => function ($query) {
                $query->latest();
            },
            'samgriVerification.verifiedBy',
        ])
            ->whereNotNull('selected_products')
            ->whereJsonLength('selected_products', '>', 0)
            ->where('samgri_otp_verified', 1);
        $booking = $this->applyTempleAccess($query)->findOrFail($id);
        $products = $booking->selected_products ?? [];
        if (is_string($products)) {
            $products = json_decode($products, true) ?? [];
        }
        $products = is_array($products) ? $products : [];
        return view('admin.samgri-booking.show', compact('booking', 'products'));
    }
}