<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\Shopper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Get the current user's cart (guest or authenticated)
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getCurrentCart($request);
        
        $cart->load(['cartProducts.product', 'cartProducts.product.images']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'items' => $cart->cartProducts,
                'totals' => [
                    'items' => $cart->total_items,
                    'amount' => $cart->total_amount,
                    'formatted_amount' => $cart->formatted_total_amount
                ],
                'price_changes' => $cart->checkForPriceChanges()
            ]
        ]);
    }

    /**
     * Get cart item count
     */
    public function count(Request $request): JsonResponse
    {
        $cart = $this->getCurrentCart($request);
        
        return response()->json([
            'success' => true,
            'data' => [
                'count' => $cart->total_items,
                'formatted_amount' => $cart->formatted_total_amount
            ]
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = $validated['quantity'] ?? 1;

        // Check if product is available
        if (!$product->is_active || $product->stock_quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 422);
        }

        // Check stock availability
        $cart = $this->getCurrentCart($request);
        $existingItem = $cart->cartProducts()
            ->where('product_id', $product->id)
            ->first();

        $requestedQuantity = $existingItem ? 
            $existingItem->quantity + $quantity : $quantity;

        if ($requestedQuantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock_quantity} items available in stock"
            ], 422);
        }

        try {
            $cartProduct = $cart->addProduct($product, $quantity);
            
            // Update session for guest carts
            if ($cart->is_guest_cart) {
                Session::put('cart_session_id', $cart->session_id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'data' => [
                    'cart_item' => $cartProduct->load('product'),
                    'cart_totals' => [
                        'items' => $cart->fresh()->total_items,
                        'amount' => $cart->fresh()->total_amount,
                        'formatted_amount' => $cart->fresh()->formatted_total_amount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart'
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_product_id' => 'required|exists:cart_products,id',
            'quantity' => 'required|integer|min:0|max:99'
        ]);

        $cartProduct = CartProduct::findOrFail($validated['cart_product_id']);
        $cart = $cartProduct->cart;

        // Verify cart ownership
        if (!$this->canAccessCart($request, $cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to cart'
            ], 403);
        }

        $quantity = $validated['quantity'];

        // Check stock availability if increasing quantity
        if ($quantity > $cartProduct->quantity) {
            $additionalQuantity = $quantity - $cartProduct->quantity;
            if ($cartProduct->product->stock_quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$cartProduct->product->stock_quantity} items available in stock"
                ], 422);
            }
        }

        try {
            if ($quantity === 0) {
                $cartProduct->delete();
                $message = 'Product removed from cart';
            } else {
                $cartProduct->updateQuantity($quantity);
                $message = 'Cart updated successfully';
            }

            $cart->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'cart_totals' => [
                        'items' => $cart->fresh()->total_items,
                        'amount' => $cart->fresh()->total_amount,
                        'formatted_amount' => $cart->fresh()->formatted_total_amount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart'
            ], 500);
        }
    }

    /**
     * Remove product from cart
     */
    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_product_id' => 'required|exists:cart_products,id'
        ]);

        $cartProduct = CartProduct::findOrFail($validated['cart_product_id']);
        $cart = $cartProduct->cart;

        // Verify cart ownership
        if (!$this->canAccessCart($request, $cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to cart'
            ], 403);
        }

        try {
            $cartProduct->delete();
            $cart->recalculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully',
                'data' => [
                    'cart_totals' => [
                        'items' => $cart->fresh()->total_items,
                        'amount' => $cart->fresh()->total_amount,
                        'formatted_amount' => $cart->fresh()->formatted_total_amount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from cart'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $this->getCurrentCart($request);

        try {
            $cart->clear();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'data' => [
                    'cart_totals' => [
                        'items' => 0,
                        'amount' => 0,
                        'formatted_amount' => '0.00'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }

    /**
     * Merge guest cart with user cart on login
     */
    public function merge(Request $request): JsonResponse
    {
        if (!Auth::check() || !Auth::user()->isShopper()) {
            return response()->json([
                'success' => false,
                'message' => 'User must be logged in as a shopper'
            ], 401);
        }

        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return response()->json([
                'success' => true,
                'message' => 'No guest cart to merge'
            ]);
        }

        try {
            $guestCart = Cart::active()->forGuest($guestSessionId)->first();
            $userCart = Auth::user()->activeCart();

            if (!$guestCart || $guestCart->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No guest cart items to merge'
                ]);
            }

            if (!$userCart) {
                $userCart = Cart::findOrCreateForShopper(Auth::user()->shopper->id);
            }

            $userCart->mergeWith($guestCart);

            // Clear guest session
            Session::forget('cart_session_id');

            return response()->json([
                'success' => true,
                'message' => 'Cart merged successfully',
                'data' => [
                    'cart_totals' => [
                        'items' => $userCart->fresh()->total_items,
                        'amount' => $userCart->fresh()->total_amount,
                        'formatted_amount' => $userCart->fresh()->formatted_total_amount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to merge cart'
            ], 500);
        }
    }

    /**
     * Update prices to current product prices
     */
    public function updatePrices(Request $request): JsonResponse
    {
        $cart = $this->getCurrentCart($request);

        try {
            $priceChanges = $cart->checkForPriceChanges();
            $cart->updatePricesToCurrent();

            return response()->json([
                'success' => true,
                'message' => 'Prices updated successfully',
                'data' => [
                    'price_changes' => $priceChanges,
                    'cart_totals' => [
                        'items' => $cart->fresh()->total_items,
                        'amount' => $cart->fresh()->total_amount,
                        'formatted_amount' => $cart->fresh()->formatted_total_amount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update prices'
            ], 500);
        }
    }

    /**
     * Get current cart for user (guest or authenticated)
     */
    private function getCurrentCart(Request $request): Cart
    {
        if (Auth::check() && Auth::user()->isShopper()) {
            // Authenticated user with shopper profile
            $cart = Auth::user()->activeCart();
            if (!$cart) {
                $cart = Cart::findOrCreateForShopper(Auth::user()->shopper->id);
            }
            return $cart;
        }

        // Guest user
        $sessionId = Session::get('cart_session_id') ?: $request->header('X-Cart-Session-ID');
        return Cart::findOrCreateForGuest($sessionId);
    }

    /**
     * Check if user can access the cart
     */
    private function canAccessCart(Request $request, Cart $cart): bool
    {
        if (Auth::check() && Auth::user()->isShopper()) {
            return $cart->shopper_id === Auth::user()->shopper->id;
        }

        $sessionId = Session::get('cart_session_id') ?: $request->header('X-Cart-Session-ID');
        return $cart->session_id === $sessionId;
    }
}
