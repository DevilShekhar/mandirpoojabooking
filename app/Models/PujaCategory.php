<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
