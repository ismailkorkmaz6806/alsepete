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

        // Marka Filtresi (?marka=sony veya ?brand=1)
        $brandParam = $request->query('marka') ?: $request->query('brand');
        if ($brandParam) {
            $query->where(function ($q) use ($brandParam) {
                if (is_numeric($brandParam)) {
                    $q->where('brand_id', $brandParam);
                } else {
                    $q->whereHas('brand', fn ($b) => $b->where('slug', $brandParam)->orWhere('name', 'like', "%{$brandParam}%"));
                }
            });
        }

        // Fiyat Aralığı Filtresi (?min=100&max=500 veya ?min_price=100)
        $minPrice = $request->query('min') ?: $request->query('min_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->whereHas('variants', function ($q) use ($minPrice) {
                $q->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '>=', (float) $minPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '>=', (float) $minPrice));
                });
            });
        }
        $maxPrice = $request->query('max') ?: $request->query('max_price');
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->whereHas('variants', function ($q) use ($maxPrice) {
                $q->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '<=', (float) $maxPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '<=', (float) $maxPrice));
                });
            });
        }

        // Renk Filtresi (?renk=siyah veya ?color=siyah)
        $color = $request->query('renk') ?: $request->query('color');
        if ($color) {
            $query->whereHas('variants', function ($q) use ($color) {
                $q->where('color', 'like', "%{$color}%");
            });
        }

        // Beden Filtresi (?beden=M veya ?size=M)
        $size = $request->query('beden') ?: $request->query('size');
        if ($size) {
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', 'like', "%{$size}%");
            });
        }

        // Sadece Stoktakiler (?stok=1 veya ?in_stock=1)
        if ($request->boolean('in_stock') || $request->boolean('stok')) {
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
