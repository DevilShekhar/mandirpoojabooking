<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaAssignment extends Model
{
    protected $fillable = [
        'booking_id',
        'pandit_id',
        'assigned_by',
        'assigned_at',
        'status',
        'started_at',
        'completed_at',
        'remarks',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function pandit()
    {
        return $this->belongsTo(Pandit::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
