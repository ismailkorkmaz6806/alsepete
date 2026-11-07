<?php

use App\Livewire\SearchDropdown;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->sellerUser = User::factory()->create();
    $this->seller = Seller::create([
        'user_id' => $this->sellerUser->id,
        'store_name' => 'Arama Test Magaza',
        'slug' => 'arama-test-magaza',
        'status' => 'onayli',
        'tax_number' => '1234567890',
        'iban' => 'TR123456789012345678901234',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Kulakliklar',
        'slug' => 'kulakliklar',
        'commission_rate' => 8.00,
        'sort' => 1,
    ]);

    $this->brandSony = Brand::create(['name' => 'Sony', 'slug' => 'sony']);
    $this->brandApple = Brand::create(['name' => 'Apple', 'slug' => 'apple']);

    // 1. Sony Kulaklık
    $this->productSony = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'brand_id' => $this->brandSony->id,
        'name' => 'Sony WH-1000XM5 Kablosuz Kulaklik',
        'slug' => 'sony-wh-1000xm5-kablosuz-kulaklik',
        'description' => 'Gürültü engelleyici efsane kulaklık',
        'status' => 'aktif',
    ]);
    ProductVariant::create([
        'product_id' => $this->productSony->id,
        'sku' => 'SNY-WH-1',
        'color' => 'Siyah',
        'size' => 'Standart',
        'price' => 12000.00,
        'discount_price' => 10999.00,
        'stock' => 15,
    ]);

    // 2. Apple AirPods
    $this->productApple = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'brand_id' => $this->brandApple->id,
        'name' => 'Apple AirPods Pro 2',
        'slug' => 'apple-airpods-pro-2',
        'description' => 'Magsafe şarj kutulu kablosuz kulak içi',
        'status' => 'aktif',
    ]);
    ProductVariant::create([
        'product_id' => $this->productApple->id,
        'sku' => 'APL-APP-2',
        'color' => 'Beyaz',
        'size' => 'Standart',
        'price' => 8000.00,
        'discount_price' => null,
        'stock' => 5,
    ]);
});

test('SearchDropdown Livewire bileseni arama yaparken anlik autocomplete onerisi dondurur', function () {
    Livewire::test(SearchDropdown::class)
        ->set('query', 'Sony')
        ->assertSee('Sony WH-1000XM5')
        ->assertSee('Sony');
});

test('Arama sayfasi /arama anahtar kelime q ile filtreler', function () {
    $response = $this->get(route('search', ['q' => 'AirPods']));

    $response->assertStatus(200);
    $response->assertSee('Apple AirPods Pro 2');
    $response->assertDontSee('Sony WH-1000XM5');
});

test('Arama ve Kategori sayfalari URL parametreli marka ve fiyat filtrelerini dogru calistirir', function () {
    // Sony markasını filtrele
    $response = $this->get(route('search', ['marka' => 'sony', 'min' => 10000]));

    $response->assertStatus(200);
    $response->assertSee('Sony WH-1000XM5');
    $response->assertDontSee('Apple AirPods Pro 2');
});

test('Renk ve Beden URL filtreleri varyant bazinda sonuclari suzer', function () {
    // Siyah renk filtrele
    $response = $this->get(route('search', ['renk' => 'Siyah']));
    $response->assertStatus(200);
    $response->assertSee('Sony WH-1000XM5');
    $response->assertDontSee('Apple AirPods Pro 2');

    // Beyaz renk filtrele
    $responseWhite = $this->get(route('search', ['renk' => 'Beyaz']));
    $responseWhite->assertStatus(200);
    $responseWhite->assertSee('Apple AirPods Pro 2');
    $responseWhite->assertDontSee('Sony WH-1000XM5');
});

test('Hafta 4 Kabul Kriteri: Filtreli bir sayfanin linki paylasilinca ayni sonuclar gelir', function () {
    $sharedUrl = route('search', [
        'marka' => 'apple',
        'renk' => 'Beyaz',
        'min' => 5000,
        'max' => 9000,
        'stok' => 1,
    ]);

    $response = $this->get($sharedUrl);

    $response->assertStatus(200);
    $response->assertSee('Apple AirPods Pro 2');
    $response->assertDontSee('Sony WH-1000XM5');
});
