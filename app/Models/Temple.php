<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Temple extends Model
{
    protected $fillable = [
        'country_id',
        'state_id',
        'district_id',
        'city_id',
        'name',
        'slug',
        'temple_code',
        'address',
        'latitude',
        'longitude',
        'main_image',
        'banner_image',
        'is_verified',
        'status',
        'created_by',
        'updated_by',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function templeDeities()
    {
        return $this->hasMany(TempleDeity::class);
    }

    public function deities()
    {
        return $this->belongsToMany(
            Deity::class,
            'temple_deities'
        );
    }

    public function pujas()
    {
        return $this->hasMany(Puja::class, 'temple_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'temple_user')
            ->withPivot('approval_status')
            ->withTimestamps();
    }
    public function pandits()
    {
        return $this->belongsToMany(User::class, 'temple_user')
            ->withPivot('approval_status')
            ->withTimestamps()
            ->wherePivot('approval_status', 'approved')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Pandit')
                    ->where('guard_name', 'web');
            });
    }
}