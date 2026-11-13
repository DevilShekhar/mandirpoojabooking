<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlinePuja extends Model
{
    protected $table = 'online_pujas';

    protected $fillable = [
        'online_puja_category_id',
        'title',
        'slug',
        'status',
        'short_description',

        'banner_image',
        'thumbnail_image',
        'gallery_images',

        'puja_significance',
        'puja_benefits',
        'puja_procedure',
        'what_you_will_receive',

        'temple_name',
        'temple_location',
        'temple_city',
        'temple_state',
        'temple_address',
        'temple_description',

        'puja_datetime',
        'puja_day',
        'tithi',
        'muhurat',
        'muhurat_end_at',
        'booking_start_at',
        'booking_end_at',

        'total_seat',
        'booked_seat',

        'video_delivery_hours',
        'whatsapp_delivery_enabled',
        'video_url',

        'is_featured',
        'is_popular',
        'sort_order',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'whatsapp_delivery_enabled' => 'boolean',

        'puja_datetime' => 'datetime',
        'muhurat_end_at' => 'datetime',
        'booking_start_at' => 'datetime',
        'booking_end_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(
            OnlinePujaCategory::class,
            'online_puja_category_id'
        );
    }

    public function faqs()
    {
        return $this->hasMany(
            OnlinePujaFaq::class,
            'online_puja_id'
        );
    }

    public function packages()
    {
        return $this->hasMany(
            OnlinePujaPackage::class,
            'online_puja_id'
        );
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