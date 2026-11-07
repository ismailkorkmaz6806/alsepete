<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $seller = $request->user()->seller;

        $query = $seller->products()->with(['category', 'brand', 'variants']);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->query('q').'%');
        }

        if ($request->filled('status') && in_array($request->query('status'), ['aktif', 'pasif', 'onay_bekliyor', 'taslak'])) {
            $query->where('status', $request->query('status'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('seller.products.index', compact('products', 'seller'));
    }

    public function create(): View
    {
        $categories = Category::whereNotNull('parent_id')->orWhereDoesntHave('children')->orderBy('name')->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('name')->get();
        }
        $brands = Brand::orderBy('name')->get();

        return view('seller.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:50'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $c = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$c}";
            $c++;
        }

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'image_url' => $validated['image_url'] ?? 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
            'description' => $validated['description'] ?? '',
            'status' => 'aktif',
        ]);

        $sku = ! empty($validated['sku']) ? $validated['sku'] : strtoupper(Str::random(8));
        $color = $validated['color'] ?? null;
        $size = $validated['size'] ?? null;
        $discountPrice = $validated['discount_price'] ?? null;

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'color' => $color,
            'size' => $size,
            'price' => $validated['price'],
            'discount_price' => $discountPrice,
            'stock' => $validated['stock'],
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu ve mağazanızda yayına alındı.');
    }

    public function edit(Product $product): View
    {
        // Hafta 4 Kabul Kriteri: Satıcı A, Satıcı B'nin ürün düzenleme adresini elle yazarsa 403 alır.
        $this->authorize('update', $product);

        $categories = Category::whereNotNull('parent_id')->orWhereDoesntHave('children')->orderBy('name')->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('name')->get();
        }
        $brands = Brand::orderBy('name')->get();
        $primaryVariant = $product->variants->first();

        return view('seller.products.edit', compact('product', 'categories', 'brands', 'primaryVariant'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:1000'],
            'status' => ['required', 'in:aktif,pasif,taslak,onay_bekliyor'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:50'],
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'image_url' => $validated['image_url'] ?? $product->image_url,
            'description' => $validated['description'] ?? '',
            'status' => $validated['status'],
        ]);

        $sku = ! empty($validated['sku']) ? $validated['sku'] : strtoupper(Str::random(8));
        $color = $validated['color'] ?? null;
        $size = $validated['size'] ?? null;
        $discountPrice = $validated['discount_price'] ?? null;

        $variant = $product->variants->first();
        if ($variant) {
            $variant->update([
                'sku' => $sku,
                'color' => $color,
                'size' => $size,
                'price' => $validated['price'],
                'discount_price' => $discountPrice,
                'stock' => $validated['stock'],
            ]);
        } else {
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'color' => $color,
                'size' => $size,
                'price' => $validated['price'],
                'discount_price' => $discountPrice,
                'stock' => $validated['stock'],
            ]);
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Ürün bilgileri başarıyla güncellendi.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Ürün mağazanızdan başarıyla silindi.');
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $variant = $product->variants->first();
        if ($variant) {
            $variant->update(['stock' => (int) $request->input('stock')]);
        }

        return redirect()->back()->with('success', "{$product->name} ürününün stoğu {$request->input('stock')} olarak güncellendi.");
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $newStatus = ($product->status === 'aktif') ? 'pasif' : 'aktif';
        $product->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Ürün durumu '".strtoupper($newStatus)."' olarak değiştirildi.");
    }
}
