<?php

use App\Livewire\ProductDetail;
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
        'store_name' => 'Livewire Magaza',
        'slug' => 'livewire-magaza',
        'status' => 'onayli',
        'tax_number' => '3333333333',
        'iban' => 'TR333333333333333333333333',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Giyim Test',
        'slug' => 'giyim-test',
        'commission_rate' => 12.00,
        'sort' => 1,
    ]);

    $this->product = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'name' => 'Klasik Sweatshirt',
        'slug' => 'klasik-sweatshirt',
        'description' => 'Test sweatshirt',
        'status' => 'aktif',
    ]);

    $this->variant1 = ProductVariant::create([
        'product_id' => $this->product->id,
        'sku' => 'SW-S-BLACK',
        'color' => 'Siyah',
        'size' => 'S',
        'price' => 500.00,
        'stock' => 10,
    ]);

    $this->variant2 = ProductVariant::create([
        'product_id' => $this->product->id,
        'sku' => 'SW-M-RED',
        'color' => 'Kırmızı',
        'size' => 'M',
        'price' => 550.00,
        'discount_price' => 499.00,
        'stock' => 5,
    ]);
});

test('product detail livewire component mounts with default variant', function () {
    Livewire::test(ProductDetail::class, ['product' => $this->product])
        ->assertSet('selectedVariantId', $this->variant1->id)
        ->assertSet('selectedColor', 'Siyah')
        ->assertSet('selectedSize', 'S')
        ->assertSee('500,00 TL')
        ->assertSee('Stokta Var (10 Adet)');
});

test('product detail livewire component reacts to color and size selection without page reload', function () {
    Livewire::test(ProductDetail::class, ['product' => $this->product])
        ->call('selectColor', 'Kırmızı')
        ->assertSet('selectedColor', 'Kırmızı')
        ->assertSet('selectedVariantId', $this->variant2->id)
        ->assertSee('499,00 TL')
        ->assertSee('550,00 TL');
});

test('product detail livewire increments and decrements quantity within stock limits', function () {
    Livewire::test(ProductDetail::class, ['product' => $this->product])
        ->assertSet('quantity', 1)
        ->call('incrementQuantity')
        ->assertSet('quantity', 2)
        ->call('decrementQuantity')
        ->assertSet('quantity', 1)
        ->call('decrementQuantity')
        ->assertSet('quantity', 1); // Minimum 1 olmali
});

test('product detail livewire can add product to cart', function () {
    Livewire::test(ProductDetail::class, ['product' => $this->product])
        ->call('addToCart')
        ->assertSet('addedToCart', true)
        ->assertSee('Ürün başarıyla sepete eklendi!');
});
