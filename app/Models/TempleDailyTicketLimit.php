<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleDailyTicketLimit extends Model
{
    protected $table = 'temple_daily_ticket_limits';

    protected $fillable = [
        'temple_id',
        'ticket_type',
        'daily_limit',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'daily_limit' => 'integer',
        ];
    }

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}