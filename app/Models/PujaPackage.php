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
        'price',
        'discount_price',
        'package_image',
        'short_description',
        'description',
        'includes_sankalp',
        'includes_prasad',
        'includes_video',
        'includes_certificate',
        'is_featured',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',

        'includes_sankalp' => 'boolean',
        'includes_prasad' => 'boolean',
        'includes_video' => 'boolean',
        'includes_certificate' => 'boolean',

        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Puja
     */
    public function puja()
    {
        return $this->belongsTo(Puja::class, 'puja_id');
    }

    /**
     * Package Features
     */
    public function features()
    {
        return $this->hasMany(PackageFeature::class, 'puja_package_id');
    }
}