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
            ->with('category', 'images')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::active()
            ->with('category', 'images')
            ->latest()
            ->take(6)
            ->get();

        return view('storefront.home', compact('categories', 'featuredProducts', 'newArrivals'));
    }

    public function category(Category $category): View
    {
        $products = $category->products()->active()->with('category', 'images')->paginate(12);

        return view('storefront.category', compact('category', 'products'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $relatedProducts = Product::active()
            ->with('category', 'images')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->take(4)
            ->get();

        $reviews = $product->approvedReviews()->with('user', 'adminRepliedBy')->latest()->take(10)->get();
        $canReview = auth()->check() && auth()->user()->orders()
            ->where('status', \App\Models\Order::STATUS_COMPLETED)
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists() && !$product->reviews()->where('user_id', auth()->id())->exists();

        $isInWishlist = auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        $product->load('variants', 'images');
        $variants = $product->variants;

        return view('storefront.product-show', compact('product', 'relatedProducts', 'reviews', 'canReview', 'isInWishlist', 'variants'));
    }

    public function catalog(Request $request): View
    {
        $categories = Category::active()->orderBy('name')->get();

        $productsQuery = Product::active()->with('category', 'images');

        if ($search = $request->input('q')) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $selectedCategory = null;
        
        // Filter collaboration harus dijalankan SEBELUM selectedCategory
        // karena ini mengubah kategori yang dipilih
        if ($request->has('collaboration') && $request->input('collaboration') == '1') {
            $collaborationCategory = Category::where('slug', 'collaboration-special-edition')->first();
            if ($collaborationCategory) {
                $productsQuery->where('category_id', $collaborationCategory->id);
                $selectedCategory = $collaborationCategory; // Set sebagai selectedCategory untuk UI
            }
        } elseif ($categorySlug = $request->input('category')) {
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
                'collaboration' => $request->has('collaboration') && $request->input('collaboration') == '1',
            ],
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $searchTerm = $request->input('q');
        $collaborationCategory = Category::where('slug', 'collaboration-special-edition')->first();

        $products = Product::active()
            ->with('images', 'category')
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('summary', 'like', '%'.$searchTerm.'%')
                    ->orWhere('description', 'like', '%'.$searchTerm.'%');
            })
            ->orderBy('name')
            ->take(8)
            ->get(['id', 'name', 'slug', 'price', 'category_id']);

        return response()->json($products->map(fn ($product) => [
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->formatted_price,
            'is_collaboration' => $collaborationCategory && $product->category_id === $collaborationCategory->id,
        ]));
    }
}
