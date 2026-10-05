<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaFaq extends Model
{
    protected $table = 'puja_faqs';

    protected $fillable = [
        'puja_id',
        'question',
        'answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function puja()
    {
        return $this->belongsTo(Puja::class, 'puja_id');
    }
}