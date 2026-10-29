<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiShoppingAssistantService
{
    /**
     * Stop words that should never pollute product search.
     *
     * @var array<int, string>
     */
    protected const STOP_WORDS = [
        'bir', 'bana', 'icin', 'için', 'varmı', 'varmi', 'var', 'mı', 'mi', 'mu', 'mü',
        'olan', 'gibi', 'veya', 'ile', 've', 'altı', 'alti', 'kadar', 'tavsiye', 'öner',
        'oner', 'öneri', 'oneri', 'istiyorum', 'bakıyorum', 'bakiyorum', 'ürün', 'urun',
        'neler', 'uygun', 'dostu', 'bütçe', 'butce', 'kaliteli', 'en', 'iyi', 'fiyatlı',
        'fiyatli', 'ucuz', 'pahalı', 'pahali', 'hakkında', 'hakkinda', 'göster', 'goster',
        'listele', 'seti', 'set', 'modeli', 'modelleri', 'türleri', 'hangisi', 'lütfen',
        'lutfen', 'tane', 'adet', 'seçenekleri', 'secenekleri', 'parça', 'parca', 'nedir',
        'nasıldır', 'nasildir', 'almak', 'istediğim', 'lazım', 'lazim', 'şöyle', 'soyle',
        'güzel', 'guzel', 'harika', 'popüler', 'populer', 'trend', 'al', 'ver',
        // English stop words
        'the', 'and', 'or', 'for', 'with', 'recommend', 'recommendation', 'suggestion',
        'suggest', 'budget', 'friendly', 'show', 'best', 'good', 'cheap', 'expensive',
        'please', 'want', 'looking', 'product', 'products', 'any', 'is', 'are', 'there',
    ];

    /**
     * Intent mappings for high precision product discovery.
     *
     * @var array<string, array{triggers: array<int, string>, search_terms: array<int, string>, category_hints?: array<int, string>, message_tr: string, message_en: string}>
     */
    protected const INTENT_MAPPINGS = [
        'coffee' => [
            'triggers' => ['kahve', 'espresso', 'coffee', 'latte', 'cappuccino', 'filtre kahve', 'moka pot', 'cezve', 'americano'],
            'search_terms' => ['kahve', 'espresso'],
            'category_hints' => ['Elektrikli Ev Aletleri'],
            'message_tr' => '☕ Kahve tutkunları için AlSepete seçkimizden en iyi kahve ve espresso makineleri:',
            'message_en' => '☕ Top-rated coffee and espresso machines selected for you:',
        ],
        'vacuum' => [
            'triggers' => ['süpürge', 'supurge', 'vacuum', 'dikey süpürge', 'şarjlı süpürge', 'kablosuz dikey', 'robot süpürge'],
            'search_terms' => ['süpürge', 'supurge', 'vacuum'],
            'category_hints' => ['Elektrikli Ev Aletleri'],
            'message_tr' => '🧹 Evinizi pırıl pırıl yapacak yüksek çekim güçlü dikey ve şarjlı süpürgeler:',
            'message_en' => '🧹 High-suction cordless and stick vacuum cleaners for your home:',
        ],
        'cookware' => [
            'triggers' => ['tencere', 'tava', 'cookware', 'pan', 'pot', 'yemek takımı', 'yemek takimi', 'çatal', 'catal', 'sahan', 'düdüklü', 'duduklu'],
            'search_terms' => ['tencere', 'tava', 'yemek takımı', 'çatal'],
            'category_hints' => ['Mutfak Gereçleri'],
            'message_tr' => '🍳 Mutfağınızda harikalar yaratacak en kaliteli tava, tencere ve yemek takımları:',
            'message_en' => '🍳 Premium cookware, pan, and dinnerware sets for your kitchen:',
        ],
        'airfryer' => [
            'triggers' => ['airfryer', 'fritöz', 'fritoz', 'hava fritözü', 'hava fritozu', 'air fryer'],
            'search_terms' => ['airfryer', 'fritöz', 'fritoz'],
            'category_hints' => ['Elektrikli Ev Aletleri'],
            'message_tr' => '🍟 Az yağlı ve çıtır lezzetler için popüler hava fritözü (Airfryer) modellerimiz:',
            'message_en' => '🍟 Popular hot air fryers (Airfryer) for healthy cooking:',
        ],
        'shoes' => [
            'triggers' => ['ayakkabı', 'ayakkabi', 'sneaker', 'spor ayakkabı', 'koşu ayakkabısı', 'jordan', 'stan smith', 'ultraboost', 'shoes', 'bot', 'çizme'],
            'search_terms' => ['ayakkabı', 'ayakkabi', 'sneaker', 'jordan', 'bot', 'ultraboost', 'stan smith'],
            'category_hints' => ['Spor Ayakkabı & Çanta', 'Kamp & Doğa Malzemeleri'],
            'message_tr' => '👟 Hem konfor hem de şıklık sunan popüler ayakkabı ve sneaker modellerimiz:',
            'message_en' => '👟 Stylish and comfortable sneakers & shoes for you:',
        ],
        'phone' => [
            'triggers' => ['telefon', 'iphone', 'samsung', 'akıllı telefon', 'cep telefonu', 'smartphone', 'phone'],
            'search_terms' => ['telefon', 'iphone', 'galaxy', 'smartphone'],
            'category_hints' => ['Telefon & Aksesuar'],
            'message_tr' => '📱 İleri teknoloji ve yüksek performanslı akıllı telefon seçeneklerimiz:',
            'message_en' => '📱 Advanced and high-performance smartphone choices:',
        ],
        'laptop' => [
            'triggers' => ['laptop', 'bilgisayar', 'macbook', 'notebook', 'dizüstü', 'dizustu', 'pc'],
            'search_terms' => ['macbook', 'laptop', 'bilgisayar', 'notebook'],
            'category_hints' => ['Bilgisayar & Laptop'],
            'message_tr' => '💻 İş, eğitim ve günlük kullanım için en iyi dizüstü bilgisayarlar:',
            'message_en' => '💻 Best laptops and computers for work, school, and daily use:',
        ],
        'audio' => [
            'triggers' => ['kulaklık', 'kulaklik', 'airpods', 'headphone', 'earphone', 'bluetooth kulaklık', 'hoparlör'],
            'search_terms' => ['kulaklık', 'airpods', 'hoparlör'],
            'category_hints' => ['Telefon & Aksesuar'],
            'message_tr' => '🎧 Kristal netliğinde ses deneyimi sunan popüler kulaklık modellerimiz:',
            'message_en' => '🎧 Crystal clear audio with our top-rated headphones:',
        ],
        'fitness' => [
            'triggers' => ['dambıl', 'dambil', 'ağırlık', 'agirlik', 'fitness', 'kondisyon', 'koşu bandı', 'mat', 'direnç lastiği', 'dumbbell', 'bisiklet'],
            'search_terms' => ['dambıl', 'ağırlık', 'kondisyon', 'koşu bandı', 'mat', 'direnç', 'bisiklet'],
            'category_hints' => ['Fitness & Kondisyon', 'Bisiklet & Ekipman'],
            'message_tr' => '💪 Formda kalmanız ve spor yapmanız için ideal fitness ve antrenman ekipmanları:',
            'message_en' => '💪 Top fitness and gym equipment for working out at home:',
        ],
        'camping' => [
            'triggers' => ['çadır', 'cadir', 'kamp', 'termos', 'outdoor', 'trekking', 'camping', 'uyku tulumu'],
            'search_terms' => ['çadır', 'termos', 'kamp', 'bot'],
            'category_hints' => ['Kamp & Doğa Malzemeleri'],
            'message_tr' => '🏕️ Doğayla iç içe maceralarınız için güvenilir kamp ve outdoor ekipmanları:',
            'message_en' => '🏕️ Reliable camping and outdoor gear for your next adventure:',
        ],
        'iron' => [
            'triggers' => ['ütü', 'utu', 'buharlı ütü', 'buharli utu', 'iron'],
            'search_terms' => ['ütü', 'utu'],
            'category_hints' => ['Elektrikli Ev Aletleri'],
            'message_tr' => '👔 Kırışıksız ve kusursuz kıyafetler için yüksek buharlı ütü modellerimiz:',
            'message_en' => '👔 High-performance steam irons for wrinkle-free clothes:',
        ],
        'haircare' => [
            'triggers' => ['saç', 'sac', 'düzleştirici', 'fön', 'dyson', 'airwrap', 'supersonic', 'corrale', 'airstrait', 'saç kurutma'],
            'search_terms' => ['airwrap', 'supersonic', 'corrale', 'airstrait', 'saç'],
            'category_hints' => ['Saç Bakımı & Şekillendirme'],
            'message_tr' => '✨ Saç sağlığı ve kusursuz şekillendirme için profesyonel saç bakım aletleri:',
            'message_en' => '✨ Professional hair care and styling tools for your hair:',
        ],
        'skincare' => [
            'triggers' => ['parfüm', 'parfum', 'serum', 'krem', 'nemlendirici', 'cilt', 'göz altı', 'güneş kremi', 'maskara', 'fondöten'],
            'search_terms' => ['parfüm', 'serum', 'krem', 'nemlendirici', 'maskara', 'fondöten'],
            'category_hints' => ['Cilt & Yüz Bakımı', 'Parfüm & Deodorant'],
            'message_tr' => '🌸 Cildinize ışıltı ve tazelik katacak en sevilen kozmetik & bakım ürünleri:',
            'message_en' => '🌸 Radiant skincare, cosmetics and perfumes curated for you:',
        ],
    ];

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
        $isEn = app()->getLocale() === 'en';

        if (Str::contains($q, ['kargo', 'teslimat', 'kurye', 'kaç günde', 'gönderim', 'shipping', 'delivery'])) {
            return [
                'message' => $isEn
                    ? "📦 **AlSepete Shipping & Delivery Information:**\n\n- Orders are dispatched by sellers within **1-3 business days** via partnered courier services.\n- Free shipping is offered on all orders of **500 TL and above**.\n- Multi-vendor orders ship independently with individual tracking numbers."
                    : "📦 **AlSepete Kargo ve Teslimat Bilgileri:**\n\n- Siparişleriniz satıcılarımız tarafından **1-3 iş günü** içerisinde anlaşmalı kargoya teslim edilir.\n- **500 TL ve üzeri** alışverişlerinizde kargo tamamen **ücretsizdir**.\n- Çoklu satıcıdan alışveriş yaptığınızda her satıcı ürününü kendi kargo takip numarasıyla bağımsız olarak gönderir.",
                'products' => collect(),
                'suggestions' => $isEn
                    ? ['What are return terms?', 'How to become a seller?', 'Show popular items']
                    : ['İade şartları neler?', 'Nasıl satıcı olurum?', 'Popüler ürünleri göster'],
            ];
        }

        if (Str::contains($q, ['iade', 'değişim', 'degisim', 'iptal', 'geri ver', 'cayma', 'return', 'refund', 'exchange'])) {
            return [
                'message' => $isEn
                    ? "🔄 **Easy & Secure Returns:**\n\n- You can return your order unconditionally within **14 days** of delivery.\n- Initiate your return from **My Orders** to generate a free return courier code.\n- Refunds are processed back to your card within **24-48 hours** after seller verification."
                    : "🔄 **Kolay ve Güvenli İade Süreci:**\n\n- Siparişinizi teslim aldığınız tarihten itibaren **14 gün boyunca** koşulsuz şartsız iade edebilirsiniz.\n- İade talebinizi hesabınızdaki **Siparişlerim** sayfasından ücretsiz kargo iade kodu oluşturarak başlatabilirsiniz.\n- Ürün satıcıya ulaşıp kontrol edildikten sonra paranız **24-48 saat** içinde kartınıza iade edilir.",
                'products' => collect(),
                'suggestions' => $isEn
                    ? ['How much is shipping?', 'How to contact support?', 'Daily deals']
                    : ['Kargo ücreti ne kadar?', 'Müşteri hizmetlerine nasıl ulaşırım?', 'Günün fırsatları'],
            ];
        }

        if (Str::contains($q, ['satıcı ol', 'satici ol', 'mağaza aç', 'magaza ac', 'nasıl satarım', 'komisyon', 'become a seller', 'sell on'])) {
            return [
                'message' => $isEn
                    ? "🏪 **Sell on AlSepete & Reach Millions:**\n\n- Apply for a seller account in minutes with our fast onboarding.\n- Competitive category commission rates (8% to 15%) apply.\n- Payouts are transferred directly to your verified IBAN once delivery is confirmed."
                    : "🏪 **AlSepete'de Satıcı Olun & Milyonlara Satış Yapın:**\n\n- Ücretsiz mağaza başvurusu yaparak dakikalar içinde onaylanabilirsiniz.\n- Kategorilere göre avantajlı komisyon oranlarımız (%8 - %15) uygulanır.\n- Satış kazançlarınız sipariş teslim edildikten sonra doğrudan IBAN hesabınıza yatırılır.",
                'products' => collect(),
                'suggestions' => $isEn
                    ? ['What are commission rates?', 'Top selling categories', 'Help center']
                    : ['Komisyon oranları nedir?', 'En çok satan kategoriler', 'Yardım merkezi'],
            ];
        }

        if (Str::contains($q, ['ödeme', 'odeme', 'taksit', 'güvenli', 'guvenli', 'kredi kartı', 'kapıda', 'payment', 'installment', 'secure'])) {
            return [
                'message' => $isEn
                    ? "🔒 **Our Secure Payment Infrastructure:**\n\n- All transactions are encrypted with **256-Bit SSL** and protected by **3D Secure**.\n- Installment plans up to **3 to 6 months** available on supported credit cards.\n- Your payment is held securely in escrow until order delivery is verified."
                    : "🔒 **Güvenli Ödeme Altyapımız:**\n\n- Tüm ödemeler **256-Bit SSL** ve **3D Secure** güvenlik protokolleriyle şifrelenir.\n- Anlaşmalı kredi kartlarına **peşin fiyatına 3 ila 6 taksit** imkanı bulunmaktadır.\n- Sipariş onaylanana kadar ödemeniz AlSepete güvenli havuz hesabında bloke edilir.",
                'products' => collect(),
                'suggestions' => $isEn
                    ? ['Shipping info', 'Return policies', 'Discounted items']
                    : ['Kargo bilgisi', 'İade koşulları', 'İndirimli ürünler'],
            ];
        }

        return null;
    }

    /**
     * Intelligent local semantic catalog recommendation.
     */
    protected function searchCatalog(string $originalQuery, string $q): array
    {
        $isEn = app()->getLocale() === 'en';

        // 1. Strip emojis and punctuation
        $clean = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $q);
        $clean = str_replace(['?', '!', '.', ',', ';', ':', '-', '_', '(', ')', '[', ']', '{', '}', '/', '\\', '&', '"', "'", '*'], ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', trim($clean));

        // 2. Detect price / budget constraints
        $maxPrice = null;
        if (preg_match('/(\d+(?:[\.,]\d+)?)\s*(?:tl|bin|lira|₺)?\s*(?:altı|alti|altında|kadar|bütçe|butce|civarı|under|below)/iu', $clean, $matches)) {
            $val = (float) str_replace(',', '.', $matches[1]);
            if (Str::contains($clean, 'bin') && $val < 1000) {
                $val *= 1000;
            }
            $maxPrice = $val;
        }

        $isBudgetOriented = Str::contains($clean, ['bütçe', 'butce', 'ucuz', 'uygun', 'ekonomik', 'fiyat/performans', 'f/p', 'budget', 'cheap', 'affordable']);

        // 3. Detect known Brands mentioned in query
        $detectedBrand = null;
        $knownBrands = ['Karaca', 'Tefal', 'Bosch', 'IKEA', 'English Home', 'Nike', 'Adidas', 'Puma', 'Zara', 'Mavi', "Levi's", 'The North Face', 'Columbia', 'Decathlon', 'Voit', 'Xiaomi', 'Dyson', 'Apple', 'Samsung', 'Asus', 'Armani', "L'Oréal", 'Nivea', 'Clinique'];
        foreach ($knownBrands as $b) {
            if (stripos($clean, $b) !== false) {
                $detectedBrand = $b;
                break;
            }
        }

        // 4. Detect Intent
        $matchedIntentKey = null;
        foreach (self::INTENT_MAPPINGS as $key => $def) {
            foreach ($def['triggers'] as $trigger) {
                if (Str::contains($clean, $trigger)) {
                    $matchedIntentKey = $key;
                    break 2;
                }
            }
        }

        // Base Product Query
        $productQuery = Product::query()
            ->where('status', 'aktif')
            ->with(['brand', 'category', 'variants']);

        if ($maxPrice !== null && $maxPrice > 0) {
            $productQuery->whereHas('variants', function ($v) use ($maxPrice) {
                $v->where('price', '<=', $maxPrice)
                    ->orWhere(fn ($sub) => $sub->whereNotNull('discount_price')->where('discount_price', '<=', $maxPrice));
            });
        }

        $resultMessage = '';

        if ($matchedIntentKey !== null) {
            $intent = self::INTENT_MAPPINGS[$matchedIntentKey];
            $searchTerms = $intent['search_terms'];

            // Strict intent filtering: product name must match intent terms
            $productQuery->where(function ($sub) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $sub->orWhere('name', 'like', "%{$term}%");
                }
            });

            // If a specific brand was also asked, narrow to that brand
            if ($detectedBrand !== null) {
                $brandClone = clone $productQuery;
                $brandClone->whereHas('brand', fn ($b) => $b->where('name', 'like', "%{$detectedBrand}%"));
                if ($brandClone->count() > 0) {
                    $productQuery = $brandClone;
                }
            }

            // Fallback to category only if name query yielded zero products
            if ($productQuery->count() === 0 && ! empty($intent['category_hints'])) {
                $categoryHints = $intent['category_hints'];
                $productQuery = Product::query()
                    ->where('status', 'aktif')
                    ->whereHas('category', fn ($c) => $c->whereIn('name', $categoryHints))
                    ->with(['brand', 'category', 'variants']);
            }

            $resultMessage = $isEn ? $intent['message_en'] : $intent['message_tr'];
        } else {
            // General Keyword Matching without Intent
            $words = array_filter(explode(' ', $clean), function ($w) {
                return mb_strlen($w) >= 3 && ! in_array(mb_strtolower($w), self::STOP_WORDS, true);
            });

            if (! empty($words)) {
                $productQuery->where(function ($sub) use ($words) {
                    foreach ($words as $w) {
                        $sub->orWhere('name', 'like', "%{$w}%")
                            ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$w}%"))
                            ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$w}%"));
                    }
                });

                $resultMessage = $isEn
                    ? 'Here are the best matching products from our catalog:'
                    : 'İsteğinize uygun olarak AlSepete kataloğumuzdan seçtiğim **en kaliteli ve avantajlı seçenekler**:';
            } else {
                $resultMessage = $isEn
                    ? 'Here are our most popular and highly rated products for you:'
                    : 'Kataloğumuzun **en popüler ve yüksek puanlı** ürünlerinden bazılarını sizin için listeledim:';
            }
        }

        // Scoring & Ranking: Calculate relevance score based on query tokens matching product name and brand
        $products = $productQuery->get();

        if ($products->isNotEmpty()) {
            $tokens = array_filter(explode(' ', mb_strtolower($clean)), fn ($t) => mb_strlen($t) >= 2 && ! in_array($t, self::STOP_WORDS, true));

            $scored = $products->map(function (Product $p) use ($tokens, $detectedBrand) {
                $nameLower = mb_strtolower($p->name);
                $brandLower = mb_strtolower($p->brand?->name ?? '');
                $score = 0;

                foreach ($tokens as $token) {
                    if (str_contains($nameLower, $token)) {
                        $score += 3;
                    }
                    if ($token === $brandLower || str_contains($brandLower, $token)) {
                        $score += 2;
                    }
                }

                if ($detectedBrand && stripos($brandLower, $detectedBrand) !== false) {
                    $score += 5;
                }

                $p->relevance_score = $score;

                return $p;
            });

            if ($isBudgetOriented) {
                // If user asked for budget-friendly / cheap, prioritize lowest price
                $products = $scored->sortBy(fn (Product $p) => (float) $p->min_price)->values()->take(3);
            } else {
                // Otherwise prioritize highest relevance matching score
                $products = $scored->sortByDesc('relevance_score')->values()->take(3);
            }
        }

        // Fallback if no products matched
        if ($products->isEmpty()) {
            $fallbackQuery = Product::where('status', 'aktif')
                ->with(['brand', 'category', 'variants']);

            if ($maxPrice) {
                $fallbackQuery->whereHas('variants', function ($v) use ($maxPrice) {
                    $v->where('price', '<=', $maxPrice)
                        ->orWhere(fn ($sub) => $sub->whereNotNull('discount_price')->where('discount_price', '<=', $maxPrice));
                });
            }

            $products = $fallbackQuery->latest()->take(3)->get();

            $resultMessage = $isEn
                ? 'I could not find exact matches for your specific criteria, but here are some of our **most popular** products:'
                : 'Aradığınız kriterlere özel tam eşleşen ürün bulamadım ancak mağazamızın **en popüler ve yüksek puanlı** ürünlerinden bazılarını sizin için listeledim:';
        }

        if ($maxPrice && $matchedIntentKey === null) {
            $formattedBudget = number_format($maxPrice, 0, ',', '.');
            $resultMessage = $isEn
                ? "🎯 Here are the best recommendations within your **{$formattedBudget} TL budget**:"
                : "🎯 **{$formattedBudget} TL bütçenize uygun**, stoklarımızda bulunan en iyi tavsiyeler:";
        }

        $defaultSuggestions = $isEn ? [
            '☕ Budget-friendly coffee machine',
            '🧹 Cordless stick vacuum',
            '🍳 Premium cookware & pan set',
            '📦 Shipping & return conditions',
        ] : [
            '☕ Bütçe dostu kahve makinesi öner',
            '🧹 Kablosuz dikey süpürge var mı?',
            '🍳 Kaliteli tava & tencere seti',
            '📦 Kargo ve iade şartları nedir?',
        ];

        return [
            'message' => $resultMessage,
            'products' => $products,
            'suggestions' => $defaultSuggestions,
        ];
    }

    /**
     * Optional external call to Google Gemini if an API key is present in .env.
     */
    protected function callGeminiApi(string $userPrompt, string $apiKey): ?array
    {
        try {
            // Build compact catalog context for grounding
            $topProducts = Product::where('status', 'aktif')
                ->select(['id', 'name', 'brand_id'])
                ->with('brand:id,name')
                ->take(15)
                ->get()
                ->map(fn ($p) => "- [ID:{$p->id}] {$p->name} ({$p->brand?->name})")
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
