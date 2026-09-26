<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FestivalPuja extends Model
{
    protected $fillable = [
        'festival_id',
        'puja_id',
        'special_price',
        'sort_order',
        'status',
    ];
    public function festival()
    {
        return $this->belongsTo(Festival::class);
    }

    public function puja()
    {
        return $this->belongsTo(Puja::class);
    }
}
