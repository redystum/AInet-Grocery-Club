<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class WishlistPreview extends Component
{
    public function getWishlistItems()
    {
        $wishlistIds = [];

        if (auth()->check()) {
            $user = auth()->user();
            $wishlistIds = $user->custom['wishlist'] ?? [];
        } else {
            $wishlistIds = session('guest_wishlist', []);
        }

        // Limit to 4 items for preview
        $wishlistIds = array_slice($wishlistIds, 0, 4);

        return Product::whereIn('id', $wishlistIds)->get();
    }

    public function render()
    {
        $products = $this->getWishlistItems();
        return view('livewire.wishlist-preview', [
            'products' => $products
        ]);
    }
}
