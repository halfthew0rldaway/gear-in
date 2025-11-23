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
            ->with('product.category')
            ->latest()
            ->get();
    }

    public function addItem(User $user, Product $product, int $quantity = 1): CartItem
    {
        $cartItem = CartItem::firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $currentQty = $cartItem->exists ? $cartItem->quantity : 0;

        $cartItem->quantity = min($product->stock, $currentQty + $quantity);
        $cartItem->save();

        return $cartItem->load('product');
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
        $subtotal = $items->sum(fn (CartItem $item) => $item->quantity * $item->product->price);
        $shipping = $subtotal > 0 ? 15000 : 0;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }
}

