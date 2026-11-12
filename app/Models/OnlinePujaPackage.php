<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePujaPackage extends Model
{
    use HasFactory;

    protected $table = 'online_puja_packages';

    protected $fillable = [
        'online_puja_id',
        'package_name',
        'devotees_count',
        'price',
        'description',
        'is_recommended',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'devotees_count' => 'integer',
        'price' => 'decimal:2',
        'is_recommended' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
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