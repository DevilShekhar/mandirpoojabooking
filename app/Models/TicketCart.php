<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCart extends Model
{
    protected $table = 'ticket_cart';

    protected $fillable = [
        'temple_id',
        'user_id',
        'booking_date',
        'member_name',
        'aadhaar_number',
        'aadhaar_photo',
        'ticket_type',
        'type',
        'age',
        'ticket_price_id',
        'price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'age' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function ticketPrice()
    {
        return $this->belongsTo(TicketPrice::class, 'ticket_price_id');
    }
}