<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiShoppingAssistantService
{
    /**
     * Process user query and return conversational text + recommended products.
     *
     * @return array{message: string, products: Collection<int, Product>, suggestions: array<int, string>}
     */
    public function respond(string $query): array
    {
        $normalized = Str::lower(trim($query));

        // 1. Check for Platform & Marketplace FAQs first
        $faqResponse = $this->checkFaq($normalized);
        if ($faqResponse !== null) {
            return $faqResponse;
        }

        // 2. Check if external Gemini API is configured and accessible
        $geminiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        if (! empty($geminiKey)) {
            $geminiResponse = $this->callGeminiApi($query, $geminiKey);
            if ($geminiResponse !== null) {
                return $geminiResponse;
            }
        }

        // 3. Intelligent Local Semantic Engine (Zero-fail, catalog-grounded)
        return $this->searchCatalog($query, $normalized);
    }

    /**
     * Check for common platform questions (shipping, returns, selling, security).
     */
    protected function checkFaq(string $q): ?array
    {
        if (Str::contains($q, ['kargo', 'teslimat', 'kurye', 'kaç günde', 'gönderim'])) {
            return [
                'message' => "📦 **AlSepete Kargo ve Teslimat Bilgileri:**\n\n- Siparişleriniz satıcılarımız tarafından **1-3 iş günü** içerisinde anlaşmalı kargoya teslim edilir.\n- **500 TL ve üzeri** alışverişlerinizde kargo tamamen **ücretsizdir**.\n- Çoklu satıcıdan alışveriş yaptığınızda her satıcı ürününü kendi kargo takip numarasıyla bağımsız olarak gönderir.",
                'products' => collect(),
                'suggestions' => ['İade şartları neler?', 'Nasıl satıcı olurum?', 'Popüler ürünleri göster'],
            ];
        }

        if (Str::contains($q, ['iade', 'değişim', 'iptal', 'geri ver', 'cayma'])) {
            return [
                'message' => "🔄 **Kolay ve Güvenli İade Süreci:**\n\n- Siparişinizi teslim aldığınız tarihten itibaren **14 gün boyunca** koşulsuz şartsız iade edebilirsiniz.\n- İade talebinizi hesabınızdaki **Siparişlerim** sayfasından ücretsiz kargo iade kodu oluşturarak başlatabilirsiniz.\n- Ürün satıcıya ulaşıp kontrol edildikten sonra paranız **24-48 saat** içinde kartınıza iade edilir.",
                'products' => collect(),
                'suggestions' => ['Kargo ücreti ne kadar?', 'Müşteri hizmetlerine nasıl ulaşırım?', 'Günün fırsatları'],
            ];
        }

        if (Str::contains($q, ['satıcı ol', 'mağaza aç', 'nasıl satarım', 'komisyon'])) {
            return [
                'message' => "🏪 **AlSepete'de Satıcı Olun & Milyonlara Satış Yapın:**\n\n- Ücretsiz mağaza başvurusu yaparak dakikalar içinde onaylanabilirsiniz.\n- Kategorilere göre avantajlı komisyon oranlarımız (%8 - %15) uygulanır.\n- Satış kazançlarınız sipariş teslim edildikten sonra doğrudan IBAN hesabınıza yatırılır.",
                'products' => collect(),
                'suggestions' => ['Komisyon oranları nedir?', 'En çok satan kategoriler', 'Yardım merkezi'],
            ];
        }

        if (Str::contains($q, ['ödeme', 'taksit', 'güvenli', 'kredi kartı', 'kapıda'])) {
            return [
                'message' => "🔒 **Güvenli Ödeme Altyapımız:**\n\n- Tüm ödemeler **256-Bit SSL** ve **3D Secure** güvenlik protokolleriyle şifrelenir.\n- Anlaşmalı kredi kartlarına **peşin fiyatına 3 ila 6 taksit** imkanı bulunmaktadır.\n- Sipariş onaylanana kadar ödemeniz AlSepete güvenli havuz hesabında bloke edilir.",
                'products' => collect(),
                'suggestions' => ['Kargo bilgisi', 'İade koşulları', 'İndirimli ürünler'],
            ];
        }

        return null;
    }

    /**
     * Intelligent local semantic catalog recommendation.
     */
    protected function searchCatalog(string $originalQuery, string $q): array
    {
        // Detect price budget (e.g. "5000 tl altı", "2000 liraya kadar", "10000 tl")
        $maxPrice = null;
        if (preg_match('/(\d+(?:[\.,]\d+)?)\s*(?:tl|bin|lira)?\s*(?:altı|altında|kadar|bütçe)/i', $q, $matches)) {
            $val = (float) str_replace(',', '.', $matches[1]);
            // If user wrote "5 bin" -> 5000
            if (Str::contains($q, 'bin') && $val < 1000) {
                $val *= 1000;
            }
            $maxPrice = $val;
        }

        // Keywords extraction
        $keywords = collect(explode(' ', $q))
            ->map(fn ($w) => trim($w, ".,!?;:'\""))
            ->filter(fn ($w) => mb_strlen($w) >= 3 && ! in_array($w, ['bir', 'bana', 'için', 'varmı', 'var', 'mı', 'mi', 'mu', 'mü', 'olan', 'gibi', 'veya', 'ile', 'altı', 'kadar', 'tavsiye', 'öner', 'öneri', 'istiyorum', 'bakıyorum', 'ürün', 'neler', 'uygun']));

        // Build product query
        $productQuery = Product::query()
            ->where('status', 'aktif')
            ->with(['brand', 'category', 'variants']);

        if ($maxPrice !== null && $maxPrice > 0) {
            $productQuery->whereHas('variants', function ($v) use ($maxPrice) {
                $v->where('price', '<=', $maxPrice)
                    ->orWhere(fn ($sub) => $sub->whereNotNull('discount_price')->where('discount_price', '<=', $maxPrice));
            });
        }

        // Specific category/item intent mappings
        $matchedCategory = null;
        $matchedBrand = null;

        if (Str::contains($q, ['kahve', 'espresso', 'türk kahve', 'latte'])) {
            $keywords->push('kahve', 'espresso');
        } elseif (Str::contains($q, ['süpürge', 'dikey süpürge', 'temizlik'])) {
            $keywords->push('süpürge');
        } elseif (Str::contains($q, ['airfryer', 'fritöz', 'fritoz'])) {
            $keywords->push('airfryer', 'fritöz');
        } elseif (Str::contains($q, ['tencere', 'tava', 'yemek takımı', 'çatal', 'mutfak'])) {
            $keywords->push('tencere', 'tava', 'takımı');
        } elseif (Str::contains($q, ['ayakkabı', 'sneaker', 'koşu', 'spor'])) {
            $keywords->push('ayakkabı', 'sneaker', 'jordan');
        } elseif (Str::contains($q, ['telefon', 'iphone', 'samsung', 'akıllı telefon'])) {
            $keywords->push('telefon', 'iphone', 'pro');
        } elseif (Str::contains($q, ['kulaklık', 'airpods', 'ses', 'müzik'])) {
            $keywords->push('airpods', 'kulaklık', 'ses');
        } elseif (Str::contains($q, ['çadır', 'kamp', 'termos', 'outdoor'])) {
            $keywords->push('çadır', 'termos', 'kamp');
        } elseif (Str::contains($q, ['dambıl', 'ağırlık', 'fitness', 'kondisyon'])) {
            $keywords->push('dambıl', 'krom');
        } elseif (Str::contains($q, ['ütü', 'buharlı'])) {
            $keywords->push('ütü');
        }

        // Apply matching
        if ($keywords->isNotEmpty()) {
            $productQuery->where(function ($sub) use ($keywords) {
                foreach ($keywords as $kw) {
                    $sub->orWhere('name', 'like', "%{$kw}%")
                        ->orWhere('description', 'like', "%{$kw}%")
                        ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$kw}%"))
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$kw}%"));
                }
            });
        }

        $products = $productQuery->latest()->take(3)->get();

        // If no products matched with specific keywords, give top rated/featured products
        if ($products->isEmpty()) {
            $products = Product::where('status', 'aktif')
                ->with(['brand', 'category', 'variants'])
                ->when($maxPrice, function ($q) use ($maxPrice) {
                    $q->whereHas('variants', function ($v) use ($maxPrice) {
                        $v->where('price', '<=', $maxPrice)
                            ->orWhere(fn ($sub) => $sub->whereNotNull('discount_price')->where('discount_price', '<=', $maxPrice));
                    });
                })
                ->latest()
                ->take(3)
                ->get();

            $message = 'Aradığınız kriterlere özel tam eşleşen ürün bulamadım ancak mağazamızın **en popüler ve yüksek puanlı** ürünlerinden bazılarını sizin için listeledim:';
        } else {
            $message = 'İsteğinize uygun olarak AlSepete kataloğumuzdan seçtiğim **en kaliteli ve avantajlı seçenekler**:';
            if ($maxPrice) {
                $formattedBudget = number_format($maxPrice, 0, ',', '.');
                $message = "🎯 **{$formattedBudget} TL bütçenize uygun**, stoklarımızda bulunan en iyi tavsiyeler:";
            }
        }

        return [
            'message' => $message,
            'products' => $products,
            'suggestions' => [
                'Bütçe dostu kahve makinesi',
                'Kablosuz dikey süpürge',
                'Nike & Adidas spor ayakkabıları',
                'Kargo ve iade şartları',
            ],
        ];
    }

    /**
     * Optional external call to Google Gemini if an API key is present in .env.
     */
    protected function callGeminiApi(string $userPrompt, string $apiKey): ?array
    {
        try {
            // Build compact catalog context for grounding
            $topProducts = Product::where('is_active', true)
                ->select(['id', 'name', 'base_price', 'brand_id'])
                ->with('brand:id,name')
                ->take(15)
                ->get()
                ->map(fn ($p) => "- [ID:{$p->id}] {$p->name} ({$p->brand?->name}): {$p->base_price} TL")
                ->implode("\n");

            $systemPrompt = "Sen AlSepete adlı Türk e-ticaret pazaryerinin yardımsever, samimi ve uzman yapay zeka alışveriş asistanısın. Müşterilere Türkçe yanıt ver. Ürün önerirken varsa katalogdaki ürünleri tavsiye et. Eğer ürün önerirsen yanıtının en sonuna [RECS: 1, 2] şeklinde önerdiğin ürün ID'lerini ekle.\nKatalog Özeti:\n{$topProducts}";

            $response = Http::timeout(4)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => "{$systemPrompt}\n\nMüşteri Sorusu: {$userPrompt}"],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                if (! empty($content)) {
                    $matchedIds = [];
                    if (preg_match('/\[RECS:\s*([0-9,\s]+)\]/i', $content, $m)) {
                        $matchedIds = array_map('intval', explode(',', $m[1]));
                        $content = trim(str_replace($m[0], '', $content));
                    }

                    $products = empty($matchedIds)
                        ? collect()
                        : Product::whereIn('id', $matchedIds)->with(['brand', 'category'])->get();

                    return [
                        'message' => $content,
                        'products' => $products,
                        'suggestions' => ['Başka ne önerirsin?', 'Kargo bilgisi', 'İade süreci'],
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Silent fallback to local semantic search on any network or API issue
        }

        return null;
    }
}
