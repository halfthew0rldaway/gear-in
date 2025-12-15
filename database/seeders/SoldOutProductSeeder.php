<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SoldOutProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist
        $hardware = Category::firstOrCreate(['slug' => 'hardware'], ['name' => 'Hardware']);
        $accessories = Category::firstOrCreate(['slug' => 'accessories'], ['name' => 'Accessories']);
        $consoles = Category::firstOrCreate(['slug' => 'consoles'], ['name' => 'Consoles']);

        $products = [
            [
                'category_id' => $hardware->id,
                'name' => 'NVIDIA GeForce RTX 3080 Founders Edition',
                'slug' => 'nvidia-geforce-rtx-3080-founders-edition',
                'summary' => 'Kartu grafis legendaris dengan performa ray tracing superior.',
                'description' => 'NVIDIA GeForce RTX 3080 Founders Edition menghadirkan performa ultra yang didambakan para gamer, didukung oleh Ampere—arsitektur RTX generasi ke-2 NVIDIA. Dibangun dengan RT Core dan Tensor Core yang disempurnakan, multiprosesor streaming baru, dan memori G6X supercepat untuk pengalaman gaming yang luar biasa.',
                'price' => 12500000,
                'stock' => 0,
                'is_featured' => false,
                'is_active' => true,
                'specifications' => [
                    'GPU' => 'NVIDIA GeForce RTX 3080',
                    'Memory' => '10GB GDDR6X',
                    'Boost Clock' => '1.71 GHz',
                    'Cores' => '8704 CUDA Cores',
                ],
            ],
            [
                'category_id' => $consoles->id,
                'name' => 'PlayStation 5 Digital Edition (God of War Bundle)',
                'slug' => 'ps5-digital-edition-gow-bundle',
                'summary' => 'Console next-gen tanpa disk drive dengan game God of War Ragnarök.',
                'description' => 'Nikmati loading super cepat dengan SSD ultra-high speed, pengalaman imersif yang lebih dalam dengan dukungan haptic feedback, adaptif triggers, dan 3D Audio, serta generasi baru game PlayStation yang luar biasa. Bundle ini termasuk voucher game God of War Ragnarök.',
                'price' => 8199000,
                'stock' => 0,
                'is_featured' => false,
                'is_active' => true,
                'specifications' => [
                    'Storage' => '825GB SSD',
                    'Resolution' => 'Target 4K 60Hz',
                    'Disk Drive' => 'No',
                    'Game Included' => 'God of War Ragnarök',
                ],
            ],
            [
                'category_id' => $accessories->id,
                'name' => 'Keychron Q1 Pro Wireless Mechanical Keyboard',
                'slug' => 'keychron-q1-pro-wireless',
                'summary' => 'Keyboard mekanik custom premium dengan body aluminium CNC penuh.',
                'description' => 'Keychron Q1 Pro adalah keyboard mekanik custom nirkabel QMK/VIA pertama di dunia dengan body aluminium penuh. Dengan fitur excellent type-feel, suara thocky yang premium, dan konektivitas Bluetooth 5.1 yang stabil untuk multitasking.',
                'price' => 3200000,
                'stock' => 0,
                'is_featured' => true,
                'is_active' => true,
                'specifications' => [
                    'Body' => 'Full CNC Aluminum',
                    'Connectivity' => 'Bluetooth 5.1 & Type-C Wired',
                    'Battery' => '4000 mAh',
                    'Switch' => 'Keychron K Pro Red',
                ],
            ],
            [
                'category_id' => $accessories->id,
                'name' => 'Finalmouse Starlight-12 Poseidon',
                'slug' => 'finalmouse-starlight-12-poseidon',
                'summary' => 'Mouse gaming ultra-ringan legendaris dari magnesium alloy.',
                'description' => 'Finalmouse Starlight-12 Poseidon adalah mahakarya engineering. Dibuat dari magnesium ally dengan berat hanya 42 gram. Edisi terbatas ini memiliki finishing warna biru laut yang unik dan performa wireless tanpa lag.',
                'price' => 4500000,
                'stock' => 0,
                'is_featured' => false,
                'is_active' => true,
                'specifications' => [
                    'Weight' => '42g (Small) / 47g (Medium)',
                    'Material' => 'Magnesium Alloy',
                    'Sensor' => 'Finalsensor',
                    'Connectivity' => 'Wireless',
                ],
            ],
            [
                'category_id' => $hardware->id,
                'name' => 'AMD Ryzen 9 5950X',
                'slug' => 'amd-ryzen-9-5950x',
                'summary' => 'Prosesor desktop 16-core terbaik untuk gaming dan kreasi konten.',
                'description' => 'Ketika Anda memiliki arsitektur prosesor paling canggih di dunia untuk gamer dan pembuat konten, kemungkinannya tak terbatas. Baik Anda bermain game terbaru, merancang gedung pencakar langit berikutnya, atau mengolah data ilmiah, Anda memerlukan prosesor yang kuat yang dapat menangani semuanya.',
                'price' => 9500000,
                'stock' => 0,
                'is_featured' => false,
                'is_active' => true,
                'specifications' => [
                    'Cores/Threads' => '16/32',
                    'Base Clock' => '3.4 GHz',
                    'Boost Clock' => 'Up to 4.9 GHz',
                    'Socket' => 'AM4',
                ],
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}
