<?php

namespace Tests\Feature;

use App\Livewire\AddressManager;
use App\Livewire\CartPage;
use App\Livewire\FavoriteButton;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Hafta5FeatureTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected User $customer;

    protected Seller $seller;

    protected Product $product;

    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->cartService = app(CartService::class);

        $this->customer = User::factory()->create();
        $this->customer->assignRole('customer');

        $sellerUser = User::factory()->create();
        $sellerUser->assignRole('seller');
        $this->seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'Moda Butik',
            'slug' => 'moda-butik',
            'status' => 'onayli',
            'tax_number' => '3333333333',
            'iban' => 'TR333333333333333333333333',
            'commission_rate' => 10.00,
        ]);

        $category = Category::create([
            'name' => 'Giyim',
            'slug' => 'giyim',
            'commission_rate' => 10.00,
            'sort' => 1,
        ]);

        $this->product = Product::create([
            'seller_id' => $this->seller->id,
            'category_id' => $category->id,
            'name' => 'Mavi Keten Gömlek',
            'slug' => 'mavi-keten-gomlek',
            'status' => 'aktif',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku' => 'GOM-MAVI-M',
            'color' => 'Mavi',
            'size' => 'M',
            'price' => 350.00,
            'discount_price' => 280.00,
            'stock' => 5, // Stok: 5 adet
        ]);
    }

    /**
     * Hafta 5 Kabul Kriteri: Stoktan fazla ürün sepete eklenemez.
     */
    public function test_stoktan_fazla_urun_sepete_eklenemez_ve_uyari_verilir(): void
    {
        $cart = Cart::create(['session_id' => 'stock-test-session']);

        // 1. Stok (5) kadar eklenebilir
        $res1 = $this->cartService->addItem($this->variant->id, 3, $cart);
        $this->assertTrue($res1['success']);

        // 2. Kalan 2 adet de eklenebilir
        $res2 = $this->cartService->addItem($this->variant->id, 2, $cart);
        $this->assertTrue($res2['success']);

        // 3. Stok doldu (toplam 5 adet oldu), 1 adet daha eklemeye çalışınca engellenir
        $res3 = $this->cartService->addItem($this->variant->id, 1, $cart);
        $this->assertFalse($res3['success']);
        $this->assertStringContainsString('Mevcut stok sınırı 5 adettir', $res3['message']);
    }

    /**
     * Hafta 5 Kabul Kriteri: Misafir sepeti session'da tutulur, giriş yapınca kullanıcı sepetine birleşir.
     */
    public function test_misafir_sepeti_giris_yapinca_kullanici_sepetine_aktarilir(): void
    {
        $guestSessionId = 'guest-session-123';
        $guestCart = Cart::create(['session_id' => $guestSessionId]);

        // Misafir 2 adet ürün ekliyor
        $this->cartService->addItem($this->variant->id, 2, $guestCart);
        $this->assertEquals(2, $this->cartService->itemCount($guestCart));

        // Kullanıcı giriş yapıyor -> mergeGuestCart tetiklenir
        $this->cartService->mergeGuestCart($this->customer->id, $guestSessionId);

        // Kullanıcının sepetinde 2 adet olmalı
        $userCart = Cart::where('user_id', $this->customer->id)->first();
        $this->assertNotNull($userCart);
        $this->assertEquals(2, $this->cartService->itemCount($userCart));

        // Misafir sepeti silinmiş olmalı
        $this->assertDatabaseMissing('carts', ['session_id' => $guestSessionId]);
    }

    /**
     * Hafta 5: Canlı Livewire Sepet Sayfası (/sepet) görüntülenir ve adet güncelleme çalışır.
     */
    public function test_canli_sepet_sayfasi_goruntulenir_ve_adet_artirma_calisir(): void
    {
        $cart = Cart::create(['user_id' => $this->customer->id]);
        $this->cartService->addItem($this->variant->id, 1, $cart);
        $item = $cart->items()->first();

        Livewire::actingAs($this->customer)
            ->test(CartPage::class)
            ->assertSee('Mavi Keten Gömlek')
            ->assertSee('Moda Butik')
            ->call('increment', $item->id, 1)
            ->assertDispatched('cart-updated');

        $this->assertEquals(2, $item->fresh()->quantity);
    }

    /**
     * Hafta 5: Kullanıcı favorilere ekleyebilir, çıkarabilir ve /favorilerim sayfasında görebilir.
     */
    public function test_kullanici_favorilere_ekleyebilir_ve_favoriler_sayfasinda_goruntuleyebilir(): void
    {
        // 1. Livewire butonu ile favoriye ekle
        Livewire::actingAs($this->customer)
            ->test(FavoriteButton::class, ['productId' => $this->product->id])
            ->call('toggle')
            ->assertSet('isFavorite', true);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
        ]);

        // 2. /favorilerim sayfasını ziyaret et
        $response = $this->actingAs($this->customer)->get(route('favorites.index'));
        $response->assertStatus(200);
        $response->assertSee('Mavi Keten Gömlek');

        // 3. Livewire butonu ile favoriden çıkar
        Livewire::actingAs($this->customer)
            ->test(FavoriteButton::class, ['productId' => $this->product->id])
            ->call('toggle')
            ->assertSet('isFavorite', false);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
        ]);
    }

    /**
     * Hafta 5: Misafir favoriye basarsa giriş sayfasına yönlendirilir.
     */
    public function test_misafir_favoriye_eklemek_istediginde_giris_sayfasina_yonlendirilir(): void
    {
        Livewire::test(FavoriteButton::class, ['productId' => $this->product->id])
            ->call('toggle')
            ->assertRedirect(route('login'));
    }

    /**
     * Hafta 5: İl ve ilçe seçimli adres defteri yönetimi ve tek varsayılan adres kuralı.
     */
    public function test_adres_defteri_il_ilce_secimi_ve_varsayilan_adres_belirleme(): void
    {
        // 1. İlk adres eklenir (otomatik varsayılan olmalı)
        Livewire::actingAs($this->customer)
            ->test(AddressManager::class)
            ->set('title', 'Ev Adresim')
            ->set('city', 'İstanbul')
            ->set('district', 'Kadıköy')
            ->set('full_address', 'Moda Caddesi No: 12 Daire: 4')
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->customer->id,
            'title' => 'Ev Adresim',
            'city' => 'İstanbul',
            'district' => 'Kadıköy',
            'is_default' => true,
        ]);

        // 2. İkinci adres varsayılan olarak eklenir -> birincinin varsayılanlığı düşmeli
        Livewire::actingAs($this->customer)
            ->test(AddressManager::class)
            ->set('title', 'İş Yeri')
            ->set('city', 'Ankara')
            ->set('district', 'Çankaya')
            ->set('full_address', 'Tunalı Hilmi Cad. No: 40')
            ->set('is_default', true)
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->customer->id,
            'title' => 'İş Yeri',
            'is_default' => true,
        ]);

        $firstAddress = Address::where('user_id', $this->customer->id)->where('title', 'Ev Adresim')->first();
        $this->assertFalse((bool) $firstAddress->is_default);
    }

    /**
     * Hafta 5 Yetkilendirme (AddressPolicy): Başka kullanıcının adresini düzenleyemez veya silemez (403 alır).
     */
    public function test_baska_kullanici_adresi_duzenleyemez_veya_silemez_403_alir(): void
    {
        $address = Address::create([
            'user_id' => $this->customer->id,
            'title' => 'Gizli Adres',
            'city' => 'İzmir',
            'district' => 'Konak',
            'full_address' => 'Alsancak Kordon Boyu No: 15',
            'is_default' => true,
        ]);

        // Başka bir müşteri
        $otherCustomer = User::factory()->create();
        $otherCustomer->assignRole('customer');

        // Başka müşteri bu adresi düzenlemeye veya silmeye çalışırsa 403 almalı
        Livewire::actingAs($otherCustomer)
            ->test(AddressManager::class)
            ->call('edit', $address->id)
            ->assertForbidden();

        Livewire::actingAs($otherCustomer)
            ->test(AddressManager::class)
            ->call('delete', $address->id)
            ->assertForbidden();

        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }
}
