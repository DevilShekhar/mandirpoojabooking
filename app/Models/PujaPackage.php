<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PujaPackage extends Model
{
    protected $fillable = [
        'puja_id',
        'name',
        'slug',
        'person_count',
        'pandit_price',
        'products',
        'price',
        'discount_price',
        'package_image',
        'short_description',
        'description',
        'is_featured',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'person_count' => 'integer',
        'pandit_price' => 'decimal:2',
        'products' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function puja()
    {
        return $this->belongsTo(Puja::class, 'puja_id');
    }

    public function features()
    {
        return $this->hasMany(PackageFeature::class, 'puja_package_id');
    }
}
