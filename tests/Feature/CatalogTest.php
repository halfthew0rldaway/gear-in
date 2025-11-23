<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->category = Category::create([
        'name' => 'Hardware',
        'slug' => Str::slug('Hardware'),
        'is_active' => true,
    ]);

    $this->otherCategory = Category::create([
        'name' => 'Games',
        'slug' => Str::slug('Games'),
        'is_active' => true,
    ]);

    Product::create([
        'category_id' => $this->category->id,
        'name' => 'Gear-In Silent Keyboard',
        'slug' => Str::slug('Gear-In Silent Keyboard'),
        'sku' => 'SKU-KEY001',
        'price' => 1500000,
        'stock' => 5,
        'is_active' => true,
    ]);

    Product::create([
        'category_id' => $this->otherCategory->id,
        'name' => 'Adventure Game Key',
        'slug' => Str::slug('Adventure Game Key'),
        'sku' => 'SKU-GAME001',
        'price' => 500000,
        'stock' => 10,
        'is_active' => true,
    ]);
});

it('filters catalog by search query', function () {
    $response = $this->get('/catalog?q=keyboard');

    $response->assertStatus(200);
    $response->assertSee('Gear-In Silent Keyboard');
    $response->assertDontSee('Adventure Game Key');
});

it('filters catalog by category parameter', function () {
    $response = $this->get('/catalog?category='.$this->otherCategory->slug);

    $response->assertStatus(200);
    $response->assertSee('Adventure Game Key');
    $response->assertDontSee('Gear-In Silent Keyboard');
});

it('returns suggestions for search api', function () {
    $response = $this->getJson('/catalog/search?q=gear');

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Gear-In Silent Keyboard']);
});

