<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaSchedule extends Model
{
    protected $fillable = [
        'temple_id',
        'puja_id',
        'puja_date',
        'start_time',
        'end_time',
        'capacity',
        'booked_capacity',
        'remaining_capacity',
        'booking_start_date',
        'booking_end_date',
        'status',
    ];

    protected $casts = [
        'puja_date' => 'date',
        'booking_start_date' => 'date',
        'booking_end_date' => 'date',
        'status' => 'boolean',
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function puja()
    {
        return $this->belongsTo(Puja::class);
    }
}