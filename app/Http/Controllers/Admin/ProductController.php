<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $productsQuery = Product::with('category', 'images');

        if ($search = $request->input('q')) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('summary', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        switch ($sortBy) {
            case 'category':
                $productsQuery->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                             ->orderBy('categories.name', $sortOrder)
                             ->select('products.*');
                break;
            case 'price':
                $productsQuery->orderBy('price', $sortOrder);
                break;
            case 'stock':
                $productsQuery->orderBy('stock', $sortOrder);
                break;
            case 'name':
                $productsQuery->orderBy('name', $sortOrder);
                break;
            default:
                $productsQuery->orderBy('created_at', $sortOrder);
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('admin.products.index', compact('products', 'sortBy', 'sortOrder'));
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

        // Parse specifications JSON if provided
        if (isset($data['specifications']) && is_string($data['specifications'])) {
            $data['specifications'] = json_decode($data['specifications'], true);
        }

        // Remove images from data array as we'll handle it separately
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product = Product::create($data);

        // Handle multiple images with resize/crop
        if ($request->hasFile('images')) {
            $this->processImages($product, $request->file('images'));
        }

        $logger->log('product.created', $product, [], $request->user());

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        // Parse specifications JSON if provided
        if (isset($data['specifications']) && is_string($data['specifications'])) {
            $data['specifications'] = json_decode($data['specifications'], true);
        }

        // Handle image deletion
        if ($request->has('delete_images')) {
            $deleteImages = $request->input('delete_images', []);
            foreach ($deleteImages as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }
        }

        // Remove images from data array
        $images = $data['images'] ?? [];
        unset($data['images'], $data['delete_images']);

        $product->update($data);

        // Handle new images
        if ($request->hasFile('images')) {
            $this->processImages($product, $request->file('images'));
        }

        $logger->log('product.updated', $product, [], $request->user());

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk diperbarui.');
    }

    public function destroy(Product $product, ActivityLogger $logger): RedirectResponse
    {
        // Delete all product images
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // Delete old image_path if exists
        if ($product->image_path && ! Str::startsWith($product->image_path, ['http://', 'https://']) && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        $logger->log('product.deleted', $product);

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Produk dihapus.');
    }

    /**
     * Process and store product images with resize/crop to square aspect ratio
     */
    private function processImages(Product $product, array $files): void
    {
        $manager = new ImageManager(new Driver());
        $existingCount = $product->images()->count();
        $order = $existingCount;

        foreach ($files as $file) {
            $order++;
            if ($order > 10) break; // Max 10 images

            try {
                // Read image
                $image = $manager->read($file->getRealPath());
                
                // Resize and crop to square (800x800) maintaining aspect ratio
                $image->cover(800, 800);

                // Save to storage
                $path = 'products/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                $fullPath = storage_path('app/public/' . $path);
                
                // Ensure directory exists
                $directory = dirname($fullPath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $image->save($fullPath);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'order' => $order,
                ]);
            } catch (\Exception $e) {
                // Log error but continue with other images
                \Log::error('Failed to process image: ' . $e->getMessage());
            }
        }
    }
}
