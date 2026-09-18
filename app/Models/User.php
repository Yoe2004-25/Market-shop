<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password',
        'phone', 'image', 'address',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

   

    public function carts()         
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function wishlists()
    {
        return $this->hasMany(wishlist::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupons::class, 'coupon_user')  
            ->withPivot('time_of_coupon')
            ->withTimestamps();
    }

   

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
}