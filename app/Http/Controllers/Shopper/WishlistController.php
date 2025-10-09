<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Shopper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WishlistController extends Controller
{
    use AuthorizesRequests;
    public function index(): Response
    {
        $user = Auth::user();
        
        if (!$user->isShopper()) {
            return Inertia::render('Wishlists/Index', [
                'wishlists' => collect(),
                'message' => 'You need to be a shopper to access wishlists.'
            ]);
        }

        $wishlists = $user->shopper->wishlists()->with('products')->get();

        return Inertia::render('Wishlists/Index', [
            'wishlists' => $wishlists
        ]);
    }

    public function show(Wishlist $wishlist): Response
    {
        $this->authorize('view', $wishlist);

        $wishlist->load(['products' => function ($query) {
            $query->orderBy('wishlist_products.added_at', 'desc');
        }]);

        return Inertia::render('Wishlists/Show', [
            'wishlist' => $wishlist
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user->isShopper()) {
            return redirect()->back()
                ->with('error', 'You need to be a shopper to create wishlists.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500']
        ]);

        $wishlist = $user->shopper->wishlists()->create($validated);

        return redirect()->route('wishlists.show', $wishlist)
            ->with('success', 'Wishlist created successfully!');
    }

    public function update(Request $request, Wishlist $wishlist): RedirectResponse
    {
        $this->authorize('update', $wishlist);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500']
        ]);

        $wishlist->update($validated);

        return redirect()->route('wishlists.show', $wishlist)
            ->with('success', 'Wishlist updated successfully!');
    }

    public function destroy(Wishlist $wishlist): RedirectResponse
    {
        $this->authorize('delete', $wishlist);

        $wishlist->delete();

        return redirect()->route('wishlists.index')
            ->with('success', 'Wishlist deleted successfully!');
    }

    public function addProduct(Request $request, Wishlist $wishlist): JsonResponse
    {
        $this->authorize('update', $wishlist);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($wishlist->hasProduct($product)) {
            return response()->json([
                'message' => 'Product is already in this wishlist'
            ], 422);
        }

        $wishlist->addProduct($product);

        return response()->json([
            'message' => 'Product added to wishlist successfully'
        ]);
    }

    public function removeProduct(Request $request, Wishlist $wishlist): JsonResponse
    {
        $this->authorize('update', $wishlist);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$wishlist->hasProduct($product)) {
            return response()->json([
                'message' => 'Product is not in this wishlist'
            ], 422);
        }

        $wishlist->removeProduct($product);

        return response()->json([
            'message' => 'Product removed from wishlist successfully'
        ]);
    }

    public function toggleProduct(Request $request, Wishlist $wishlist): JsonResponse
    {
        $this->authorize('update', $wishlist);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id']
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($wishlist->hasProduct($product)) {
            $wishlist->removeProduct($product);
            $message = 'Product removed from wishlist';
            $action = 'removed';
        } else {
            $wishlist->addProduct($product);
            $message = 'Product added to wishlist';
            $action = 'added';
        }

        return response()->json([
            'message' => $message,
            'action' => $action
        ]);
    }

    public function moveProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'from_wishlist_id' => ['required', 'exists:wishlists,id'],
            'to_wishlist_id' => ['required', 'exists:wishlists,id', 'different:from_wishlist_id']
        ]);

        $fromWishlist = Auth::user()->wishlists()->findOrFail($validated['from_wishlist_id']);
        $toWishlist = Auth::user()->wishlists()->findOrFail($validated['to_wishlist_id']);
        
        $product = Product::findOrFail($validated['product_id']);

        if (!$fromWishlist->hasProduct($product)) {
            return response()->json([
                'message' => 'Product is not in the source wishlist'
            ], 422);
        }

        if ($toWishlist->hasProduct($product)) {
            return response()->json([
                'message' => 'Product is already in the destination wishlist'
            ], 422);
        }

        $fromWishlist->removeProduct($product);
        $toWishlist->addProduct($product);

        return response()->json([
            'message' => 'Product moved successfully'
        ]);
    }
}
