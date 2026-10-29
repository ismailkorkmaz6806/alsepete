<?php

use App\Livewire\ProductQuestions;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductQuestion;
use App\Models\Seller;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('customer', 'web');
    Role::findOrCreate('seller', 'web');

    $this->sellerUser = User::factory()->create();
    $this->sellerUser->assignRole('seller');

    $this->seller = Seller::create([
        'user_id' => $this->sellerUser->id,
        'store_name' => 'Bonus Magaza',
        'slug' => 'bonus-magaza',
        'status' => 'onayli',
        'tax_number' => '4444444444',
        'iban' => 'TR444444444444444444444444',
        'commission_rate' => 10.00,
    ]);

    $this->category = Category::create([
        'name' => 'Bonus Kategori',
        'slug' => 'bonus-kategori',
        'commission_rate' => 10.00,
        'sort' => 1,
    ]);

    $this->product = Product::create([
        'seller_id' => $this->seller->id,
        'category_id' => $this->category->id,
        'name' => 'Bonus Test Urunu',
        'slug' => 'bonus-test-urunu',
        'description' => 'Test urun aciklamasi',
        'status' => 'aktif',
    ]);

    $this->customer = User::factory()->create();
    $this->customer->assignRole('customer');
});

test('authenticated customer can ask question to seller via Livewire', function () {
    $this->actingAs($this->customer);

    Livewire::test(ProductQuestions::class, ['product' => $this->product])
        ->set('question', 'Bu ürünün kumaşı pamuk mu?')
        ->call('askQuestion')
        ->assertSet('success', true);

    $this->assertDatabaseHas('product_questions', [
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'seller_id' => $this->seller->id,
        'question' => 'Bu ürünün kumaşı pamuk mu?',
    ]);
});

test('approved seller can view their questions dashboard', function () {
    ProductQuestion::create([
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'seller_id' => $this->seller->id,
        'question' => 'Garanti süresi kaç yıl?',
        'is_approved' => true,
    ]);

    $response = $this->actingAs($this->sellerUser)->get(route('seller.questions.index'));
    $response->assertStatus(200);
    $response->assertSee('Garanti süresi kaç yıl?');
    $response->assertSee('Bonus Test Urunu');
});

test('seller can answer a question and publish it on product page', function () {
    $question = ProductQuestion::create([
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'seller_id' => $this->seller->id,
        'question' => 'Kargo ücretsiz mi?',
        'is_approved' => true,
    ]);

    $response = $this->actingAs($this->sellerUser)->put(route('seller.questions.update', $question->id), [
        'answer' => 'Evet, tüm Türkiye geneline kargo ücretsizdir.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('product_questions', [
        'id' => $question->id,
        'answer' => 'Evet, tüm Türkiye geneline kargo ücretsizdir.',
    ]);

    // Ürün sayfasında soru ve cevap görünmeli
    $pageResponse = $this->get(route('products.show', $this->product->slug));
    $pageResponse->assertStatus(200);
    $pageResponse->assertSee('Kargo ücretsiz mi?');
    $pageResponse->assertSee('Evet, tüm Türkiye geneline kargo ücretsizdir.');
});

test('another user or customer cannot answer someone else seller question', function () {
    $question = ProductQuestion::create([
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'seller_id' => $this->seller->id,
        'question' => 'Test soru',
        'is_approved' => true,
    ]);

    $otherUser = User::factory()->create();
    $response = $this->actingAs($otherUser)->put(route('seller.questions.update', $question->id), [
        'answer' => 'Yetkisiz cevap denemesi',
    ]);

    $response->assertStatus(403);
});

test('customer can view their asked questions and seller answers on dashboard', function () {
    ProductQuestion::create([
        'product_id' => $this->product->id,
        'user_id' => $this->customer->id,
        'seller_id' => $this->seller->id,
        'question' => 'Müşteri panelimdeki özel soru?',
        'answer' => 'Satıcının cevabı burada.',
        'answered_at' => now(),
        'is_approved' => true,
    ]);

    $response = $this->actingAs($this->customer)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Müşteri Kontrol Paneli');
    $response->assertSee('Sorduğum Sorular ve Satıcı Yanıtları');
    $response->assertSee('Müşteri panelimdeki özel soru?');
    $response->assertSee('Satıcının cevabı burada.');
    $response->assertSee('Bonus Test Urunu');
});
