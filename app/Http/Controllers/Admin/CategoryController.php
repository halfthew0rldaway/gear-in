<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->latest()->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);

        $logger->log('category.created', $category, [], $request->user());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category, ActivityLogger $logger): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        $logger->log('category.updated', $category, [], $request->user());

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori diperbarui.');
    }

    public function destroy(Category $category, ActivityLogger $logger): RedirectResponse
    {
        $category->delete();

        $logger->log('category.deleted', $category, []);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', 'Kategori dihapus.');
    }
}
