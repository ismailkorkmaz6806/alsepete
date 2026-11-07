<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\Services\InstallmentService;

test('InstallmentService anlasmali banka aileleri icin taksit plani uretir', function () {
    $service = new InstallmentService;
    $plans = $service->getInstallmentPlans(1000.00);

    expect($plans)->toBeArray()->not->toBeEmpty();
    $cardNames = array_column($plans, 'name');
    expect($cardNames)->toContain('Bonus', 'Maximum', 'World', 'Axess');

    $bonus = collect($plans)->firstWhere('name', 'Bonus');
    expect($bonus['table'])->toBeArray();
    expect($bonus['table'][0]['installment'])->toBe(1);
    expect($bonus['table'][0]['total'])->toBe(1000.00);
});

test('urun detay sayfasinda taksit secenekleri butonu ve iyzico tablosu render edilir', function () {
    $user = User::factory()->create();
    $seller = Seller::create([
        'user_id' => $user->id,
        'store_name' => 'Taksit Test Magaza',
        'slug' => 'taksit-test-magaza',
        'status' => 'onayli',
        'tax_number' => '1234567890',
        'iban' => 'TR123456789012345678901234',
        'commission_rate' => 10.00,
    ]);

    $category = Category::create([
        'name' => 'Elektronik Taksit Test',
        'slug' => 'elektronik-taksit-test',
        'commission_rate' => 10.00,
        'sort' => 1,
    ]);

    $product = Product::create([
        'seller_id' => $seller->id,
        'category_id' => $category->id,
        'name' => 'Taksitli Akilli Saat',
        'slug' => 'taksitli-akilli-saat',
        'status' => 'aktif',
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'SAAT-01',
        'price' => 3500.00,
        'stock' => 10,
    ]);

    $response = $this->get(route('products.show', $product->slug));
    $response->assertStatus(200);
    $response->assertSee('Taksit Seçenekleri');
    $response->assertSee('Garanti BBVA');
    $response->assertSee('Bonus');
});
