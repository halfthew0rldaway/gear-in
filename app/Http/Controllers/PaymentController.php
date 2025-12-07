<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private array $bankAccounts = [
        'BCA' => [
            'name' => 'Bank Central Asia (BCA)',
            'account' => '1234567890',
            'account_name' => 'PT Gear-In Indonesia',
            'va' => '98888' . '1234567890', // Format: 98888 + account
        ],
        'Mandiri' => [
            'name' => 'Bank Mandiri',
            'account' => '9876543210',
            'account_name' => 'PT Gear-In Indonesia',
            'va' => '88888' . '9876543210',
        ],
        'BNI' => [
            'name' => 'Bank Negara Indonesia (BNI)',
            'account' => '5555555555',
            'account_name' => 'PT Gear-In Indonesia',
            'va' => '77777' . '5555555555',
        ],
        'BRI' => [
            'name' => 'Bank Rakyat Indonesia (BRI)',
            'account' => '1111111111',
            'account_name' => 'PT Gear-In Indonesia',
            'va' => '66666' . '1111111111',
        ],
        'CIMB' => [
            'name' => 'CIMB Niaga',
            'account' => '2222222222',
            'account_name' => 'PT Gear-In Indonesia',
            'va' => '55555' . '2222222222',
        ],
    ];

    public function show(Order $order): View
    {
        $user = auth()->user();
        
        // Ensure user owns this order
        abort_unless($order->user_id === $user->id, 403);
        
        // Only show payment page if order is pending/waiting payment
        abort_unless(in_array($order->payment_status, ['waiting', 'unpaid']), 403, 'Pesanan ini sudah dibayar atau tidak memerlukan pembayaran.');

        return view('storefront.payment.show', [
            'order' => $order,
            'bankAccounts' => $this->bankAccounts,
        ]);
    }

    public function complete(Request $request, Order $order): RedirectResponse
    {
        $user = auth()->user();
        
        // Ensure user owns this order
        abort_unless($order->user_id === $user->id, 403);
        
        // Only allow completion if order is waiting payment
        abort_unless(in_array($order->payment_status, ['waiting', 'unpaid']), 403, 'Pesanan ini sudah dibayar.');

        // Update order status
        $order->update([
            'payment_status' => 'paid',
            'status' => Order::STATUS_PAID,
        ]);

        // Create status history
        $order->statusHistories()->create([
            'status' => Order::STATUS_PAID,
            'user_id' => $user->id,
            'note' => 'Pembayaran berhasil dilakukan',
        ]);

        return redirect()
            ->route('payment.success', $order)
            ->with('status', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    }

    public function success(Order $order): View
    {
        $user = auth()->user();
        
        // Ensure user owns this order
        abort_unless($order->user_id === $user->id, 403);

        return view('storefront.payment.success', [
            'order' => $order,
        ]);
    }
}

