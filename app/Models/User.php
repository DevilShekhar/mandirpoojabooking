<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'birth_date',
        'profile',
        'status',
        'password',
        'role_id',
        'google_id',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(
            \Spatie\Permission\Models\Role::class,
            'role_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            self::class,
            'created_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            self::class,
            'updated_by'
        );
    }
}