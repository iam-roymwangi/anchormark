<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CartSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->initializeCartSession($request);
        
        // Add cart session ID to response headers for frontend
        $response = $next($request);
        
        $cartSessionId = Session::get('cart_session_id');
        if ($cartSessionId) {
            $response->headers->set('X-Cart-Session-ID', $cartSessionId);
        }

        return $response;
    }

    /**
     * Initialize cart session for guest users
     */
    private function initializeCartSession(Request $request): void
    {
        // Skip if user is authenticated and has shopper profile
        if (Auth::check() && Auth::user()->isShopper()) {
            return;
        }

        // Check if cart session ID exists in session
        $sessionId = Session::get('cart_session_id');
        
        // Check if cart session ID exists in request headers (for API calls)
        $headerSessionId = $request->header('X-Cart-Session-ID');
        
        // Use header session ID if available and session doesn't have one
        if ($headerSessionId && !$sessionId) {
            $sessionId = $headerSessionId;
            Session::put('cart_session_id', $sessionId);
        }
        
        // Generate new session ID if none exists
        if (!$sessionId) {
            $sessionId = Str::uuid();
            Session::put('cart_session_id', $sessionId);
        }

        // Ensure cart exists for this session
        $this->ensureCartExists($sessionId);
    }

    /**
     * Ensure a cart exists for the given session ID
     */
    private function ensureCartExists(string $sessionId): void
    {
        $cart = Cart::active()->forGuest($sessionId)->first();
        
        if (!$cart) {
            Cart::create([
                'session_id' => $sessionId,
                'cart_status' => \App\Enums\CartStatus::Active,
                'total_items' => 0,
                'total_amount' => 0,
                'last_activity_at' => now()
            ]);
        } else {
            // Update last activity
            $cart->touch('last_activity_at');
        }
    }

    /**
     * Handle cart merge on user login
     */
    public static function handleLoginMerge(Request $request): void
    {
        if (!Auth::check() || !Auth::user()->isShopper()) {
            return;
        }

        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return;
        }

        $guestCart = Cart::active()->forGuest($guestSessionId)->first();
        
        if (!$guestCart || $guestCart->isEmpty()) {
            return;
        }

        // Get or create user cart
        $userCart = Auth::user()->activeCart();
        if (!$userCart) {
            $userCart = Cart::findOrCreateForShopper(Auth::user()->shopper->id);
        }

        // Merge carts
        $userCart->mergeWith($guestCart);

        // Clear guest session
        Session::forget('cart_session_id');
    }

    /**
     * Clean up abandoned carts (can be called from a scheduled job)
     */
    public static function cleanupAbandonedCarts(int $hoursThreshold = 24): int
    {
        $threshold = now()->subHours($hoursThreshold);
        
        $abandonedCarts = Cart::active()
            ->where('last_activity_at', '<', $threshold)
            ->get();

        $count = 0;
        foreach ($abandonedCarts as $cart) {
            $cart->markAsAbandoned();
            $count++;
        }

        return $count;
    }

    /**
     * Get cart session ID from request
     */
    public static function getCartSessionId(Request $request): ?string
    {
        // Priority: session > header > generate new
        $sessionId = Session::get('cart_session_id');
        
        if (!$sessionId) {
            $sessionId = $request->header('X-Cart-Session-ID');
        }
        
        if (!$sessionId) {
            $sessionId = Str::uuid();
            Session::put('cart_session_id', $sessionId);
        }

        return $sessionId;
    }

    /**
     * Set cart session ID
     */
    public static function setCartSessionId(string $sessionId): void
    {
        Session::put('cart_session_id', $sessionId);
    }

    /**
     * Clear cart session
     */
    public static function clearCartSession(): void
    {
        Session::forget('cart_session_id');
    }
}
