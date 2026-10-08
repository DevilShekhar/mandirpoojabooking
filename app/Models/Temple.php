<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temple extends Model
{
    protected $fillable = [
        'state_id',
        'district_id',
        'city_id',
        'name',
        'slug',
        'temple_code',
        'address',
        'latitude',
        'longitude',
        'main_image',
        'banner_image',
        'is_verified',
        'status',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function templeDeities()
    {
        return $this->hasMany(TempleDeity::class);
    }

    public function deities()
    {
        return $this->belongsToMany(Deity::class,'temple_deities');
    }
    public function pujas()
{
    return $this->hasMany(Puja::class, 'temple_id');
}
}
