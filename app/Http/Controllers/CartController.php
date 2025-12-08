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
use Illuminate\Http\JsonResponse;

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

    public function store(StoreCartItemRequest $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $product = Product::with('category')
            ->findOrFail($data['product_id']);

        if (! $product->is_active) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Produk tidak tersedia.'], 422);
            }
            return back()->withErrors(['product' => 'Produk tidak tersedia.']);
        }

        $variantId = $data['variant_id'] ?? null;
        $variantId = $variantId ? (int) $variantId : null; // Convert to int or null
        $hasVariants = $product->variants()->count() > 0;
        $availableStock = $product->stock;

        // If product has variants, variant_id is required
        if ($hasVariants && !$variantId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan pilih varian terlebih dahulu.', 'requires_variant' => true], 422);
            }
            return back()->withErrors(['variant_id' => 'Silakan pilih varian terlebih dahulu.']);
        }

        if ($variantId && $variantId > 0) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if (!$variant || $variant->product_id !== $product->id || !$variant->is_active) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Varian tidak valid atau tidak tersedia.'], 422);
                }
                return back()->withErrors(['variant_id' => 'Varian tidak valid atau tidak tersedia.']);
            }
            $availableStock = $variant->stock;
            if ($variant->stock < $data['quantity']) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok varian tidak mencukupi.'], 422);
                }
                return back()->withErrors(['quantity' => 'Stok varian tidak mencukupi.']);
            }
        } else {
            // No variant selected, use product stock
            if ($product->stock < $data['quantity']) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Stok produk tidak mencukupi.'], 422);
                }
                return back()->withErrors(['quantity' => 'Stok produk tidak mencukupi.']);
            }
        }

        $this->cartService->addItem($request->user(), $product, $data['quantity'], $variantId);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.']);
        }

        return back()->with('status', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->cartService->updateItem($request->user(), $cartItem, $request->validated()['quantity']);

        // If AJAX request, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jumlah diperbarui.',
            ]);
        }

        return back()->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->cartService->removeItem(auth()->user(), $cartItem);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'items' => ['required', 'string'],
        ]);

        $itemIds = json_decode($request->input('items'), true);
        
        if (!is_array($itemIds) || empty($itemIds)) {
            return back()->withErrors(['items' => 'Tidak ada item yang dipilih.']);
        }

        $user = $request->user();
        $deleted = 0;

        foreach ($itemIds as $itemId) {
            $cartItem = CartItem::where('id', $itemId)
                ->where('user_id', $user->id)
                ->first();
            
            if ($cartItem) {
                $this->cartService->removeItem($user, $cartItem);
                $deleted++;
            }
        }

        return back()->with('status', "{$deleted} item berhasil dihapus dari keranjang.");
    }
}
