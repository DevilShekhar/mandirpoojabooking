<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'country_id',
        'state_id',
        'district_id',
        'name',
        'slug',
        'pincode',
        'latitude',
        'longitude',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
