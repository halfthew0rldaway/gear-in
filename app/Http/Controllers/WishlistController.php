<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $wishlists = $request->user()
            ->wishlists()
            ->with('product.category')
            ->latest()
            ->paginate(12);

        return view('storefront.wishlist', compact('wishlists'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return back()->with('status', 'Produk sudah ada di wishlist.');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return back()->with('status', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('status', 'Produk dihapus dari wishlist.');
    }
}
