<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(Request $request): View
    {
        $cart = $this->cartService->totals($request->user());

        return view('storefront.cart', [
            'cartItems' => $cart['items'],
            'subtotal' => $cart['subtotal'],
            'shipping' => $cart['shipping'],
            'total' => $cart['total'],
        ]);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $product = Product::with('category')
            ->findOrFail($data['product_id']);

        if (! $product->is_active) {
            return back()->withErrors(['product' => 'Produk tidak tersedia.']);
        }

        $variantId = $data['variant_id'] ?? null;
        $variantId = $variantId ? (int) $variantId : null; // Convert to int or null
        $hasVariants = $product->variants()->count() > 0;
        $availableStock = $product->stock;

        // If product has variants, variant_id is required
        if ($hasVariants && !$variantId) {
            return back()->withErrors(['variant_id' => 'Silakan pilih varian terlebih dahulu.']);
        }

        if ($variantId && $variantId > 0) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if (!$variant || $variant->product_id !== $product->id || !$variant->is_active) {
                return back()->withErrors(['variant_id' => 'Varian tidak valid atau tidak tersedia.']);
            }
            $availableStock = $variant->stock;
            if ($variant->stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Stok varian tidak mencukupi.']);
            }
        } else {
            // No variant selected, use product stock
            if ($product->stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Stok produk tidak mencukupi.']);
            }
        }

        $this->cartService->addItem($request->user(), $product, $data['quantity'], $variantId);

        return back()->with('status', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->cartService->updateItem($request->user(), $cartItem, $request->validated()['quantity']);

        return back()->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->removeItem(auth()->user(), $cartItem);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }
}
