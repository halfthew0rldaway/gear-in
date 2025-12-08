#!/usr/bin/env php
<?php

/**
 * Script untuk mengecek data di database
 * Usage: php check-db.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

echo "\n=== DATABASE CHECK ===\n\n";

// Timezone Info
echo "Timezone Info:\n";
echo "  App Timezone: " . config('app.timezone') . "\n";
echo "  Current Time: " . now()->format('Y-m-d H:i:s T') . "\n";
echo "  Current Date: " . now()->format('Y-m-d') . "\n\n";

// Orders
echo "Orders:\n";
echo "  Total Orders: " . Order::count() . "\n";
$today = now()->format('Y-m-d');
echo "  Orders Today (" . $today . "): " . Order::whereDate('created_at', $today)->count() . "\n";
echo "  Orders This Month: " . Order::whereBetween('created_at', [
    now()->startOfMonth(),
    now()->endOfMonth()
])->count() . "\n";
echo "  Pending Orders: " . Order::where('status', Order::STATUS_PENDING)->count() . "\n";
echo "  Completed Orders: " . Order::where('status', Order::STATUS_COMPLETED)->count() . "\n\n";

// Products
echo "Products:\n";
echo "  Total Products: " . Product::count() . "\n";
echo "  Active Products: " . Product::where('is_active', true)->count() . "\n";
echo "  Low Stock (<10): " . Product::where('stock', '<', 10)->where('stock', '>', 0)->count() . "\n";
echo "  Out of Stock: " . Product::where('stock', 0)->count() . "\n\n";

// Customers
echo "Customers:\n";
echo "  Total Customers: " . User::where('role', User::ROLE_CUSTOMER)->count() . "\n\n";

// Recent Orders Today
echo "Recent Orders Today:\n";
$recentOrders = Order::whereDate('created_at', $today)
    ->latest('created_at')
    ->take(5)
    ->get(['id', 'code', 'status', 'total', 'created_at']);

if ($recentOrders->isEmpty()) {
    echo "  No orders today.\n";
} else {
    foreach ($recentOrders as $order) {
        echo sprintf(
            "  ID: %d | Code: %s | Status: %s | Created: %s | Total: %s\n",
            $order->id,
            $order->code,
            $order->status,
            $order->created_at->format('Y-m-d H:i:s T'),
            number_format($order->total, 0, ',', '.')
        );
    }
}

echo "\n=== END ===\n\n";

