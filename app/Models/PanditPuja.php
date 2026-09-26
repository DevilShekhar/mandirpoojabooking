<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanditPuja extends Model
{
    protected $fillable = [
        'pandit_id',
        'puja_id',
    ];

    public function pandit()
    {
        return $this->belongsTo(Pandit::class);
    }

    public function puja()
    {
        return $this->belongsTo(Puja::class);
    }
}
