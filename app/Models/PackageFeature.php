<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $fillable = [
        'puja_package_id',
        'feature_name',
        'feature_value',
        'sort_order',
        'status',
    ];

    public function pujaPackage()
    {
        return $this->belongsTo(PujaPackage::class);
    }
}
