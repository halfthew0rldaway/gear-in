<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'order_id' => ['nullable', 'exists:orders,id'],
        ]);

        // If order_id is provided, check if order is completed
        if ($data['order_id']) {
            $order = Order::findOrFail($data['order_id']);
            
            if ($order->user_id !== $user->id) {
                return back()->withErrors(['review' => 'Unauthorized.']);
            }

            if ($order->status !== Order::STATUS_COMPLETED) {
                return back()->withErrors(['review' => 'Anda hanya dapat memberikan review untuk pesanan yang sudah selesai.']);
            }

            // Check if order contains this product
            if (!$order->items()->where('product_id', $product->id)->exists()) {
                return back()->withErrors(['review' => 'Produk tidak ditemukan dalam pesanan ini.']);
            }

            // Check if user already reviewed this product from this order
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->where('order_id', $order->id)
                ->first();

            if ($existingReview) {
                return back()->withErrors(['review' => 'Anda sudah memberikan review untuk produk ini dari pesanan ini.']);
            }
        } else {
            // Legacy check: Check if user has purchased this product
            $hasPurchased = Order::where('user_id', $user->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();

            if (!$hasPurchased) {
                return back()->withErrors([
                    'review' => 'Anda harus membeli dan menyelesaikan pesanan produk ini terlebih dahulu untuk memberikan review.',
                ]);
            }

            // Check if user already reviewed this product
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingReview) {
                return back()->withErrors([
                    'review' => 'Anda sudah memberikan review untuk produk ini.',
                ]);
            }
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $data['order_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_approved' => true, // Auto-approve for simplicity
        ]);

        return back()->with('status', 'Review berhasil ditambahkan.');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'admin_reply' => ['required', 'string', 'max:1000'],
        ]);

        $review->update([
            'admin_reply' => $data['admin_reply'],
            'admin_replied_by' => $request->user()->id,
            'admin_replied_at' => now(),
        ]);

        return back()->with('status', 'Balasan berhasil ditambahkan.');
    }
}
