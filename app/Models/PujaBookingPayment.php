<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PujaBookingPayment extends Model
{
    use HasFactory;

    protected $table = 'puja_booking_payments';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'puja_id',
        'puja_package_id',
        'puja_booking_id',

        // Payment amount
        'amount_paise',
        'currency',

        // Razorpay payment details
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',

        // Payment status: pending, paid, failed
        'status',

        // Razorpay failure details
        'error_code',
        'error_description',
        'error_source',
        'error_step',
        'error_reason',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'puja_id' => 'integer',
            'puja_package_id' => 'integer',
            'puja_booking_id' => 'integer',
            'amount_paise' => 'integer',
        ];
    }

    /**
     * Payment belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Payment belongs to a puja.
     */
    public function puja(): BelongsTo
    {
        return $this->belongsTo(Puja::class);
    }

    /**
     * Payment belongs to a puja package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(
            PujaPackage::class,
            'puja_package_id'
        );
    }

    /**
     * Payment belongs to a puja booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            PujaBooking::class,
            'puja_booking_id'
        );
    }
}