<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['shopper_id', 'name', 'description'];

    public function shopper(): BelongsTo
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist_products')
            ->withPivot('added_at')
            ->withTimestamps();
    }

    public function addProduct(Product $product): void
    {
        $this->products()->syncWithoutDetaching([
            $product->id => ['added_at' => now()]
        ]);
    }

    public function removeProduct(Product $product): void
    {
        $this->products()->detach($product->id);
    }

    public function hasProduct(Product $product): bool
    {
        return $this->products()->where('product_id', $product->id)->exists();
    }
}
