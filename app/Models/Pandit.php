<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pandit extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'profile_image',
        'experience',
        'languages',
        'specialization',
        'temple_id',
        'verification_status',
        'status',
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function panditPujas()
    {
        return $this->hasMany(PanditPuja::class);
    }
    public function pujaAssignments()
    {
        return $this->hasMany(PujaAssignment::class);
    }
    public function bookingSankalps()
    {
        return $this->hasMany(BookingSankalp::class, 'pandit_id');
    }
}
