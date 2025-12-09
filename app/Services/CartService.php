<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
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

    public function totals(User $user, ?Voucher $voucher = null): array
    {
        $items = $this->getItems($user);
        $subtotal = $items->sum(function (CartItem $item) {
            $price = $item->product->price;
            if ($item->variant) {
                $price += $item->variant->price_adjustment;
            }
            // Apply product discount
            if ($item->product->discount_percentage > 0) {
                $now = now();
                $isDiscountActive = true;
                if ($item->product->discount_starts_at && $now < $item->product->discount_starts_at) {
                    $isDiscountActive = false;
                }
                if ($item->product->discount_expires_at && $now > $item->product->discount_expires_at) {
                    $isDiscountActive = false;
                }
                if ($isDiscountActive) {
                    $discount = $price * ($item->product->discount_percentage / 100);
                    $price = $price - $discount;
                }
            }
            return $item->quantity * $price;
        });
        $shipping = $subtotal > 0 ? 15000 : 0;
        $discount = 0;

        if ($voucher && $voucher->isValid($user, $subtotal)) {
            $discount = $voucher->calculateDiscount($subtotal);
        }

        $total = $subtotal + $shipping - $discount;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'voucher' => $voucher,
            'total' => max(0, $total),
        ];
    }

    public function totalsForItems(User $user, array $itemIds, ?Voucher $voucher = null): array
    {
        $items = $user->cartItems()
            ->whereIn('id', $itemIds)
            ->with(['product.category', 'variant'])
            ->latest()
            ->get();
        
        $subtotal = $items->sum(function (CartItem $item) {
            $price = $item->product->price;
            if ($item->variant) {
                $price += $item->variant->price_adjustment;
            }
            // Apply product discount
            if ($item->product->discount_percentage > 0) {
                $now = now();
                $isDiscountActive = true;
                if ($item->product->discount_starts_at && $now < $item->product->discount_starts_at) {
                    $isDiscountActive = false;
                }
                if ($item->product->discount_expires_at && $now > $item->product->discount_expires_at) {
                    $isDiscountActive = false;
                }
                if ($isDiscountActive) {
                    $discount = $price * ($item->product->discount_percentage / 100);
                    $price = $price - $discount;
                }
            }
            return $item->quantity * $price;
        });
        $shipping = $subtotal > 0 ? 15000 : 0;
        $discount = 0;

        if ($voucher && $voucher->isValid($user, $subtotal)) {
            $discount = $voucher->calculateDiscount($subtotal);
        }

        $total = $subtotal + $shipping - $discount;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'voucher' => $voucher,
            'total' => max(0, $total),
        ];
    }
}

