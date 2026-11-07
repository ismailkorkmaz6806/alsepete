<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'seller']);
    Role::firstOrCreate(['name' => 'customer']);

    // Satıcı A
    $this->userA = User::factory()->create();
    $this->userA->assignRole('seller');
    $this->sellerA = Seller::create([
        'user_id' => $this->userA->id,
        'store_name' => 'Magaza Alpha',
        'slug' => 'magaza-alpha',
        'status' => 'onayli',
        'tax_number' => '1111111111',
        'iban' => 'TR111111111111111111111111',
        'commission_rate' => 10.00,
    ]);

    // Satıcı B
    $this->userB = User::factory()->create();
    $this->userB->assignRole('seller');
    $this->sellerB = Seller::create([
        'user_id' => $this->userB->id,
        'store_name' => 'Magaza Beta',
        'slug' => 'magaza-beta',
        'status' => 'onayli',
        'tax_number' => '2222222222',
        'iban' => 'TR222222222222222222222222',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Test Kategori',
        'slug' => 'test-kategori',
        'commission_rate' => 10.00,
        'sort' => 1,
    ]);

    // Satıcı B'ye ait bir ürün
    $this->productB = Product::create([
        'seller_id' => $this->sellerB->id,
        'category_id' => $this->category->id,
        'name' => 'Beta Ozel Urun',
        'slug' => 'beta-ozel-urun',
        'description' => 'Sadece B ye ait',
        'status' => 'aktif',
    ]);

    $this->variantB = ProductVariant::create([
        'product_id' => $this->productB->id,
        'sku' => 'BETA-SKU-1',
        'price' => 500.00,
        'stock' => 20,
    ]);
});

test("Hafta 4 Kabul Kriteri: Satici A, Satici B'nin urun duzenleme adresini elle yazarsa 403 alir", function () {
    $response = $this->actingAs($this->userA)
        ->get(route('seller.products.edit', $this->productB));

    $response->assertStatus(403);
});

test("Satici A, Satici B'nin urununu PUT istegi ile guncellemeye calisirsa 403 alir", function () {
    $response = $this->actingAs($this->userA)
        ->put(route('seller.products.update', $this->productB), [
            'name' => 'Hacked Name',
            'category_id' => $this->category->id,
            'price' => 10.00,
            'stock' => 100,
            'status' => 'aktif',
        ]);

    $response->assertStatus(403);
    $this->assertDatabaseHas('products', [
        'id' => $this->productB->id,
        'name' => 'Beta Ozel Urun',
    ]);
});

test("Satici A, Satici B'nin urununu DELETE ile silmeye calisirsa 403 alir", function () {
    $response = $this->actingAs($this->userA)
        ->delete(route('seller.products.destroy', $this->productB));

    $response->assertStatus(403);
    $this->assertDatabaseHas('products', ['id' => $this->productB->id]);
});

test("Satici A, Satici B'nin urun stok veya durumunu degistirmeye calisirsa 403 alir", function () {
    $resStock = $this->actingAs($this->userA)
        ->patch(route('seller.products.stock', $this->productB), ['stock' => 999]);
    $resStock->assertStatus(403);

    $resStatus = $this->actingAs($this->userA)
        ->patch(route('seller.products.status', $this->productB));
    $resStatus->assertStatus(403);
});

test('Satici A kendi urununu basariyla olusturabilir, listeleyebilir ve guncelleyebilir', function () {
    // 1. Yeni ürün ekle
    $response = $this->actingAs($this->userA)
        ->post(route('seller.products.store'), [
            'name' => 'Alpha Yeni Kulaklik',
            'category_id' => $this->category->id,
            'price' => 750.00,
            'discount_price' => 699.00,
            'stock' => 15,
            'color' => 'Siyah',
            'size' => 'Standart',
        ]);

    $response->assertRedirect(route('seller.products.index'));

    $createdProduct = Product::where('slug', 'alpha-yeni-kulaklik')->first();
    expect($createdProduct)->not->toBeNull();
    expect($createdProduct->seller_id)->toBe($this->sellerA->id);

    // 2. Kendi düzenleme sayfasına erişebilmeli (200 OK)
    $editResponse = $this->actingAs($this->userA)
        ->get(route('seller.products.edit', $createdProduct));
    $editResponse->assertStatus(200);
    $editResponse->assertSee('Alpha Yeni Kulaklik');

    // 3. Güncelleyebilmeli
    $updateResponse = $this->actingAs($this->userA)
        ->put(route('seller.products.update', $createdProduct), [
            'name' => 'Alpha Yeni Kulaklik Pro',
            'category_id' => $this->category->id,
            'price' => 850.00,
            'stock' => 25,
            'status' => 'aktif',
        ]);
    $updateResponse->assertRedirect(route('seller.products.index'));

    $this->assertDatabaseHas('products', [
        'id' => $createdProduct->id,
        'name' => 'Alpha Yeni Kulaklik Pro',
    ]);

    // 4. Hızlı stok güncelleme
    $stockResponse = $this->actingAs($this->userA)
        ->patch(route('seller.products.stock', $createdProduct), ['stock' => 50]);
    $stockResponse->assertSessionHas('success');
    expect($createdProduct->fresh()->variants->first()->stock)->toBe(50);

    // 5. Durum değiştirme (Aktif -> Pasif)
    $statusResponse = $this->actingAs($this->userA)
        ->patch(route('seller.products.status', $createdProduct));
    expect($createdProduct->fresh()->status)->toBe('pasif');

    // 6. Silebilmeli
    $deleteResponse = $this->actingAs($this->userA)
        ->delete(route('seller.products.destroy', $createdProduct));
    $deleteResponse->assertRedirect(route('seller.products.index'));
    $this->assertDatabaseMissing('products', ['id' => $createdProduct->id]);
});
