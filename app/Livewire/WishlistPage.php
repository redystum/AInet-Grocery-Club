<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class WishlistPage extends Component
{
    protected $listeners = ['productRemovedFromWishlist' => 'refreshWishlist'];

    public function refreshWishlist()
    {
        // This method will be called when a product is removed from wishlist
        // The render method will be called automatically
    }

    public function getWishlistItems()
    {
        $wishlistIds = [];

        if (auth()->check()) {
            $user = auth()->user();
            $wishlistIds = $user->custom['wishlist'] ?? [];
        } else {
            $wishlistIds = session('guest_wishlist', []);
        }

        return Product::whereIn('id', $wishlistIds)->get();
    }

    public function removeFromWishlist($productId)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $wishlist = $user->custom['wishlist'] ?? [];

            // Remove from wishlist
            $wishlist = array_values(array_filter($wishlist, function($id) use ($productId) {
                return $id != $productId;
            }));

            // Update user custom data
            $custom = $user->custom;
            $custom['wishlist'] = $wishlist;
            $user->custom = $custom;
            $user->save();
        } else {
            $wishlist = session('guest_wishlist', []);

            // Remove from wishlist
            $wishlist = array_values(array_filter($wishlist, function($id) use ($productId) {
                return $id != $productId;
            }));

            // Update session
            session(['guest_wishlist' => $wishlist]);
        }

        $this->dispatch('showToast', message: 'Removed from wishlist', type: 'success');

        // Notify other components that might be listening
        $this->dispatch('productRemovedFromWishlist');
    }

    public function render()
    {
        $products = $this->getWishlistItems();
        return view('livewire.wishlist-page', [
            'products' => $products
        ]);
    }
}
