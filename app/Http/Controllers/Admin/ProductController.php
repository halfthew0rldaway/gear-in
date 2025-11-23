<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sku'] = strtoupper(Str::random(8));

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        $logger->log('product.created', $product, [], $request->user());

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            if ($product->image_path && ! Str::startsWith($product->image_path, ['http://', 'https://']) && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        $logger->log('product.updated', $product, [], $request->user());

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk diperbarui.');
    }

    public function destroy(Product $product, ActivityLogger $logger): RedirectResponse
    {
        if ($product->image_path && ! Str::startsWith($product->image_path, ['http://', 'https://']) && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        $logger->log('product.deleted', $product);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk dihapus.');
    }
}
