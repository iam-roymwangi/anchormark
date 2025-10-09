<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index(Request $request): Response|RedirectResponse
    {
        // Get cart from session or current cart
        $cartId = Session::get('checkout_cart_id');
        
        if ($cartId) {
            $cart = Cart::with(['cartProducts.product'])->find($cartId);
        } else {
            // Fallback to current cart
            $cart = $this->getCurrentCart($request);
        }

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty');
        }

        return Inertia::render('Checkout/Index', [
            'cart' => $cart,
            'user' => Auth::check() ? Auth::user() : null
        ]);
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
}
