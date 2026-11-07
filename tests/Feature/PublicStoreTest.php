<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;

beforeEach(function () {
    $this->user1 = User::factory()->create();
    $this->seller1 = Seller::create([
        'user_id' => $this->user1->id,
        'store_name' => 'Mega Trend Magaza',
        'slug' => 'mega-trend-magaza',
        'status' => 'onayli',
        'tax_number' => '1111111111',
        'iban' => 'TR111111111111111111111111',
        'commission_rate' => 10.00,
    ]);

    $this->user2 = User::factory()->create();
    $this->seller2 = Seller::create([
        'user_id' => $this->user2->id,
        'store_name' => 'Baska Magaza',
        'slug' => 'baska-magaza',
        'status' => 'onayli',
        'tax_number' => '2222222222',
        'iban' => 'TR222222222222222222222222',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Moda & Giyim',
        'slug' => 'moda-giyim',
        'commission_rate' => 10.00,
        'sort' => 1,
    ]);

    // Seller 1 Aktif Ürün
    $this->p1 = Product::create([
        'seller_id' => $this->seller1->id,
        'category_id' => $this->category->id,
        'name' => 'Mega Trend Deri Ceket',
        'slug' => 'mega-trend-deri-ceket',
        'status' => 'aktif',
    ]);
    ProductVariant::create([
        'product_id' => $this->p1->id,
        'sku' => 'MGT-01',
        'price' => 2500.00,
        'stock' => 10,
    ]);

    // Seller 1 Pasif Ürün
    $this->p1Pasif = Product::create([
        'seller_id' => $this->seller1->id,
        'category_id' => $this->category->id,
        'name' => 'Eski Sezon Pasif Mont',
        'slug' => 'eski-sezon-pasif-mont',
        'status' => 'pasif',
    ]);
    ProductVariant::create([
        'product_id' => $this->p1Pasif->id,
        'sku' => 'MGT-02',
        'price' => 1500.00,
        'stock' => 5,
    ]);

    // Seller 2 Ürünü
    $this->p2 = Product::create([
        'seller_id' => $this->seller2->id,
        'category_id' => $this->category->id,
        'name' => 'Baska Magazanin Urunu',
        'slug' => 'baska-magazanin-urunu',
        'status' => 'aktif',
    ]);
    ProductVariant::create([
        'product_id' => $this->p2->id,
        'sku' => 'BSK-01',
        'price' => 3000.00,
        'stock' => 5,
    ]);
});

test('herkese acik magaza sayfasi /magaza/{slug} yalnizca o saticinin aktif urunlerini gosterir', function () {
    $response = $this->get(route('stores.show', $this->seller1->slug));

    $response->assertStatus(200);
    $response->assertSee('Mega Trend Magaza');
    $response->assertSee('Mega Trend Deri Ceket');
    $response->assertDontSee('Eski Sezon Pasif Mont');
    $response->assertDontSee('Baska Magazanin Urunu');
});
