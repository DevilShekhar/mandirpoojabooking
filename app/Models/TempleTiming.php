<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempleTiming extends Model
{
    protected $table = 'temple_timings';

    protected $fillable = [
        'name',
        'temple_id',
        'temple_timing_category_id',
        'description',
        'start_day',
        'end_day',
        'opening_time',
        'closing_time',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            TempleTimingCategory::class,
            'temple_timing_category_id'
        );
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}