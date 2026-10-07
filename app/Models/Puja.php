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

    protected $casts = [
        'is_online' => 'boolean',
        'is_offline' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Temple
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    /**
     * Deity
     */
    public function deity()
    {
        return $this->belongsTo(Deity::class);
    }

    /**
     * Puja Category
     */
    public function pujaCategory()
    {
        return $this->belongsTo(PujaCategory::class);
    }

    /**
     * Festival Pujas
     */
    public function festivalPujas()
    {
        return $this->hasMany(FestivalPuja::class);
    }

    /**
     * FAQs
     */
    public function faqs()
    {
        return $this->hasMany(PujaFaq::class, 'puja_id')
            ->orderBy('sort_order');
    }

    /**
     * Packages
     */
    public function packages()
    {
        return $this->hasMany(PujaPackage::class, 'puja_id')
            ->orderBy('sort_order');
    }
}