<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_slug' => 'hardware',
                'name' => 'Gear-In Core One',
                'summary' => 'Desktop PC minimalis dengan RTX 4070 dan casing matte hitam.',
                'description' => 'Setup siap pakai dengan fokus pada airflow optimal dan estetika monokrom.',
                'price' => 28999000,
                'stock' => 8,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'hardware',
                'name' => 'Gear-In Silent Mech Keyboard',
                'summary' => 'Keyboard TKL low-noise dengan switch pre-lube.',
                'description' => 'Housing aluminium, keycaps PBT dye-sub, cocok untuk workstation elegan.',
                'price' => 2499000,
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Gear-In Void Wireless Headset',
                'summary' => 'Headset wireless 40h battery dengan ANC adaptif.',
                'description' => 'Driver 40mm custom-tuned dengan sound signature seimbang.',
                'price' => 1999000,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'category_slug' => 'accessories',
                'name' => 'Gear-In Precision Mouse',
                'summary' => 'Mouse 65g dengan sensor 26k DPI dan coating matte.',
                'price' => 1499000,
                'stock' => 40,
            ],
            [
                'category_slug' => 'games',
                'name' => 'Ori Digital Deluxe',
                'summary' => 'Bundle game platformer artistik edisi digital.',
                'price' => 499000,
                'stock' => 50,
            ],
            [
                'category_slug' => 'games',
                'name' => 'Forza Horizon Premium Key',
                'summary' => 'Kode original Forza Horizon dengan DLC expansion.',
                'price' => 1299000,
                'stock' => 35,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::whereSlug($productData['category_slug'])->first();

            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['name' => $productData['name']],
                array_merge(
                    Arr::except($productData, ['category_slug']),
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($productData['name']),
                        'sku' => $productData['sku'] ?? strtoupper(Str::random(8)),
                        'image_path' => $productData['image_path'] ?? 'https://placehold.co/800x800/111111/FFFFFF?text=gear-in',
                        'is_active' => true,
                    ],
                )
            );
        }
    }
}
