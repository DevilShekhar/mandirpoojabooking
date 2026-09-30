<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSankalp extends Model
{
    protected $fillable = [
        'booking_id',
        'sankalp_date',
        'sankalp_text',
        'pandit_id',
        'status',
        'completed_at',
        'video_url',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sankalp_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(PujaBooking::class, 'booking_id');
    }

    public function pandit()
    {
        return $this->belongsTo(Pandit::class, 'pandit_id');
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

