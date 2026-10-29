<?php

namespace Tests\Feature;

use App\Livewire\AiShoppingAssistant;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AiShoppingAssistantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $sellerUser = User::factory()->create();
        $seller = Seller::create([
            'user_id' => $sellerUser->id,
            'store_name' => 'TeknoStore',
            'slug' => 'teknostore',
            'tax_number' => '1234567890',
            'iban' => 'TR123456789012345678901234',
            'status' => 'onayli',
            'commission_rate' => 10.00,
        ]);

        $cat = Category::create([
            'name' => 'Elektrikli Ev Aletleri',
            'slug' => 'elektrikli-ev-aletleri',
            'commission_rate' => 12.00,
        ]);

        $brand = Brand::create([
            'name' => 'Karaca',
            'slug' => 'karaca',
        ]);

        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $cat->id,
            'brand_id' => $brand->id,
            'name' => 'Karaca Hatır Hüps Közde ve Sütlü Türk Kahvesi Makinesi',
            'slug' => 'karaca-hatir-hups-turk-kahvesi-makinesi',
            'description' => '5 fincan kapasiteli elektrikli közde Türk kahvesi makinesi.',
            'status' => 'aktif',
            'image_url' => '/images/products/turkish-coffee-maker.jpg',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'KRC-HPS-01',
            'color' => 'Rose Gold',
            'size' => '5 Fincan',
            'price' => 1999.00,
            'discount_price' => null,
            'stock' => 10,
        ]);
    }

    public function test_ai_assistant_renders_on_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeLivewire(AiShoppingAssistant::class);
        $response->assertSee('AI Asistanı');
    }

    public function test_ai_assistant_can_toggle_chat_modal(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->assertSet('isOpen', false)
            ->call('toggleChat')
            ->assertSet('isOpen', true)
            ->call('closeChat')
            ->assertSet('isOpen', false);
    }

    public function test_ai_assistant_recommends_products_for_keyword_search(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->set('userInput', 'Bana Türk kahvesi makinesi önerir misin?')
            ->call('sendMessage')
            ->assertSee('Karaca Hatır Hüps')
            ->assertSee('1.999,00 TL')
            ->assertSet('userInput', '');
    }

    public function test_ai_assistant_answers_shipping_faq(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->set('userInput', 'Kargo ücreti ne kadar ve kaç günde gelir?')
            ->call('sendMessage')
            ->assertSee('Kargo ve Teslimat Bilgileri')
            ->assertSee('1-3 iş günü')
            ->assertSee('500 TL');
    }

    public function test_ai_assistant_answers_return_policy_faq(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->set('userInput', 'Siparişi iade etmek istiyorum süreç nasıl?')
            ->call('sendMessage')
            ->assertSee('İade Süreci')
            ->assertSee('14 gün boyunca');
    }

    public function test_ai_assistant_handles_quick_prompt(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->call('quickAsk', 'Bütçe dostu kahve makinesi')
            ->assertSee('Karaca Hatır Hüps');
    }

    public function test_ai_assistant_can_reset_chat(): void
    {
        Livewire::test(AiShoppingAssistant::class)
            ->set('userInput', 'Kargo bilgisi')
            ->call('sendMessage')
            ->call('resetChat')
            ->assertSee('Sohbet sıfırlandı');
    }
}
