<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePujaOrder extends Model
{
    use HasFactory;

    protected $table = 'online_puja_orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'online_puja_id',
        'online_puja_cat_id',
        'package_id',
        'package_name',
        'amount',
        'currency',
        'payment_status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'booking_status',
        'created_by',
    ];

    // Online Puja Relationship
    public function puja()
    {
        return $this->belongsTo(
            OnlinePuja::class,
            'online_puja_id',
            'id'
        );
    }

    // Package Relationship
    public function package()
    {
        return $this->belongsTo(
            OnlinePujaPackage::class,
            'package_id',
            'id'
        );
    }

    // Customer Relationship
    public function customer()
    {
        return $this->belongsTo(
            User::class,
            'customer_id',
            'id'
        );
    }

    // Created By Relationship
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }
}