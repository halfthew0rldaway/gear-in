<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with('user', 'product', 'adminRepliedBy')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review): View
    {
        $review->load('user', 'product', 'adminRepliedBy');

        return view('admin.reviews.show', compact('review'));
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
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
