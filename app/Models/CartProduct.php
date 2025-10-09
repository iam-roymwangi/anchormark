<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartProduct extends Model
{
    protected $table = 'cart_products';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cartProduct) {
            $cartProduct->calculateSubtotal();
        });

        static::updating(function ($cartProduct) {
            if ($cartProduct->isDirty(['quantity', 'unit_price'])) {
                $cartProduct->calculateSubtotal();
            }
        });
    }

    // Relationships
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Business Logic Methods
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->unit_price * $this->quantity;
    }

    public function updateQuantity(int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->delete();
        }

        $this->quantity = $quantity;
        $this->calculateSubtotal();
        return $this->save();
    }

    public function incrementQuantity(int $amount = 1): bool
    {
        return $this->updateQuantity($this->quantity + $amount);
    }

    public function decrementQuantity(int $amount = 1): bool
    {
        return $this->updateQuantity($this->quantity - $amount);
    }

    public function updatePrice(float $newPrice): bool
    {
        $this->unit_price = $newPrice;
        $this->calculateSubtotal();
        return $this->save();
    }

    public function syncWithCurrentPrice(): bool
    {
        $currentPrice = $this->product->current_price ?? $this->product->price;
        
        if ($currentPrice != $this->unit_price) {
            return $this->updatePrice($currentPrice);
        }

        return true;
    }

    // Price Comparison Methods
    public function hasPriceChanged(): bool
    {
        $currentPrice = $this->product->current_price ?? $this->product->price;
        return $currentPrice != $this->unit_price;
    }

    public function getPriceDifference(): float
    {
        $currentPrice = $this->product->current_price ?? $this->product->price;
        return $currentPrice - $this->unit_price;
    }

    public function getPriceDifferencePercentage(): float
    {
        if ($this->unit_price == 0) {
            return 0;
        }

        $difference = $this->getPriceDifference();
        return ($difference / $this->unit_price) * 100;
    }

    public function getCurrentPrice(): float
    {
        return $this->product->current_price ?? $this->product->price;
    }

    public function getCurrentSubtotal(): float
    {
        return $this->getCurrentPrice() * $this->quantity;
    }

    // Validation Methods
    public function isQuantityValid(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->product->stock_quantity;
    }

    public function isProductAvailable(): bool
    {
        return $this->product->is_active && $this->product->stock_quantity > 0;
    }

    public function canIncreaseQuantity(int $amount = 1): bool
    {
        $newQuantity = $this->quantity + $amount;
        return $newQuantity <= $this->product->stock_quantity;
    }

    // Accessors
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 2);
    }

    public function getFormattedCurrentPriceAttribute(): string
    {
        return number_format($this->getCurrentPrice(), 2);
    }

    public function getFormattedCurrentSubtotalAttribute(): string
    {
        return number_format($this->getCurrentSubtotal(), 2);
    }

    public function getPriceChangeIndicatorAttribute(): string
    {
        if (!$this->hasPriceChanged()) {
            return 'unchanged';
        }

        $difference = $this->getPriceDifference();
        return $difference > 0 ? 'increased' : 'decreased';
    }

    public function getPriceChangeMessageAttribute(): string
    {
        if (!$this->hasPriceChanged()) {
            return '';
        }

        $difference = $this->getPriceDifference();
        $percentage = $this->getPriceDifferencePercentage();
        
        if ($difference > 0) {
            return "Price increased by {$this->formatted_current_price} (was {$this->formatted_unit_price})";
        } else {
            return "Price decreased by {$this->formatted_current_price} (was {$this->formatted_unit_price})";
        }
    }

    // Scopes
    public function scopeWithPriceChanges($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereRaw('(COALESCE(products.current_price, products.price) != cart_products.unit_price)');
        });
    }

    public function scopeWithoutPriceChanges($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->whereRaw('(COALESCE(products.current_price, products.price) = cart_products.unit_price)');
        });
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeWithAvailableProducts($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('status', 'active')
              ->where('stock_quantity', '>', 0);
        });
    }

    // Static Methods
    public static function createForProduct(Cart $cart, Product $product, int $quantity = 1): self
    {
        $unitPrice = $product->current_price ?? $product->price;
        
        return static::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $unitPrice * $quantity
        ]);
    }

    public static function findByCartAndProduct(int $cartId, int $productId): ?self
    {
        return static::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }
}
