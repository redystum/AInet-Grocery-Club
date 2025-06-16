<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogFilterRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShippingCosts;

class ProductController extends Controller
{

    public function index(CatalogFilterRequest $request)
    {
        $request->validated();

        $query = Product::query();

        $sort = $request->input('sort', 'discount_desc');

        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('discount', 'desc');
                break;
        }

        if ($request->has('category')) {
            $category = $request->input('category');
            $query->where('category_id', $category);
        }

        $categories = Category::all();

        $products = $query->paginate(24);

        $totalProducts = Product::count();

        return view('pages.products', compact('categories', 'products', 'totalProducts'));
    }

    public function show(Product $product)
    {

        $product->load('category');

        $delivery_prices = ShippingCosts::orderBy('min_value_threshold', 'asc')->get();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // TODO: replce this with the custom column
        $images = [$product->photo, $relatedProducts->first()->photo, $relatedProducts->last()->photo, $product->photo];

        // Get wishlist status
        $inWishlist = false;
        if (auth()->check()) {
            $user = auth()->user();
            $inWishlist = isset($user->custom['wishlist']) && in_array($product->id, $user->custom['wishlist']);
        } else if (session()->has('guest_wishlist')) {
            $inWishlist = in_array($product->id, session('guest_wishlist', []));
        }

        // Get wishlist status for related products
        $relatedProductsWishlist = [];
        foreach ($relatedProducts as $relatedProduct) {
            $isInWishlist = false;
            if (auth()->check()) {
                $user = auth()->user();
                $isInWishlist = isset($user->custom['wishlist']) && in_array($relatedProduct->id, $user->custom['wishlist']);
            } else if (session()->has('guest_wishlist')) {
                $isInWishlist = in_array($relatedProduct->id, session('guest_wishlist', []));
            }
            $relatedProductsWishlist[$relatedProduct->id] = $isInWishlist;
        }

        return view('pages.product', compact('product', 'delivery_prices', 'relatedProducts', 'images', 'inWishlist', 'relatedProductsWishlist'));
    }

}
