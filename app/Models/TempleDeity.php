<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempleDeity extends Model
{
    protected $fillable = [
        'temple_id',
        'deity_id',
        'is_primary',
        'display_order',
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
}
