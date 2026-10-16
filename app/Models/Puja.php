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
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'is_offline' => 'boolean',
        'status' => 'boolean',
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

    public function packages()
    {
        return $this->hasMany(PujaPackage::class, 'puja_id')
            ->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function bookings()
    {
        return $this->hasMany(PujaBooking::class, 'puja_id');
    }
}