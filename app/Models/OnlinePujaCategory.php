<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnlinePujaCategory extends Model
{
    use HasFactory;

    protected $table = 'online_puja_categories';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'created_by',
        'updated_by',
    ];

    // Created By User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updated By User
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}