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
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_primary' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(PujaBooking::class, 'booking_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
