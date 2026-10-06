<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Puja;
use App\Models\User;

class PujaReview extends Model
{
    protected $table = 'puja_reviews';

    protected $fillable = [
        'puja_id',
        'review',
        'rating',
        'created_by',
        'updated_by',
        'status',
        'approved_by',
    ];

    public function puja()
    {
        return $this->belongsTo(Puja::class, 'puja_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}