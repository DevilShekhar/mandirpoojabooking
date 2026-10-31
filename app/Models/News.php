<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'temple_id',
        'slug',
        'title',
        'short_description',
        'description',
        'image',
        'artical_img',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class, 'temple_id');
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