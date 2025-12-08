<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();
        
        // Get customer users (or create dummy customers)
        $customers = User::where('role', User::ROLE_CUSTOMER)->get();
        
        // If no customers, create some dummy customers for reviews
        if ($customers->isEmpty()) {
            $customers = collect([
                User::create([
                    'name' => 'Budi Santoso',
                    'email' => 'budi@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CUSTOMER,
                ]),
                User::create([
                    'name' => 'Siti Nurhaliza',
                    'email' => 'siti@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CUSTOMER,
                ]),
                User::create([
                    'name' => 'Ahmad Rizki',
                    'email' => 'ahmad@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CUSTOMER,
                ]),
                User::create([
                    'name' => 'Dewi Lestari',
                    'email' => 'dewi@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CUSTOMER,
                ]),
                User::create([
                    'name' => 'Rizki Pratama',
                    'email' => 'rizki@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_CUSTOMER,
                ]),
            ]);
        }
        
        // Sample comments
        $comments = [
            'Produk sangat bagus, sesuai dengan deskripsi!',
            'Kualitas sangat memuaskan, recommended!',
            'Pengiriman cepat dan produk original.',
            'Sangat puas dengan pembelian ini.',
            'Produk sesuai ekspektasi, terima kasih!',
            'Kualitas bagus untuk harga segini.',
            'Pengiriman cepat, produk aman sampai.',
            'Sangat recommended untuk yang mencari produk berkualitas.',
            'Produk original dan packing rapi.',
            'Pelayanan bagus, produk sesuai deskripsi.',
            'Kualitas oke, harga juga reasonable.',
            'Produk bagus, pengiriman cepat.',
            'Sesuai ekspektasi, puas dengan pembelian.',
            'Kualitas produk sangat baik.',
            'Recommended untuk yang mencari produk ini.',
        ];
        
        // Add reviews to products (not all products, random selection)
        foreach ($products as $product) {
            // Random chance: 60% products get reviews
            if (rand(1, 100) <= 60) {
                // Random number of reviews per product (1-8 reviews)
                $reviewCount = rand(1, 8);
                
                for ($i = 0; $i < $reviewCount; $i++) {
                    // Random rating (mostly 4-5 stars, some 3 stars, rare 1-2 stars)
                    $ratingChance = rand(1, 100);
                    if ($ratingChance <= 70) {
                        $rating = rand(4, 5); // 70% chance: 4-5 stars
                    } elseif ($ratingChance <= 90) {
                        $rating = 3; // 20% chance: 3 stars
                    } else {
                        $rating = rand(1, 2); // 10% chance: 1-2 stars
                    }
                    
                    // Random customer
                    $customer = $customers->random();
                    
                    // Random comment (70% chance to have comment)
                    $comment = null;
                    if (rand(1, 100) <= 70) {
                        $comment = $comments[array_rand($comments)];
                    }
                    
                    // Random date within last 3 months
                    $createdAt = now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                    
                    $review = Review::create([
                        'user_id' => $customer->id,
                        'product_id' => $product->id,
                        'order_id' => null, // Can be null for dummy data
                        'rating' => $rating,
                        'comment' => $comment,
                        'is_approved' => true, // All reviews approved for dummy data
                    ]);
                    
                    // Update timestamps manually
                    $review->created_at = $createdAt;
                    $review->updated_at = $createdAt;
                    $review->save();
                }
            }
        }
    }
}

