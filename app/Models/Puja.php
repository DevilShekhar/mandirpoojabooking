<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puja extends Model
{
    protected $fillable = [
        'temple_id',
        'deity_id',
        'puja_category_id',
        'name',
        'slug',
        'puja_code',
        'description',
        'benefits',
        'ritual_details',
        'samagri_details',
        'duration',
        'image',
        'banner_image',
        'is_online',
        'is_offline',
        'status',
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function deity()
    {
        return $this->belongsTo(Deity::class);
    }

    public function pujaCategory()
    {
        return $this->belongsTo(PujaCategory::class);
    }
    public function festivalPujas()
    {
        return $this->hasMany(FestivalPuja::class);
    }
     public function faqs()
    {
        return $this->hasMany(PujaFaq::class, 'puja_id')
            ->orderBy('sort_order');
    }
}
