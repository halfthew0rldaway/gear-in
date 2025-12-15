<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Diskon Pengguna Baru',
                'description' => 'Diskon 10% untuk pengguna baru tanpa minimal pembelian.',
                'type' => 'percentage',
                'value' => 10, // 10%
                'min_purchase' => 0,
                'max_discount' => null,
                'usage_limit' => null,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'GEARIN50',
                'name' => 'Potongan 50 Ribu',
                'description' => 'Potongan langsung Rp 50.000 dengan minimal belanja Rp 200.000.',
                'type' => 'fixed',
                'value' => 50000,
                'min_purchase' => 200000,
                'max_discount' => null,
                'usage_limit' => 100,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
                'is_active' => true,
            ],
            [
                'code' => 'KILAT20',
                'name' => 'Flash Sale 20%',
                'description' => 'Diskon 20% maksimal Rp 25.000. Berlaku hari ini saja.',
                'type' => 'percentage',
                'value' => 20,
                'min_purchase' => 50000,
                'max_discount' => 25000,
                'usage_limit' => 50,
                'user_limit' => 1,
                'starts_at' => now()->startOfDay(),
                'expires_at' => now()->endOfDay(),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED',
                'name' => 'Voucher Kedaluwarsa',
                'description' => 'Contoh voucher yang sudah tidak berlaku.',
                'type' => 'percentage',
                'value' => 50,
                'min_purchase' => 0,
                'max_discount' => null,
                'usage_limit' => null,
                'user_limit' => 1,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->subDay(),
                'is_active' => true,
            ],
            [
                'code' => 'FUTURE',
                'name' => 'Voucher Masa Depan',
                'description' => 'Belum mulai berlaku.',
                'type' => 'fixed',
                'value' => 100000,
                'min_purchase' => 500000,
                'max_discount' => null,
                'usage_limit' => null,
                'user_limit' => 1,
                'starts_at' => now()->addDays(7),
                'expires_at' => now()->addDays(14),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            \App\Models\Voucher::updateOrCreate(
                ['code' => $voucher['code']],
                $voucher
            );
        }
    }
}
