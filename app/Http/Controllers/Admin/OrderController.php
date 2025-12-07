<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('user')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items.product', 'user', 'statusHistories.user');

        return view('admin.orders.show', compact('order'));
    }

    public function receipt(Order $order): View
    {
        $order->load('items', 'user', 'statusHistories.user');

        return view('admin.orders.receipt', compact('order'));
    }

    public function update(Request $request, Order $order, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_SHIPPED,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        // Auto-update payment_status when status is set to PAID
        $updateData = [
            'status' => $data['status'],
            'tracking_number' => $data['tracking_number'] ?? null,
        ];

        // If status is updated to PAID, also update payment_status to 'paid'
        if ($data['status'] === Order::STATUS_PAID) {
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);

        $order->statusHistories()->create([
            'status' => $data['status'],
            'user_id' => $request->user()->id,
            'note' => 'Status diperbarui oleh admin' . ($data['tracking_number'] ? ' - Tracking: ' . $data['tracking_number'] : ''),
        ]);

        $logger->log('order.status_updated', $order, [
            'status' => $data['status'],
            'tracking_number' => $data['tracking_number'] ?? null,
        ], $request->user());

        return back()->with('status', 'Status pesanan diperbarui.');
    }
}
