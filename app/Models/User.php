<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'gender',
        'role',
        'verified',
        'onboarding_complete',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean',
        'onboarding_complete' => 'boolean',
        'gender' => Gender::class,
        'role' => Role::class,
        'status' => UserStatus::class,
    ];

    /**
     * Automatically hash password when setting.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Accessor for full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the user's shopper profile.
     */
    public function shopper()
    {
        return $this->hasOne(Shopper::class);
    }

    /**
     * Get the user's wishlists through their shopper profile.
     */
    public function wishlists()
    {
        return $this->hasManyThrough(Wishlist::class, Shopper::class, 'user_id', 'shopper_id');
    }

    /**
     * Check if user is a shopper.
     */
    public function isShopper(): bool
    {
        return $this->shopper()->exists();
    }

    /**
     * Get the user's carts through their shopper profile.
     */
    public function carts()
    {
        return $this->hasManyThrough(Cart::class, Shopper::class, 'user_id', 'shopper_id');
    }

    /**
     * Get the user's active cart through their shopper profile.
     */
    public function activeCart()
    {
        return $this->carts()->where('cart_status', 'active')->first();
    }
}
