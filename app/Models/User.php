<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'provider',
        'profile_picture_url',
        'address',
        'contact_number',
        'email_verified_at',
        'is_admin',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->username)) {
                $username = Str::lower($user->first_name . $user->last_name);
                $username = preg_replace('/[^a-z0-9]/', '', $username);
                
                // Check if username exists and append number if needed
                $baseUsername = $username;
                $counter = 1;
                while (static::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                $user->username = $username;
            }

            // Set default profile picture if using social login and avatar is available
            if (!empty($user->avatar) && empty($user->profile_picture_url)) {
                $user->profile_picture_url = $user->avatar;
            }
        });
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get recent payment transactions for the user.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentPayments($limit = 5)
    {
        return $this->orders()
            ->select('order_id', 'created_at', 'grand_total', 'payment_status', 'payment_method')
            ->with('paymentMethod:id,last_four')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($transaction) {
                return (object) [
                    'created_at' => $transaction->created_at,
                    'amount' => $transaction->grand_total,
                    'status' => $transaction->payment_status,
                    'last_four' => $transaction->payment_method ? substr($transaction->payment_method, -4) : '****'
                ];
            });
    }

    /**
     * Get the addresses for the user.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the wishlist items for the user
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
