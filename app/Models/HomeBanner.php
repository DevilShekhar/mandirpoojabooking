<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'image',
        'btn_name',
        'btn_url',
        'created_by',
        'updated_by',
    ];

    /**
     * Created By User
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated By User
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}