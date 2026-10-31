<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamgriBookingVerification extends Model
{
    protected $table = 'samgri_booking_verifications';

    protected $fillable = [
        'puja_booking_id',
        'temple_id',
        'puja_id',
        'user_id',
        'customer_name',
        'customer_mobile',
        'otp',
        'is_verified',
        'verified_by',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(PujaBooking::class, 'puja_booking_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
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