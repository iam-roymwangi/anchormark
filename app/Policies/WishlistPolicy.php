<?php

namespace App\Policies;

use App\Models\Wishlist;
use App\Models\User;

class WishlistPolicy
{
    public function view(User $user, Wishlist $wishlist): bool
    {
        return $user->isShopper() && $user->shopper->id === $wishlist->shopper_id;
    }

    public function update(User $user, Wishlist $wishlist): bool
    {
        return $user->isShopper() && $user->shopper->id === $wishlist->shopper_id;
    }

    public function delete(User $user, Wishlist $wishlist): bool
    {
        return $user->isShopper() && $user->shopper->id === $wishlist->shopper_id;
    }
}
