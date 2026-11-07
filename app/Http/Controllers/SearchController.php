<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $query = Product::with(['seller', 'category', 'brand', 'variants', 'media'])
            ->where('status', 'aktif');

        // Arama sorgusu: Ürün adı, açıklama veya marka adı
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$q}%"));
            });
        }

        // Kategori Filtresi (?category=2 veya ?kategori=elektronik)
        $categoryParam = $request->query('category') ?: $request->query('kategori');
        if ($categoryParam) {
            if (is_numeric($categoryParam)) {
                $query->where('category_id', $categoryParam);
            } else {
                $query->whereHas('category', fn ($c) => $c->where('slug', $categoryParam));
            }
        }

        // Marka Filtresi (?marka=sony veya ?brand=1)
        $brandParam = $request->query('marka') ?: $request->query('brand');
        if ($brandParam) {
            $query->where(function ($bQuery) use ($brandParam) {
                if (is_numeric($brandParam)) {
                    $bQuery->where('brand_id', $brandParam);
                } else {
                    $bQuery->whereHas('brand', fn ($b) => $b->where('slug', $brandParam)->orWhere('name', 'like', "%{$brandParam}%"));
                }
            });
        }

        // Fiyat Aralığı Filtresi (?min=100&max=500 veya ?min_price=100)
        $minPrice = $request->query('min') ?: $request->query('min_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->whereHas('variants', function ($v) use ($minPrice) {
                $v->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '>=', (float) $minPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '>=', (float) $minPrice));
                });
            });
        }

        $maxPrice = $request->query('max') ?: $request->query('max_price');
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->whereHas('variants', function ($v) use ($maxPrice) {
                $v->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '<=', (float) $maxPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '<=', (float) $maxPrice));
                });
            });
        }

        // Renk Filtresi (?renk=siyah veya ?color=siyah)
        $color = $request->query('renk') ?: $request->query('color');
        if ($color) {
            $query->whereHas('variants', function ($v) use ($color) {
                $v->where('color', 'like', "%{$color}%");
            });
        }

        // Beden Filtresi (?beden=M veya ?size=M)
        $size = $request->query('beden') ?: $request->query('size');
        if ($size) {
            $query->whereHas('variants', function ($v) use ($size) {
                $v->where('size', 'like', "%{$size}%");
            });
        }

        // Stok Filtresi (?stok=1 veya ?in_stock=1)
        if ($request->boolean('stok') || $request->boolean('in_stock')) {
            $query->whereHas('variants', function ($v) {
                $v->where('stock', '>', 0);
            });
        }

        // Sıralama (4 Mod)
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

        $products = $query->paginate(24)->withQueryString();

        // Filtre Listeleri (Aktif Ürünler Üzerinden)
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('sort')->get();
        $brands = Brand::orderBy('name')->take(20)->get();

        return view('search.index', compact('products', 'q', 'categories', 'brands', 'sort'));
    }
}
