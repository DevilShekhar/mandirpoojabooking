<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketBooking extends Model
{
    protected $fillable = [
        'booking_reference',
        'user_id',
        'temple_id',
        'booking_date',
        'booking_time',
        'member_name',
        'aadhaar_number',
        'aadhaar_photo',
        'ticket_type',
        'type',
        'age',
        'ticket_price_id',
        'price',
        'razorpay_order_id',
        'razorpay_payment_id',
        'payment_status',
        'booking_status',
        'ticket_otp',
        'ticket_otp_verified_by',
        'ticket_otp_verified_at',
        'not_reached_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'booking_time' => 'string',
            'price' => 'decimal:2',
            'ticket_otp_verified_at' => 'datetime',
            'not_reached_notified_at' => 'datetime',
        ];
    }

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ticketOtpVerifiedBy()
    {
        return $this->belongsTo(User::class, 'ticket_otp_verified_by');
    }
}