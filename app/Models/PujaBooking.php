<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaBooking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'temple_id',
        'deity_id',
        'puja_id',
        'puja_package_id',
        'schedule_date',
        'schedule_time',
        'pandit_id',
        'customer_name',
        'customer_email',
        'customer_mobile',
        'address_id',
        'package_name',
        'person_count',
        'base_amount',
        'discount_amount',
        'coupon_discount',
        'addon_amount',
        'shipping_amount',
        'tax_amount',
        'total_amount',
        'payment_status',
        'booking_status',
        'sankalp_status',
        'puja_status',
        'video_status',
        'prasad_status',
        'selected_products',
        'samgri_otp',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'string',
        'base_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'addon_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'selected_products' => 'array',
        'samgri_otp' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function deity()
    {
        return $this->belongsTo(Deity::class);
    }

    public function puja()
    {
        return $this->belongsTo(Puja::class);
    }

    public function pujaPackage()
    {
        return $this->belongsTo(PujaPackage::class);
    }

    public function pandit()
    {
        return $this->belongsTo(User::class, 'pandit_id');
    }

    public function devotees()
    {
        return $this->hasMany(BookingDevotee::class);
    }

    public function sankalp()
    {
        return $this->hasOne(BookingSankalp::class);
    }

    public function payments()
    {
        return $this->hasMany(PujaBookingPayment::class, 'puja_booking_id');
    }

    public function assignments()
    {
        return $this->hasMany(PujaAssignment::class);
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