<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Helper to get wishlist data for both authenticated and guest users
    private function getWishlist()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Ensure the wishlist field is an array
            if (!isset($user->custom['wishlist']) || !is_array($user->custom['wishlist'])) {
                $user->custom = array_merge($user->custom ?? [], ['wishlist' => []]);
                $user->save();
            }

            return $user;
        } else {
            // For guest users, use session instead
            if (!session()->has('guest_wishlist')) {
                session(['guest_wishlist' => []]);
            }

            // Create a virtual user object to maintain consistency
            $user = new \App\Models\User();
            $user->custom = ['wishlist' => session('guest_wishlist', [])];
            return $user;
        }
    }

    public function index()
    {
        $user = $this->getWishlist();
        $wishlistIds = $user->custom['wishlist'] ?? [];
        $products = Product::whereIn('id', $wishlistIds)->get();

        return view('pages.wishlist', compact('products'));
    }

    public function toggle($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $user = $this->getWishlist();
        $wishlist = $user->custom['wishlist'] ?? [];

        // Toggle product in wishlist
        $isInWishlist = in_array($productId, $wishlist);

        if ($isInWishlist) {
            // Remove from wishlist
            $wishlist = array_values(array_filter($wishlist, function($id) use ($productId) {
                return $id != $productId;
            }));
            $message = "Removed from wishlist";
            $status = false;
        } else {
            // Add to wishlist
            $wishlist[] = $productId;
            $message = "Added to wishlist";
            $status = true;
        }

        // Update storage
        if (auth()->check()) {
            $custom = $user->custom;
            $custom['wishlist'] = $wishlist;
            $user->custom = $custom;
            $user->save();
        } else {
            session(['guest_wishlist' => $wishlist]);
        }

        return response()->json([
            'success' => true,
            'in_wishlist' => $status,
            'message' => $message
        ]);
    }

    public function check($productId)
    {
        $user = $this->getWishlist();
        $wishlist = $user->custom['wishlist'] ?? [];
        $isInWishlist = in_array($productId, $wishlist);

        return response()->json([
            'in_wishlist' => $isInWishlist
        ]);
    }

    public function remove($productId)
    {
        $user = $this->getWishlist();
        $wishlist = $user->custom['wishlist'] ?? [];

        // Remove from wishlist
        $wishlist = array_values(array_filter($wishlist, function($id) use ($productId) {
            return $id != $productId;
        }));

        // Update storage
        if (auth()->check()) {
            $custom = $user->custom;
            $custom['wishlist'] = $wishlist;
            $user->custom = $custom;
            $user->save();
        } else {
            session(['guest_wishlist' => $wishlist]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist'
        ]);
    }
}
