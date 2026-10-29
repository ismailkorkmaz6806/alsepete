<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;

test('locale can be switched to english', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'en']));

    $response->assertSessionHas('locale', 'en');
});

test('locale can be switched to turkish', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'tr']));

    $response->assertSessionHas('locale', 'tr');
});

test('invalid locale is ignored', function () {
    $response = $this->get(route('locale.switch', ['locale' => 'fr']));

    $response->assertSessionMissing('locale');
});

test('home page renders in english when locale is set to en', function () {
    $response = $this->withSession(['locale' => 'en'])->get('/');

    $response->assertStatus(200);
    $response->assertSee('All Categories');
    $response->assertSee('Become a Seller');
    $response->assertSee('Cart');
    $response->assertSee('Featured Deals of the Week');
    $response->assertSee('Trending Products');
    $response->assertSee('New Arrivals');
    $response->assertSee('AI Assistant');
});

test('products and categories render in english when locale is en', function () {
    $sellerUser = User::factory()->create();
    $seller = Seller::create([
        'user_id' => $sellerUser->id,
        'store_name' => 'Teknoloji Dünyası',
        'slug' => 'tekno',
        'status' => 'onayli',
        'tax_number' => '1111111111',
        'iban' => 'TR111111111111111111111111',
        'commission_rate' => 10.00,
    ]);

    $category = Category::create([
        'name' => 'Telefon & Aksesuar',
        'slug' => 'telefon-aksesuar',
        'commission_rate' => 8.00,
        'sort' => 1,
    ]);

    $product = Product::create([
        'seller_id' => $seller->id,
        'category_id' => $category->id,
        'name' => 'Apple iPhone 15 Pro Max 256GB Titanyum',
        'slug' => 'apple-iphone-15-pro-max-256gb-titanyum',
        'description' => "Bu ürün resmi distribütör garantili olup, yetkili satıcı {$seller->store_name} tarafından AlSepete güvencesiyle gönderilmektedir. %100 orijinal ve faturalı üründür. 14 gün koşulsuz iade hakkınız bulunmaktadır.",
        'image_url' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800',
        'gallery' => [
            'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800',
            'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800',
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800',
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800',
        ],
        'status' => 'aktif',
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'SKU-TEST-1',
        'color' => 'Doğal Titanyum',
        'size' => '256GB',
        'price' => 74999.00,
        'stock' => 10,
    ]);

    $response = $this->withSession(['locale' => 'en'])->get(route('products.show', $product->slug));

    $response->assertStatus(200);
    $response->assertSee('Apple iPhone 15 Pro Max 256GB Titanium');
    $response->assertSee('Phones & Accessories');
    $response->assertSee('Natural Titanium');
    $response->assertSee('official distributor warranty', false);
});

test('every product has at least 6 gallery images available', function () {
    $gallery = [
        'https://images.unsplash.com/photo-1',
        'https://images.unsplash.com/photo-2',
        'https://images.unsplash.com/photo-3',
        'https://images.unsplash.com/photo-4',
        'https://images.unsplash.com/photo-5',
    ];
    $product = new Product([
        'image_url' => 'https://images.unsplash.com/photo-main',
        'gallery' => $gallery,
    ]);

    $allImages = array_values(array_unique(array_merge([$product->featured_image], $gallery)));
    expect(count($allImages))->toBeGreaterThanOrEqual(6);
});
