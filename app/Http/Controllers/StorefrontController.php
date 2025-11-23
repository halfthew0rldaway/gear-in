<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(): View
    {
        $categories = Category::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::active()
            ->with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::active()
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        return view('storefront.home', compact('categories', 'featuredProducts', 'newArrivals'));
    }

    public function category(Category $category): View
    {
        $products = $category->products()->active()->with('category')->paginate(12);

        return view('storefront.category', compact('category', 'products'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->take(4)
            ->get();

        return view('storefront.product-show', compact('product', 'relatedProducts'));
    }

    public function catalog(Request $request): View
    {
        $categories = Category::active()->orderBy('name')->get();

        $productsQuery = Product::active()->with('category');

        if ($search = $request->input('q')) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $selectedCategory = null;
        if ($categorySlug = $request->input('category')) {
            $selectedCategory = $categories->firstWhere('slug', $categorySlug);
            if ($selectedCategory) {
                $productsQuery->where('category_id', $selectedCategory->id);
            }
        }

        if ($request->filled('min_price')) {
            $productsQuery->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $productsQuery->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->boolean('in_stock')) {
            $productsQuery->where('stock', '>', 0);
        }

        if ($request->boolean('featured')) {
            $productsQuery->where('is_featured', true);
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        return view('storefront.catalog', [
            'products' => $products,
            'categories' => $categories,
            'searchQuery' => $search,
            'selectedCategory' => $selectedCategory,
            'filters' => [
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'in_stock' => $request->boolean('in_stock'),
                'featured' => $request->boolean('featured'),
            ],
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $products = Product::active()
            ->where('name', 'like', '%'.$request->input('q').'%')
            ->orderBy('name')
            ->take(5)
            ->get(['id', 'name', 'slug', 'price']);

        return response()->json($products->map(fn ($product) => [
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->formatted_price,
        ]));
    }
}
