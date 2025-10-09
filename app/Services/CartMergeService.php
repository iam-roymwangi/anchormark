<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartMergeService
{
    /**
     * Merge guest cart with user cart on login
     */
    public function mergeGuestCartOnLogin(User $user): array
    {
        if (!$user->isShopper()) {
            return [
                'success' => false,
                'message' => 'User is not a shopper',
                'merged_items' => 0
            ];
        }

        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return [
                'success' => true,
                'message' => 'No guest cart to merge',
                'merged_items' => 0
            ];
        }

        try {
            DB::beginTransaction();

            $guestCart = Cart::active()->forGuest($guestSessionId)->first();
            
            if (!$guestCart || $guestCart->isEmpty()) {
                DB::rollBack();
                return [
                    'success' => true,
                    'message' => 'No guest cart items to merge',
                    'merged_items' => 0
                ];
            }

            // Get or create user cart
            $userCart = $user->activeCart();
            if (!$userCart) {
                $userCart = Cart::findOrCreateForShopper($user->shopper->id);
            }

            $originalItemCount = $userCart->total_items;
            
            // Merge carts
            $userCart->mergeWith($guestCart);

            $mergedItems = $userCart->fresh()->total_items - $originalItemCount;

            // Clear guest session
            Session::forget('cart_session_id');

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cart merged successfully',
                'merged_items' => $mergedItems,
                'total_items' => $userCart->fresh()->total_items,
                'total_amount' => $userCart->fresh()->total_amount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to merge cart: ' . $e->getMessage(),
                'merged_items' => 0
            ];
        }
    }

    /**
     * Check if user has a guest cart to merge
     */
    public function hasGuestCartToMerge(): bool
    {
        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return false;
        }

        $guestCart = Cart::active()->forGuest($guestSessionId)->first();
        
        return $guestCart && !$guestCart->isEmpty();
    }

    /**
     * Get guest cart summary
     */
    public function getGuestCartSummary(): array
    {
        $guestSessionId = Session::get('cart_session_id');
        
        if (!$guestSessionId) {
            return [
                'has_cart' => false,
                'items' => 0,
                'amount' => 0
            ];
        }

        $guestCart = Cart::active()->forGuest($guestSessionId)->first();
        
        if (!$guestCart || $guestCart->isEmpty()) {
            return [
                'has_cart' => false,
                'items' => 0,
                'amount' => 0
            ];
        }

        return [
            'has_cart' => true,
            'items' => $guestCart->total_items,
            'amount' => $guestCart->total_amount,
            'formatted_amount' => $guestCart->formatted_total_amount
        ];
    }

    /**
     * Clear guest cart session without merging
     */
    public function clearGuestCartSession(): void
    {
        $guestSessionId = Session::get('cart_session_id');
        
        if ($guestSessionId) {
            $guestCart = Cart::active()->forGuest($guestSessionId)->first();
            if ($guestCart) {
                $guestCart->markAsAbandoned();
            }
        }

        Session::forget('cart_session_id');
    }
}
