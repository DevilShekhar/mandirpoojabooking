<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PujaBookingPayment extends Model
{
    use HasFactory;

    protected $table = 'puja_booking_payments';

    protected $fillable = [
        'user_id',
        'puja_id',
        'puja_package_id',
        'puja_booking_id',
        'amount_paise',
        'currency',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status',
    ];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function puja(): BelongsTo
    {
        return $this->belongsTo(Puja::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(
            PujaPackage::class,
            'puja_package_id'
        );
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            PujaBooking::class,
            'puja_booking_id'
        );
    }
}
