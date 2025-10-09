<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index(Request $request): Response
    {
        $cart = $this->getCurrentCart($request);
        
        $cart->load([
            'cartProducts.product',
            'cartProducts.product.images'
        ]);

        $priceChanges = $cart->checkForPriceChanges();
        
        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'items' => $cart->cartProducts,
            'priceChanges' => $priceChanges,
            'totals' => [
                'items' => $cart->total_items,
                'amount' => $cart->total_amount,
                'formatted_amount' => $cart->formatted_total_amount
            ],
            'canCheckout' => $cart->total_items > 0 && $this->canProceedToCheckout($cart)
        ]);
    }

    /**
     * Add product to cart and redirect back
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = $validated['quantity'] ?? 1;

        // Check if product is available
        if (!$product->is_active || $product->stock_quantity <= 0) {
            return redirect()->back()
                ->with('error', 'Product is not available');
        }

        // Check stock availability
        $cart = $this->getCurrentCart($request);
        $existingItem = $cart->cartProducts()
            ->where('product_id', $product->id)
            ->first();

        $requestedQuantity = $existingItem ? 
            $existingItem->quantity + $quantity : $quantity;

        if ($requestedQuantity > $product->stock_quantity) {
            return redirect()->back()
                ->with('error', "Only {$product->stock_quantity} items available in stock");
        }

        try {
            $cart->addProduct($product, $quantity);
            
            // Update session for guest carts
            if ($cart->is_guest_cart) {
                Session::put('cart_session_id', $cart->session_id);
            }

            return redirect()->back()
                ->with('success', 'Product added to cart successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add product to cart');
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartProduct $cartProduct): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:99'
        ]);

        $cart = $cartProduct->cart;

        // Verify cart ownership
        if (!$this->canAccessCart($request, $cart)) {
            return redirect()->back()
                ->with('error', 'Unauthorized access to cart');
        }

        $quantity = $validated['quantity'];

        // Check stock availability if increasing quantity
        if ($quantity > $cartProduct->quantity) {
            if ($cartProduct->product->stock_quantity < $quantity) {
                return redirect()->back()
                    ->with('error', "Only {$cartProduct->product->stock_quantity} items available in stock");
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

            return redirect()->route('cart.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update cart');
        }
    }

    /**
     * Remove product from cart
     */
    public function remove(CartProduct $cartProduct, Request $request): RedirectResponse
    {
        $cart = $cartProduct->cart;

        // Verify cart ownership
        if (!$this->canAccessCart($request, $cart)) {
            return redirect()->back()
                ->with('error', 'Unauthorized access to cart');
        }

        try {
            $cartProduct->delete();
            $cart->recalculateTotals();

            return redirect()->route('cart.index')
                ->with('success', 'Product removed from cart successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove product from cart');
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request): RedirectResponse
    {
        $cart = $this->getCurrentCart($request);

        try {
            $cart->clear();

            return redirect()->route('cart.index')
                ->with('success', 'Cart cleared successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to clear cart');
        }
    }

    /**
     * Update prices to current product prices
     */
    public function updatePrices(Request $request): RedirectResponse
    {
        $cart = $this->getCurrentCart($request);

        try {
            $priceChanges = $cart->checkForPriceChanges();
            $cart->updatePricesToCurrent();

            $message = count($priceChanges) > 0 
                ? 'Prices updated successfully' 
                : 'All prices are already up to date';

            return redirect()->route('cart.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update prices');
        }
    }

    /**
     * Proceed to checkout
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart = $this->getCurrentCart($request);

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        if (!$this->canProceedToCheckout($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Cannot proceed to checkout. Please check your cart items.');
        }

        // Check for price changes before checkout
        $priceChanges = $cart->checkForPriceChanges();
        if (!empty($priceChanges)) {
            return redirect()->route('cart.index')
                ->with('warning', 'Some items have price changes. Please review your cart before checkout.');
        }

        // Store cart ID in session for checkout process
        Session::put('checkout_cart_id', $cart->id);

        return redirect()->route('checkout.index');
    }

    /**
     * Merge guest cart with user cart on login
     */
    public function merge(Request $request): RedirectResponse
    {
        if (!Auth::check() || !Auth::user()->isShopper()) {
            return redirect()->back()
                ->with('error', 'User must be logged in as a shopper');
        }

        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return redirect()->back()
                ->with('info', 'No guest cart to merge');
        }

        try {
            $guestCart = Cart::active()->forGuest($guestSessionId)->first();
            $userCart = Auth::user()->activeCart();

            if (!$guestCart || $guestCart->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'No guest cart items to merge');
            }

            if (!$userCart) {
                $userCart = Cart::findOrCreateForShopper(Auth::user()->shopper->id);
            }

            $userCart->mergeWith($guestCart);

            // Clear guest session
            Session::forget('cart_session_id');

            return redirect()->route('cart.index')
                ->with('success', 'Cart merged successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to merge cart');
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
        $sessionId = Session::get('cart_session_id');
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

        $sessionId = Session::get('cart_session_id');
        return $cart->session_id === $sessionId;
    }

    /**
     * Check if cart can proceed to checkout
     */
    private function canProceedToCheckout(Cart $cart): bool
    {
        // Check if cart has items
        if ($cart->isEmpty()) {
            return false;
        }

        // Check if all products are still available
        foreach ($cart->cartProducts as $cartProduct) {
            if (!$cartProduct->isProductAvailable()) {
                return false;
            }
        }

        return true;
    }
}
