<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePujaFaq extends Model
{
    use HasFactory;

    protected $table = 'online_puja_faqs';

    protected $fillable = [
        'online_puja_id',
        'question',
        'answer',
        'created_by',
        'updated_by',
    ];

    public function onlinePuja()
    {
        return $this->belongsTo(OnlinePuja::class, 'online_puja_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}