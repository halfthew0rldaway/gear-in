<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingReviewController extends Controller
{
    public function create(Product $product): View
    {
        // Get customer users for the dropdown
        $customers = User::where('role', User::ROLE_CUSTOMER)
            ->orderBy('name')
            ->get();

        return view('admin.reviews.create', compact('product', 'customers'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Verify the user is a customer
        $user = User::findOrFail($data['user_id']);
        if (!$user->isCustomer()) {
            return back()->withErrors(['user_id' => 'Hanya pelanggan yang dapat memberikan review.']);
        }

        // Check if this user already has a review for this product (without order_id)
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->whereNull('order_id')
            ->first();

        if ($existingReview) {
            return back()->withErrors([
                'error' => 'User ini sudah memiliki review marketing untuk produk ini.',
            ]);
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => null, // Marketing reviews don't have an order
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_approved' => true,
        ]);

        return redirect()
            ->route('admin.products.show', $product)
            ->with('status', 'Review marketing berhasil ditambahkan.');
    }
}
