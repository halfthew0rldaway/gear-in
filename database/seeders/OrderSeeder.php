<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products and customers
        $products = Product::all();
        $customers = User::where('role', User::ROLE_CUSTOMER)->get();

        if ($products->isEmpty()) {
            $this->command->warn('⚠️  No products found. Skipping OrderSeeder. Please run ProductSeeder first.');
            return;
        }

        if ($customers->isEmpty()) {
            $this->command->warn('⚠️  No customers found. Creating default customer for orders...');
            User::firstOrCreate(
                ['email' => 'customer@gear-in.dev'],
                [
                    'name' => 'Gear-In Customer',
                    'role' => User::ROLE_CUSTOMER,
                    'password' => bcrypt('password'),
                ]
            );
            $customers = User::where('role', User::ROLE_CUSTOMER)->get();
        }

        // Create some additional dummy customers if needed
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => "customer{$i}@gear-in.dev"],
                [
                    'name' => "Customer {$i}",
                    'role' => User::ROLE_CUSTOMER,
                    'password' => bcrypt('password'),
                ]
            );
        }

        $allCustomers = User::where('role', User::ROLE_CUSTOMER)->get();
        $statuses = [
            Order::STATUS_PENDING,
            Order::STATUS_PAID,
            Order::STATUS_SHIPPED,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        $paymentMethods = ['bank_transfer', 'cod', 'e_wallet'];
        $shippingMethods = ['jne', 'jnt', 'sicepat', 'pos'];

        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Medan', 'Semarang', 'Makassar', 'Denpasar'];
        $addresses = [
            'Jl. Sudirman No. 123',
            'Jl. Gatot Subroto No. 45',
            'Jl. Thamrin No. 67',
            'Jl. Diponegoro No. 89',
            'Jl. Ahmad Yani No. 12',
            'Jl. Merdeka No. 34',
            'Jl. Asia Afrika No. 56',
            'Jl. Malioboro No. 78',
        ];

        // Create orders for the past 90 days
        $ordersToCreate = 150;
        
        for ($i = 0; $i < $ordersToCreate; $i++) {
            $customer = $allCustomers->random();
            $status = $statuses[array_rand($statuses)];
            
            // Weight completed orders more (40%)
            if (rand(1, 100) <= 40) {
                $status = Order::STATUS_COMPLETED;
            }
            
            // Random date within last 90 days
            $daysAgo = rand(0, 90);
            $placedAt = Carbon::now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Determine payment status based on order status
            $paymentStatus = 'unpaid';
            if ($status === Order::STATUS_PAID || $status === Order::STATUS_SHIPPED || $status === Order::STATUS_COMPLETED) {
                $paymentStatus = 'paid';
            } elseif ($status === Order::STATUS_CANCELLED) {
                $paymentStatus = 'cancelled';
            }

            // Select random products (1-4 items per order)
            $orderProducts = $products->random(rand(1, 4));
            $subtotal = 0;
            $lineItems = [];

            foreach ($orderProducts as $product) {
                $quantity = rand(1, 3);
                $variant = null;
                
                // Try to get a variant if product has variants
                if ($product->variants()->count() > 0) {
                    $activeVariants = $product->variants()->where('is_active', true)->where('stock', '>', 0)->get();
                    if ($activeVariants->count() > 0) {
                        $variant = $activeVariants->random();
                        $unitPrice = $product->price + $variant->price_adjustment;
                    } else {
                        $unitPrice = $product->price;
                    }
                } else {
                    $unitPrice = $product->price;
                }

                $lineTotal = $unitPrice * $quantity;
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            // Shipping fee (random between 10k-50k)
            $shippingFee = rand(10000, 50000);
            $total = $subtotal + $shippingFee;

            $city = $cities[array_rand($cities)];
            $address = $addresses[array_rand($addresses)];

            // Generate unique order code
            $orderCode = 'ORD-' . strtoupper(Str::random(8));
            while (Order::where('code', $orderCode)->exists()) {
                $orderCode = 'ORD-' . strtoupper(Str::random(8));
            }

            $order = Order::create([
                'code' => $orderCode,
                'user_id' => $customer->id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => $status,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'shipping_method' => $shippingMethods[array_rand($shippingMethods)],
                'payment_status' => $paymentStatus,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => '08' . rand(1000000000, 9999999999),
                'address_line1' => $address,
                'address_line2' => rand(0, 1) ? 'Gedung ' . rand(1, 10) . ' Lantai ' . rand(1, 5) : null,
                'city' => $city,
                'postal_code' => rand(10000, 99999),
                'notes' => rand(0, 1) ? 'Mohon diantar dengan hati-hati' : null,
                'placed_at' => $placedAt,
                'created_at' => $placedAt,
                'updated_at' => $placedAt,
            ]);

            // Add tracking number for shipped/completed orders
            if (in_array($status, [Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])) {
                $order->update([
                    'tracking_number' => strtoupper($order->shipping_method) . rand(1000000000, 9999999999),
                ]);
            }

            // Create order items
            foreach ($lineItems as $item) {
                $product = $item['product'];
                $variant = $item['variant'];
                
                // Get first image if available
                $productImage = $product->images()->first();
                $imagePath = $productImage ? $productImage->image_path : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $imagePath,
                    'variant_id' => $variant ? $variant->id : null,
                    'variant_name' => $variant ? $variant->name : null,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                    'created_at' => $placedAt,
                    'updated_at' => $placedAt,
                ]);
            }

            // Create status history
            $order->statusHistories()->create([
                'status' => $status,
                'user_id' => $customer->id,
                'note' => 'Pesanan dibuat',
                'created_at' => $placedAt,
                'updated_at' => $placedAt,
            ]);

            // Add additional status histories for completed orders
            if ($status === Order::STATUS_COMPLETED) {
                $order->statusHistories()->create([
                    'status' => Order::STATUS_PAID,
                    'user_id' => $customer->id,
                    'note' => 'Pembayaran diterima',
                    'created_at' => $placedAt->copy()->addHours(rand(1, 6)),
                    'updated_at' => $placedAt->copy()->addHours(rand(1, 6)),
                ]);
                
                $order->statusHistories()->create([
                    'status' => Order::STATUS_SHIPPED,
                    'user_id' => $customer->id,
                    'note' => 'Pesanan dikirim',
                    'created_at' => $placedAt->copy()->addDays(rand(1, 3)),
                    'updated_at' => $placedAt->copy()->addDays(rand(1, 3)),
                ]);
                
                $order->statusHistories()->create([
                    'status' => Order::STATUS_COMPLETED,
                    'user_id' => $customer->id,
                    'note' => 'Pesanan selesai',
                    'created_at' => $placedAt->copy()->addDays(rand(4, 7)),
                    'updated_at' => $placedAt->copy()->addDays(rand(4, 7)),
                ]);
            }
        }

        $this->command->info("Created {$ordersToCreate} dummy orders.");
    }
}

