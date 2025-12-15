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
    public function index(Request $request): View
    {
        $ordersQuery = Order::with('user');

        // Search
        if ($search = $request->input('q')) {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        switch ($sortBy) {
            case 'code':
                $ordersQuery->orderBy('code', $sortOrder);
                break;
            case 'customer':
                $ordersQuery->leftJoin('users', 'orders.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortOrder)
                    ->select('orders.*');
                break;
            case 'total':
                $ordersQuery->orderBy('total', $sortOrder);
                break;
            case 'status':
                $ordersQuery->orderBy('status', $sortOrder);
                break;
            case 'date':
            default:
                $ordersQuery->orderBy('created_at', $sortOrder);
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'sortBy', 'sortOrder'));
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
            'status' => [
                'required',
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_PAID,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED,
                    Order::STATUS_CANCELLED,
                ])
            ],
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
    public function assign(Request $request, Order $order): RedirectResponse
    {
        $action = $request->input('action');

        if ($action === 'claim') {
            $order->update(['handled_by' => $request->user()->id]);
            $message = 'Anda telah mengambil alih pesanan ini.';
        } elseif ($action === 'release') {
            abort_unless($order->handled_by === $request->user()->id, 403);
            $order->update(['handled_by' => null]);
            $message = 'Anda telah melepaskan pesanan ini.';
        } else {
            return back();
        }

        return back()->with('status', $message);
    }
}
