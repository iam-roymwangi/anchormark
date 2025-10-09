<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shopper extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'county',
        'town',
        'closest_landmark'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'shopper_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'shopper_id');
    }

    public function activeCart(): HasMany
    {
        return $this->carts()->where('cart_status', 'active');
    }
}
