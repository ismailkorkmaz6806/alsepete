<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryShowController extends Controller
{
    /**
     * Display products for the given category with 24 items pagination and sorting.
     * Prevents N+1 queries by eager loading relations.
     */
    public function show(string $slug, Request $request): View
    {
        $category = Category::with(['children', 'parent'])->where('slug', $slug)->firstOrFail();

        // Kategori ve tum alt kategorilerinin ID listesi
        $categoryIds = $category->children->pluck('id')->push($category->id)->all();

        $query = Product::with([
            'seller',
            'category',
            'brand',
            'variants',
            'media',
        ])
            ->where('status', 'aktif')
            ->whereIn('category_id', $categoryIds);

        // Marka Filtresi
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->query('brand'));
        }

        // Fiyat Aralığı Filtresi
        if ($request->filled('min_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '>=', (float) $request->query('min_price'));
            });
        }
        if ($request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', (float) $request->query('max_price'));
            });
        }

        // Sadece Stoktakiler
        if ($request->boolean('in_stock')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sıralama (Hafta 3 Kabul Kriteri)
        $sort = $request->query('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy(
                ProductVariant::select('price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->limit(1),
                'asc'
            ),
            'price_desc' => $query->orderBy(
                ProductVariant::select('price')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->orderBy('price', 'desc')
                    ->limit(1),
                'desc'
            ),
            'rating' => $query->orderByDesc('id'),
            default => $query->orderByDesc('created_at'),
        };

        // 24 Urunluk Sayfalama (Hafta 3 Kabul Kriteri)
        $products = $query->paginate(24)->withQueryString();

        // Bu kategorideki ürünlerin markaları (filtreleme için)
        $brandIds = Product::whereIn('category_id', $categoryIds)->whereNotNull('brand_id')->distinct()->pluck('brand_id');
        $brands = Brand::whereIn('id', $brandIds)->orderBy('name')->get();

        return view('categories.show', compact('category', 'products', 'brands', 'sort'));
    }
}
