<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopper_id',
        'session_id',
        'cart_status',
        'total_items',
        'total_amount',
        'last_activity_at'
    ];

    protected $casts = [
        'cart_status' => CartStatus::class,
        'total_amount' => 'decimal:2',
        'last_activity_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            if (!$cart->session_id && !$cart->shopper_id) {
                $cart->session_id = Str::uuid();
            }
            $cart->last_activity_at = now();
        });

        static::updating(function ($cart) {
            $cart->last_activity_at = now();
        });
    }

    // Relationships
    public function shopper(): BelongsTo
    {
        return $this->belongsTo(Shopper::class, 'shopper_id');
    }

    public function cartProducts(): HasMany
    {
        return $this->hasMany(CartProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_products')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    // Cart Management Methods
    public static function findOrCreateForGuest(?string $sessionId = null): self
    {
        if (!$sessionId) {
            $sessionId = session()->getId() ?: Str::uuid();
        }

        return static::firstOrCreate(
            [
                'session_id' => $sessionId,
                'cart_status' => CartStatus::Active
            ],
            [
                'total_items' => 0,
                'total_amount' => 0,
                'last_activity_at' => now()
            ]
        );
    }

    public static function findOrCreateForShopper(int $shopperId): self
    {
        return static::firstOrCreate(
            [
                'shopper_id' => $shopperId,
                'cart_status' => CartStatus::Active
            ],
            [
                'total_items' => 0,
                'total_amount' => 0,
                'last_activity_at' => now()
            ]
        );
    }

    public function addProduct(Product $product, int $quantity = 1, ?string $size = null, ?string $color = null): CartProduct
    {
        $unitPrice = $product->current_price ?? $product->price;
        
        // For now, size and color are not stored in the database for logged-in users
        // due to schema limitations. They are handled in session for guest users.
        // A migration would be needed to add 'size' and 'color' columns to 'cart_products' table.

        $cartProduct = $this->cartProducts()
            ->where('product_id', $product->id)
            // If size and color were to be stored, the query would need to include them:
            // ->where('size', $size)
            // ->where('color', $color)
            ->first();

        if ($cartProduct) {
            // Update existing item
            $newQuantity = $cartProduct->quantity + $quantity;
            $cartProduct->update([
                'quantity' => $newQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $newQuantity,
                // If size and color were stored, they would be updated here:
                // 'size' => $size,
                // 'color' => $color,
            ]);
        } else {
            // Add new item
            $cartProduct = $this->cartProducts()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity,
                // If size and color were stored, they would be added here:
                // 'size' => $size,
                // 'color' => $color,
            ]);
        }

        $this->recalculateTotals();
        return $cartProduct;
    }

    public function updateProductQuantity(Product $product, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeProduct($product);
        }

        $cartProduct = $this->cartProducts()
            ->where('product_id', $product->id)
            ->first();

        if (!$cartProduct) {
            return false;
        }

        $unitPrice = $product->current_price ?? $product->price;
        
        $cartProduct->update([
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $quantity
        ]);

        $this->recalculateTotals();
        return true;
    }

    public function removeProduct(Product $product): bool
    {
        $deleted = $this->cartProducts()
            ->where('product_id', $product->id)
            ->delete();

        if ($deleted) {
            $this->recalculateTotals();
        }

        return $deleted > 0;
    }

    public function clear(): void
    {
        $this->cartProducts()->delete();
        $this->update([
            'total_items' => 0,
            'total_amount' => 0
        ]);
    }

    public function recalculateTotals(): void
    {
        $totals = $this->cartProducts()
            ->selectRaw('SUM(quantity) as total_items, SUM(subtotal) as total_amount')
            ->first();

        $this->update([
            'total_items' => $totals->total_items ?? 0,
            'total_amount' => $totals->total_amount ?? 0
        ]);
    }

    // Status Management
    public function markAsOrdered(): void
    {
        $this->update(['cart_status' => CartStatus::Ordered]);
    }

    public function markAsAbandoned(): void
    {
        $this->update(['cart_status' => CartStatus::Abandoned]);
    }

    public function isActive(): bool
    {
        return $this->cart_status === CartStatus::Active;
    }

    public function isEmpty(): bool
    {
        return $this->total_items <= 0;
    }

    // Cart Merging for Login
    public function mergeWith(Cart $otherCart): void
    {
        if ($otherCart->id === $this->id) {
            return; // Can't merge with itself
        }

        DB::transaction(function () use ($otherCart) {
            foreach ($otherCart->cartProducts as $cartProduct) {
                $existingProduct = $this->cartProducts()
                    ->where('product_id', $cartProduct->product_id)
                    ->first();

                if ($existingProduct) {
                    // Merge quantities
                    $newQuantity = $existingProduct->quantity + $cartProduct->quantity;
                    $existingProduct->update([
                        'quantity' => $newQuantity,
                        'subtotal' => $existingProduct->unit_price * $newQuantity
                    ]);
                } else {
                    // Add new product
                    $this->cartProducts()->create([
                        'product_id' => $cartProduct->product_id,
                        'quantity' => $cartProduct->quantity,
                        'unit_price' => $cartProduct->unit_price,
                        'subtotal' => $cartProduct->subtotal
                    ]);
                }
            }

            // Mark the other cart as abandoned
            $otherCart->markAsAbandoned();
            
            // Recalculate totals
            $this->recalculateTotals();
        });
    }

    // Price Change Detection
    public function checkForPriceChanges(): array
    {
        $priceChanges = [];
        
        foreach ($this->cartProducts as $cartProduct) {
            $currentPrice = $cartProduct->product->current_price ?? $cartProduct->product->price;
            
            if ($currentPrice != $cartProduct->unit_price) {
                $priceChanges[] = [
                    'product' => $cartProduct->product,
                    'old_price' => $cartProduct->unit_price,
                    'new_price' => $currentPrice,
                    'difference' => $currentPrice - $cartProduct->unit_price,
                    'cart_product' => $cartProduct
                ];
            }
        }

        return $priceChanges;
    }

    public function updatePricesToCurrent(): void
    {
        foreach ($this->cartProducts as $cartProduct) {
            $currentPrice = $cartProduct->product->current_price ?? $cartProduct->product->price;
            
            if ($currentPrice != $cartProduct->unit_price) {
                $cartProduct->update([
                    'unit_price' => $currentPrice,
                    'subtotal' => $currentPrice * $cartProduct->quantity
                ]);
            }
        }
        
        $this->recalculateTotals();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('cart_status', CartStatus::Active);
    }

    public function scopeForGuest($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForShopper($query, int $shopperId)
    {
        return $query->where('shopper_id', $shopperId);
    }

    public function scopeAbandoned($query)
    {
        return $query->where('cart_status', CartStatus::Abandoned);
    }

    // Accessors
    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2);
    }

    public function getItemCountAttribute(): int
    {
        return $this->total_items;
    }

    public function getIsGuestCartAttribute(): bool
    {
        return !is_null($this->session_id) && is_null($this->shopper_id);
    }

    public function getIsShopperCartAttribute(): bool
    {
        return !is_null($this->shopper_id);
    }
}
