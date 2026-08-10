<?php

namespace App\Services\Wishlist;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class WishlistService
{
    // ================================================================
    // Get user's wishlist (paginated)
    // ================================================================
    public function getUserWishlist(User $user, Request $request): LengthAwarePaginator
    {
        $perPage = min((int) $request->get('per_page', 20), 100);

        return $user->wishlistProducts()
            ->with([
                'brand',
                'categories',
                'images',
                'variants',
                'discounts.campaign',
            ])
            ->orderByDesc('wishlists.created_at')
            ->paginate($perPage);
    }

    // ================================================================
    // Add product to wishlist
    // ================================================================
    public function add(User $user, Product $product): Wishlist
    {
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return $wishlist;
    }

    // ================================================================
    // Remove product from wishlist
    // ================================================================
    public function remove(User $user, Product $product): bool
    {
        return Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete() > 0;
    }

    // ================================================================
    // Toggle product in wishlist (add if not exists, remove if exists)
    // ================================================================
    public function toggle(User $user, Product $product): array
    {
        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return [
                'action' => 'removed',
                'in_wishlist' => false,
                'message' => 'Product removed from wishlist.',
            ];
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return [
            'action' => 'added',
            'in_wishlist' => true,
            'message' => 'Product added to wishlist.',
        ];
    }

    // ================================================================
    // Check if product is in user's wishlist
    // ================================================================
    public function isInWishlist(User $user, Product $product): bool
    {
        return Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();
    }

    // ================================================================
    // Get user's wishlist count
    // ================================================================
    public function getCount(User $user): int
    {
        return Wishlist::where('user_id', $user->id)->count();
    }

    // ================================================================
    // Clear all wishlist items
    // ================================================================
    public function clear(User $user): int
    {
        return Wishlist::where('user_id', $user->id)->delete();
    }

    // ================================================================
    // Get product IDs in user's wishlist (for bulk check)
    // ================================================================
    public function getWishlistProductIds(User $user): array
    {
        return Wishlist::where('user_id', $user->id)
            ->pluck('product_id')
            ->toArray();
    }
}