<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreShowController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $seller = Seller::with(['user'])->where('slug', $slug)->firstOrFail();

        $query = $seller->products()
            ->with(['category', 'brand', 'variants', 'media'])
            ->where('status', 'aktif');

        // Kategori Filtresi
        if ($request->filled('category')) {
            $query->where('category_id', $request->query('category'));
        }

        // Marka Filtresi
        if ($request->filled('brand') || $request->filled('marka')) {
            $brandParam = $request->query('brand') ?: $request->query('marka');
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
        if ($minPrice) {
            $query->whereHas('variants', function ($q) use ($minPrice) {
                $q->where(function ($sub) use ($minPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '>=', (float) $minPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '>=', (float) $minPrice));
                });
            });
        }

        $maxPrice = $request->query('max') ?: $request->query('max_price');
        if ($maxPrice) {
            $query->whereHas('variants', function ($q) use ($maxPrice) {
                $q->where(function ($sub) use ($maxPrice) {
                    $sub->whereNotNull('discount_price')->where('discount_price', '<=', (float) $maxPrice)
                        ->orWhere(fn ($p) => $p->whereNull('discount_price')->where('price', '<=', (float) $maxPrice));
                });
            });
        }

        // Renk Filtresi (?renk=Siyah veya ?color=Siyah)
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

        // Stok Filtresi (?stok=1 veya ?in_stock=1)
        if ($request->boolean('in_stock') || $request->boolean('stok')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sıralama
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

        // Filtre seçenekleri
        $sellerProductIds = $seller->products()->where('status', 'aktif')->pluck('id');
        $categoryIds = $seller->products()->where('status', 'aktif')->distinct()->pluck('category_id');
        $categories = Category::whereIn('id', $categoryIds)->orderBy('name')->get();

        $brandIds = $seller->products()->where('status', 'aktif')->whereNotNull('brand_id')->distinct()->pluck('brand_id');
        $brands = Brand::whereIn('id', $brandIds)->orderBy('name')->get();

        return view('stores.show', compact('seller', 'products', 'categories', 'brands', 'sort'));
    }
}
