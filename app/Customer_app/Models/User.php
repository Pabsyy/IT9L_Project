<?php

namespace App\Customer_app\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'UserID';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_picture_url',
        'role',
        'address',
        'phone',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'UserID');
    }
} 