<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deity extends Model
{
    protected $fillable = [
        'name','slug','description','image','status'
    ];
    public function templeDeities()
    {
        return $this->hasMany(TempleDeity::class);
    }

    public function temples()
    {
        return $this->belongsToMany(Temple::class,'temple_deities');
    }
}
