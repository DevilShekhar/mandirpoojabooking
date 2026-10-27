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
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'price' => 'decimal:2',
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
}
