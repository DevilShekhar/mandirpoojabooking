<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageAddon extends Model
{
    protected $fillable = [
        'puja_package_id',
        'name',
        'description',
        'price',
        'image',
        'max_quantity',
        'status',
    ];

    public function pujaPackage()
    {
        return $this->belongsTo(PujaPackage::class);
    }
}
