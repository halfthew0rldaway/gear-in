<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;

class VoucherService
{
    public function validate(string $code, User $user, float $subtotal): array
    {
        $voucher = Voucher::where('code', strtoupper($code))->first();

        if (!$voucher) {
            return ['valid' => false, 'message' => 'Kode voucher tidak ditemukan.'];
        }

        if (!$voucher->isValid($user, $subtotal)) {
            return ['valid' => false, 'message' => 'Kode voucher tidak valid atau sudah tidak berlaku.'];
        }

        $discount = $voucher->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'voucher' => $voucher,
            'discount' => $discount,
            'message' => 'Voucher berhasil diterapkan.',
        ];
    }

    public function apply(Voucher $voucher, User $user, Order $order): void
    {
        $subtotal = $order->subtotal;
        $discount = $voucher->calculateDiscount($subtotal);

        VoucherUsage::create([
            'voucher_id' => $voucher->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'discount_amount' => $discount,
        ]);

        $voucher->increment('usage_count');
    }
}

