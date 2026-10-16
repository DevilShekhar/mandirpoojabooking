<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerBookingCancellation extends Model
{
    protected $table = 'customer_booking_cancellations';

    protected $fillable = [
        'puja_booking_id',
        'customer_id',
        'cancellation_reason',
        'status',
        'reviewed_by',
        'review_notes',
        'refund_amount',
        'refund_status',
        'razorpay_refund_id',
        'refund_error',
        'reviewed_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'refund_amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(PujaBooking::class, 'puja_booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}