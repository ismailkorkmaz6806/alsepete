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
        'store_name' => 'Kategori Test Magaza',
        'slug' => 'kategori-test-magaza',
        'status' => 'onayli',
        'tax_number' => '2222222222',
        'iban' => 'TR222222222222222222222222',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Elektronik Test',
        'slug' => 'elektronik-test',
        'commission_rate' => 8.00,
        'sort' => 1,
    ]);

    $this->brandA = Brand::create(['name' => 'Sony', 'slug' => 'sony']);
    $this->brandB = Brand::create(['name' => 'Samsung', 'slug' => 'samsung']);

    // 26 adet ürün oluştur (24'lük sayfalamayı test etmek için)
    for ($i = 1; $i <= 26; $i++) {
        $isBrandA = ($i % 2 === 0);
        $prefix = $isBrandA ? 'Alpha' : 'Beta';

        $p = Product::create([
            'seller_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'brand_id' => $isBrandA ? $this->brandA->id : $this->brandB->id,
            'name' => "{$prefix} Urun {$i}",
            'slug' => strtolower("{$prefix}-urun-{$i}"),
            'description' => "Test aciklamasi {$i}",
            'status' => 'aktif',
        ]);

        ProductVariant::create([
            'product_id' => $p->id,
            'sku' => "SKU-TEST-{$i}",
            'price' => $i * 100.00,
            'stock' => ($i === 26) ? 0 : 10,
        ]);
    }
});

test('user can browse category page and see 24 items pagination', function () {
    $response = $this->get(route('categories.show', $this->category->slug));

    $response->assertStatus(200);
    $response->assertSee('Elektronik Test');
    $response->assertSee('26 Ürün');
    // İlk sayfada 24 ürün olmalı
    $response->assertSee('Beta Urun 1');
    $response->assertSee('Alpha Urun 24');
    // Sayfalama bağlantısı olmalı (Page 2)
    $response->assertSee('page=2');
});

test('user can sort category products by price ascending', function () {
    $response = $this->get(route('categories.show', ['slug' => $this->category->slug, 'sort' => 'price_asc']));

    $response->assertStatus(200);
    // En ucuz olan (Beta Urun 1 = 100 TL) ilk sıralarda olmalı
    $response->assertSee('100,00 TL');
});

test('user can filter category products by brand', function () {
    $response = $this->get(route('categories.show', ['slug' => $this->category->slug, 'brand' => $this->brandA->id]));

    $response->assertStatus(200);
    // Sadece Brand A ürünleri (Alpha Urun)
    $response->assertSee('Alpha Urun 2');
    $response->assertDontSee('Beta Urun 1');
});

test('user can filter category products by in_stock', function () {
    $response = $this->get(route('categories.show', ['slug' => $this->category->slug, 'in_stock' => 1]));

    $response->assertStatus(200);
    // Ürün 26'nın stoğu 0 olduğu için listelenmemeli
    $response->assertDontSee('Alpha Urun 26');
});
