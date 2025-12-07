<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class CartService
{
    public function getItems(User $user): Collection
    {
        return $user->cartItems()
            ->with(['product.category', 'variant'])
            ->latest()
            ->get();
    }

    public function addItem(User $user, Product $product, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $cartItem = CartItem::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'variant_id' => $variantId,
        ]);

        $availableStock = $product->stock;
        if ($variantId) {
            $variant = \App\Models\ProductVariant::find($variantId);
            if ($variant && $variant->product_id === $product->id) {
                $availableStock = $variant->stock;
            }
        }

        $currentQty = $cartItem->exists ? $cartItem->quantity : 0;
        $cartItem->quantity = min($availableStock, $currentQty + $quantity);
        $cartItem->save();

        return $cartItem->load(['product', 'variant']);
    }

    public function updateItem(User $user, CartItem $cartItem, int $quantity): CartItem
    {
        abort_unless($cartItem->user_id === $user->id, 403);

        $cartItem->update([
            'quantity' => min(max($quantity, 1), $cartItem->product->stock),
        ]);

        return $cartItem->load('product');
    }

    public function removeItem(User $user, CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === $user->id, 403);

        $cartItem->delete();
    }

    public function clear(User $user): void
    {
        $user->cartItems()->delete();
    }

    public function totals(User $user): array
    {
        $items = $this->getItems($user);
        $subtotal = $items->sum(function (CartItem $item) {
            $price = $item->product->price;
            if ($item->variant) {
                $price += $item->variant->price_adjustment;
            }
            return $item->quantity * $price;
        });
        $shipping = $subtotal > 0 ? 15000 : 0;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }
}

