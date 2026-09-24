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
    ];
}
