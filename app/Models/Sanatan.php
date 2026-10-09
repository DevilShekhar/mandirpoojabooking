<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sanatan extends Model
{
    protected $table = 'sanatans';

    protected $fillable = [
        'name',
        'slug',
        'program_type',
        'language',
        'short_description',
        'description',
        'image',
        'banner_image',
        'duration',
        'price',
        'is_online',
        'is_offline',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_online' => 'boolean',
            'is_offline' => 'boolean',
            'status' => 'boolean',
        ];
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