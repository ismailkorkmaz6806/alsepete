<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected CouponService $couponService;

    protected Seller $sellerA;

    protected Seller $sellerB;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->couponService = new CouponService;
        $this->cartService = new CartService($this->couponService);

        // Satıcı A
        $userA = User::factory()->create();
        $this->sellerA = Seller::create([
            'user_id' => $userA->id,
            'store_name' => 'Mağaza A',
            'slug' => 'magaza-a',
            'status' => 'onayli',
            'tax_number' => '1111111111',
            'iban' => 'TR111111111111111111111111',
            'commission_rate' => 10.00,
        ]);

        // Satıcı B
        $userB = User::factory()->create();
        $this->sellerB = Seller::create([
            'user_id' => $userB->id,
            'store_name' => 'Mağaza B',
            'slug' => 'magaza-b',
            'status' => 'onayli',
            'tax_number' => '2222222222',
            'iban' => 'TR222222222222222222222222',
            'commission_rate' => 10.00,
        ]);

        $this->category = Category::create([
            'name' => 'Kategori Test',
            'slug' => 'kategori-test',
            'commission_rate' => 10.00,
            'sort' => 1,
        ]);
    }

    protected function createProductWithVariant(Seller $seller, float $price, int $stock = 50, ?float $discountPrice = null): ProductVariant
    {
        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $this->category->id,
            'name' => 'Ürün '.$seller->id.'-'.rand(100, 999),
            'slug' => 'urun-'.$seller->id.'-'.rand(100, 99999),
            'status' => 'aktif',
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-'.rand(1000, 9999),
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => $stock,
        ]);
    }

    /**
     * Test 1: Ara toplam hesabı, ürün fiyat ve adetlerinin çarpım toplamını doğru verir.
     */
    public function test_ara_toplam_hesabi_urun_fiyat_ve_adetlerinin_carpim_toplamini_dogru_verir(): void
    {
        $v1 = $this->createProductWithVariant($this->sellerA, 100.00); // 100 TL
        $v2 = $this->createProductWithVariant($this->sellerA, 150.00); // 150 TL

        $cart = Cart::create(['session_id' => 'test-calc-1']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v1->id, 'quantity' => 2]); // 2 * 100 = 200
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v2->id, 'quantity' => 3]); // 3 * 150 = 450

        $totals = $this->cartService->calculateTotals($cart);

        $this->assertEquals(650.00, $totals['subtotal']);
    }

    /**
     * Test 2: Tek satıcı için 300 TL altı siparişlerde standart kargo ücreti (49.90 TL) uygulanır.
     */
    public function test_tek_satici_icin_300_tl_alti_siparislerde_standart_kargo_ucreti_uygulanir(): void
    {
        $v1 = $this->createProductWithVariant($this->sellerA, 120.00); // 120 TL (< 300 TL)

        $cart = Cart::create(['session_id' => 'test-calc-2']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v1->id, 'quantity' => 1]);

        $totals = $this->cartService->calculateTotals($cart);

        $this->assertEquals(120.00, $totals['subtotal']);
        $this->assertEquals(49.90, $totals['total_shipping']);
        $this->assertEquals(169.90, $totals['grand_total']);
    }

    /**
     * Test 3: Tek satıcı için 300 TL ve üzeri siparişlerde kargo ücretsiz olur.
     */
    public function test_tek_satici_icin_300_tl_ve_uzeri_siparislerde_kargo_ucretsiz_olur(): void
    {
        $v1 = $this->createProductWithVariant($this->sellerA, 350.00); // 350 TL (>= 300 TL)

        $cart = Cart::create(['session_id' => 'test-calc-3']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v1->id, 'quantity' => 1]);

        $totals = $this->cartService->calculateTotals($cart);

        $this->assertEquals(350.00, $totals['subtotal']);
        $this->assertEquals(0.00, $totals['total_shipping']);
        $this->assertEquals(350.00, $totals['grand_total']);
    }

    /**
     * Test 4: Çok satıcılı sepette her satıcının kargo ücreti ve 300 TL barajı bağımsız hesaplanır.
     */
    public function test_cok_saticili_sepette_her_saticinin_kargo_ucreti_bagimsiz_hesaplanir(): void
    {
        // Satıcı A: 100 TL (< 300 => Kargo 49.90)
        $vA = $this->createProductWithVariant($this->sellerA, 100.00);

        // Satıcı B: 400 TL (>= 300 => Kargo 0.00)
        $vB = $this->createProductWithVariant($this->sellerB, 400.00);

        $cart = Cart::create(['session_id' => 'test-calc-4']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $vA->id, 'quantity' => 1]);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $vB->id, 'quantity' => 1]);

        $totals = $this->cartService->calculateTotals($cart);
        $groups = $this->cartService->groupedBySeller($cart);

        $this->assertEquals(500.00, $totals['subtotal']);
        $this->assertEquals(49.90, $totals['total_shipping']); // 49.90 + 0.00
        $this->assertEquals(549.90, $totals['grand_total']);
        $this->assertCount(2, $groups);
        $this->assertFalse($groups[$this->sellerA->id]['is_free_shipping']);
        $this->assertTrue($groups[$this->sellerB->id]['is_free_shipping']);
    }

    /**
     * Test 5: Yüzde indirimli kupon ara toplam üzerinden doğru hesaplanır ve genel toplama yansır.
     */
    public function test_yuzde_indirimli_kupon_ara_toplam_uzerinden_dogru_hesaplanir(): void
    {
        $v = $this->createProductWithVariant($this->sellerA, 500.00); // 500 TL (Kargo bedava)

        $coupon = Coupon::create([
            'code' => 'YUZDE10',
            'type' => 'yuzde',
            'value' => 10.00, // %10 = 50 TL indirim
            'min_total' => 200.00,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);

        $cart = Cart::create(['session_id' => 'test-calc-5']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v->id, 'quantity' => 1]);

        $totals = $this->cartService->calculateTotals($cart, $coupon);

        $this->assertEquals(500.00, $totals['subtotal']);
        $this->assertEquals(50.00, $totals['discount']);
        $this->assertEquals(0.00, $totals['total_shipping']);
        $this->assertEquals(450.00, $totals['grand_total']);
    }

    /**
     * Test 6: Sabit tutar kupon indirimi sepet ara toplamını aşamaz (eksiye düşmez).
     */
    public function test_sabit_tutar_kupon_indirimi_sepet_tutarini_asamaz(): void
    {
        $v = $this->createProductWithVariant($this->sellerA, 100.00); // 100 TL (<300 => Kargo 49.90)

        $coupon = Coupon::create([
            'code' => 'DEV150',
            'type' => 'tutar',
            'value' => 150.00, // 150 TL indirim (100 TL'lik sepete uygulandığında max 100 TL düşmeli)
            'min_total' => 50.00,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);

        $cart = Cart::create(['session_id' => 'test-calc-6']);
        CartItem::create(['cart_id' => $cart->id, 'variant_id' => $v->id, 'quantity' => 1]);

        $totals = $this->cartService->calculateTotals($cart, $coupon);

        $this->assertEquals(100.00, $totals['subtotal']);
        $this->assertEquals(100.00, $totals['discount']); // En fazla 100 TL
        $this->assertEquals(49.90, $totals['total_shipping']);
        $this->assertEquals(49.90, $totals['grand_total']); // 0 + 49.90 kargo
    }

    /**
     * Test 7 (Hafta 5 Kabul Kriteri): Süresi dolmuş kupon hata mesajı verir.
     */
    public function test_hafta_5_kabul_kriteri_suresi_dolmus_kupon_hata_mesaji_verir(): void
    {
        $expiredCoupon = Coupon::create([
            'code' => 'ESKIKUPON',
            'type' => 'yuzde',
            'value' => 15.00,
            'min_total' => 50.00,
            'starts_at' => now()->subMonths(3),
            'ends_at' => now()->subDay(), // Dün bitti
        ]);

        $result = $this->couponService->validate($expiredCoupon, 250.00);

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('süresi dolmuştur', $result['message']);
    }

    /**
     * Test 8: Kupon minimum sepet tutarını sağlamadığında hata mesajı verir.
     */
    public function test_kupon_minimum_sepet_tutarini_saglamadiginda_hata_mesaji_verir(): void
    {
        $coupon = Coupon::create([
            'code' => 'LIMIT500',
            'type' => 'tutar',
            'value' => 50.00,
            'min_total' => 500.00,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);

        $result = $this->couponService->validate($coupon, 350.00); // 350 TL < 500 TL

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('en az', $result['message']);
    }
}
