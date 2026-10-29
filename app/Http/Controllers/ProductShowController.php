<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductShowController extends Controller
{
    /**
     * Display the specified product detail page.
     * Prevents N+1 queries by eager loading all relations.
     */
    public function show(string $slug): View
    {
        $product = Product::with([
            'seller.user',
            'category.parent',
            'brand',
            'variants',
            'media',
            'answeredQuestions.user',
        ])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Bonus 1: "Bu ürünü alanlar bunları da aldı" (Frequently Bought Together / Related Products)
        $relatedProducts = Product::with(['seller', 'variants', 'media', 'brand'])
            ->where('status', 'aktif')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Eğer aynı kategoride 4'ten az varsa diğer aktif ürünlerle tamamla
        if ($relatedProducts->count() < 4) {
            $fallback = Product::with(['seller', 'variants', 'media', 'brand'])
                ->where('status', 'aktif')
                ->whereNotIn('id', $relatedProducts->pluck('id')->push($product->id))
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($fallback);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
