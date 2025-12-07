<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware',
                'description' => 'Rig gaming performa tinggi, motherboard, dan komponen inti.',
            ],
            [
                'name' => 'Accessories',
                'description' => 'Periferal minimalis untuk productivity dan competitive gaming.',
            ],
            [
                'name' => 'Games',
                'description' => 'Koleksi game original pilihan untuk PC dan console.',
            ],
            [
                'name' => 'Consoles',
                'description' => 'Gaming console dan aksesoris lengkap untuk gaming experience terbaik.',
            ],
            [
                'name' => 'Digital Products',
                'description' => 'Software licenses, activation keys, dan produk digital lainnya.',
            ],
            [
                'name' => 'Collaboration & Special Edition',
                'description' => 'Produk limited edition hasil kolaborasi dengan brand, pro players, VTubers, dan franchise populer.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                ],
            );
        }
    }
}
