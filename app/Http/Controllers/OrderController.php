<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Ongoing orders (pending, paid, shipped)
        $ongoingOrders = $user->orders()
            ->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
            ])
            ->with('items')
            ->latest()
            ->get();

        // Canceled orders
        $canceledOrders = $user->orders()
            ->where('status', Order::STATUS_CANCELLED)
            ->with('items')
            ->latest()
            ->get();

        // Completed orders
        $completedOrders = $user->orders()
            ->where('status', Order::STATUS_COMPLETED)
            ->with('items')
            ->latest()
            ->get();

        return view('storefront.orders.index', compact('ongoingOrders', 'canceledOrders', 'completedOrders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items.product', 'statusHistories.user', 'voucher');
        
        // Load reviews for products in this order
        $productIds = $order->items->pluck('product_id')->unique();
        $reviews = \App\Models\Review::where('user_id', $request->user()->id)
            ->whereIn('product_id', $productIds)
            ->where('order_id', $order->id)
            ->get()
            ->keyBy('product_id');

        return view('storefront.orders.show', compact('order', 'reviews'));
    }

    public function receipt(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        
        $order->load('items.product', 'voucher');

        $order->load('items', 'statusHistories.user');

        return view('storefront.orders.receipt', compact('order'));
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PAID])) {
            return back()->withErrors([
                'order' => 'Pesanan hanya dapat dibatalkan jika status masih pending atau paid.',
            ]);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        $order->statusHistories()->create([
            'status' => Order::STATUS_CANCELLED,
            'user_id' => $request->user()->id,
            'note' => 'Pesanan dibatalkan oleh customer',
        ]);

        // Restore stock
        foreach ($order->items as $item) {
            $product = \App\Models\Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('status', 'Pesanan berhasil dibatalkan.');
    }
}
