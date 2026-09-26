<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'festival_date',
        'start_date',
        'end_date',
        'image',
        'banner_image',
        'status',
    ];

    public function festivalPujas()
    {
        return $this->hasMany(FestivalPuja::class);
    }
}
