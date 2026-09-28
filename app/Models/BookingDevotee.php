<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDevotee extends Model
{
    protected $fillable = [
        'booking_id',
        'name',
        'relation',
        'gender',
        'date_of_birth',
        'gotra',
        'rashi',
        'nakshatra',
        'mobile',
        'address',
        'is_primary',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_primary' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(PujaBooking::class, 'booking_id');
    }
}
