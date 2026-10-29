<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;

beforeEach(function () {
    $this->sellerUser = User::factory()->create();
    $this->seller = Seller::create([
        'user_id' => $this->sellerUser->id,
        'store_name' => 'Test Magaza',
        'slug' => 'test-magaza',
        'status' => 'onayli',
        'tax_number' => '1111111111',
        'iban' => 'TR111111111111111111111111',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Bilgisayar',
        'slug' => 'bilgisayar',
        'commission_rate' => 8.00,
        'sort' => 1,
    ]);

    $this->brand = Brand::create([
        'name' => 'Apple',
        'slug' => 'apple',
    ]);

    $this->product = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
        'name' => 'MacBook Pro M3 Max',
        'slug' => 'macbook-pro-m3-max',
        'description' => 'Guclu performans dizustu bilgisayar.',
        'status' => 'aktif',
    ]);

    ProductVariant::create([
        'product_id' => $this->product->id,
        'sku' => 'MBP-M3-SILVER',
        'color' => 'Gümüş',
        'size' => '512GB',
        'price' => 75000.00,
        'discount_price' => 69999.00,
        'stock' => 15,
    ]);
});

test('guest can view product detail page via SEO slug', function () {
    $response = $this->get(route('products.show', $this->product->slug));

    $response->assertStatus(200);
    $response->assertSee('MacBook Pro M3 Max');
    $response->assertSee('Test Magaza');
    $response->assertSee('Apple');
    $response->assertSee('69.999,00 TL');
});

test('inactive or missing product returns 404', function () {
    $this->product->update(['status' => 'pasif']);

    $response = $this->get(route('products.show', $this->product->slug));
    $response->assertStatus(404);

    $missingResponse = $this->get(route('products.show', 'olmayan-urun'));
    $missingResponse->assertStatus(404);
});

test('product detail renders recommendations section', function () {
    // Aynı kategoride başka bir ürün oluştur
    $otherProduct = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'name' => 'Magic Mouse Black',
        'slug' => 'magic-mouse-black',
        'description' => 'Kablosuz fare.',
        'status' => 'aktif',
    ]);

    ProductVariant::create([
        'product_id' => $otherProduct->id,
        'sku' => 'MM-BLK',
        'price' => 3500.00,
        'stock' => 20,
    ]);

    $response = $this->get(route('products.show', $this->product->slug));
    $response->assertStatus(200);
    $response->assertSee('Bu Ürünü Alanlar Bunları da Aldı');
    $response->assertSee('Magic Mouse Black');
});
