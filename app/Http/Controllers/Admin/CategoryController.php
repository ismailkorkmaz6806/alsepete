<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Kategorileri hiyerarşik listeler.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::with('parent')
            ->orderByRaw('CASE WHEN parent_id IS NULL THEN id ELSE parent_id END, parent_id NULLS FIRST, sort ASC')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Yeni kategori ekleme formu.
     */
    public function create(): View
    {
        $this->authorize('create', Category::class);

        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Yeni kategori kaydeder.
     */
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action): RedirectResponse
    {
        $action->execute($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Kategori düzenleme formu.
     */
    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Kategoriyi günceller.
     */
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): RedirectResponse
    {
        $action->execute($category, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Kategoriyi siler.
     */
    public function destroy(Category $category, DeleteCategoryAction $action): RedirectResponse
    {
        $this->authorize('delete', $category);

        $action->execute($category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }
}
