<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempleTimingCategory extends Model
{
    protected $table = 'temple_timing_categories';

    protected $fillable = [
        'temple_id',
        'name',
        'slug',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'temple_id' => 'integer',
        'status' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * Category belongs to a temple.
     */
    public function temple(): BelongsTo
    {
        return $this->belongsTo(Temple::class, 'temple_id');
    }

    /**
     * Category has many timings.
     */
    public function timings(): HasMany
    {
        return $this->hasMany(
            TempleTiming::class,
            'temple_timing_category_id'
        );
    }

    /**
     * User who created the category.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the category.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}