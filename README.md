# 🛍️ AlSepete - Çok Satıcılı E-Ticaret Pazaryeri Platformu

Amazon ve Hepsiburada iş modeline dayalı; modern, yüksek performanslı ve tam teşekküllü bir Laravel e-ticaret pazaryeri projesi.

[![Laravel Version](https://img.shields.io/badge/Laravel-11%20%2F%2012-red?logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%2F%208.3-blue?logo=php)](https://php.net)
[![Livewire 3](https://img.shields.io/badge/Livewire-3.x-fb70a9?logo=livewire)](https://livewire.laravel.com)
[![Tests](https://img.shields.io/badge/Tests-67%20Passing%20(176%20Assertions)-brightgreen?logo=pest)](https://pestphp.com)
[![Code Style](https://img.shields.io/badge/Code%20Style-PSR--12%20(Pint)-success)](https://laravel.com/docs/pint)
[![AI Engine](https://img.shields.io/badge/AI-Google%20Gemini%203.5-orange?logo=google)](https://ai.google.dev)
[![Release](https://img.shields.io/badge/Release-v0.3%20(Hafta%203)-indigo)](https://github.com/ismailkorkmaz6806/alsepete)

---

## 📌 Proje Özeti ve Çekirdek Mimari

- **Çok Satıcılı Mimari (Multi-Vendor):** Satıcılar mağaza açar (`sellers`), ürün listeler ve kendi siparişlerini bağımsız yönetir.
- **Sepet ve Sipariş Bölme Mantığı (Sub-Orders):** Müşteri tek sepette 3 farklı satıcıdan ürün aldığında; sistem **1 ana sipariş (`orders`)** ve satıcı başına **1 alt sipariş (`sub_orders`)** oluşturur. Her satıcı yalnızca kendi alt siparişini görür ve kargolar.
- **Dinamik Komisyon Modeli:** Kategori (`categories.commission_rate`) ve satıcı bazlı komisyon otomatik hesaplanır.
- **3 Zorunlu Tasarım Kuralı:**
  1. **Hassas Para:** Fiyatlar `decimal(10,2)` olarak saklanır (asla float kullanılmaz).
  2. **Snapshot Güvenliği:** Sipariş anındaki ürün adı, fiyat ve teslimat adresi sipariş tablolarına kopyalanır (`snapshot`).
  3. **Stok Güvenliği:** Stok `product_variants` tablosundadır ve `DB::transaction` + `lockForUpdate()` ile yarış koşullarına (race condition) karşı korunur.

---

## 🌟 Hafta 3 (v0.3) Teslim Edilen Modüller & Kabul Kriterleri

Hafta 3 şartnamesine ait gezilebilir vitrin, reaktif varyant yönetimi, arama ve seçilen tüm bonus özellikler eksiksiz tamamlanmıştır:

### 1. Canlı Varyant Seçimi & Ürün Detay (Livewire 3)
- **Sayfa Yenilenmeden Reaktif Değişim:** Renk ve beden seçildiğinde anlık fiyat, indirim oranı, SKU ve stok miktarı Livewire ile dinamik güncellenir.
- **SEO Uyumlu URL:** `/urun/{slug}` rotası.
- **Görsel Galerisi:** Büyük ürün önizlemesi ve tıklanabilir çoklu küçük resim (thumbnail) galerisi.
- **Stok Koruması:** Stok bittiğinde *"Tükendi"* uyarısı ve sepete ekleme butonunun otomatik pasifleştirilmesi.

### 2. Kategori Sayfası (24 Ürünlük Sayfalama, Filtreleme & Sıralama)
- **SEO Uyumlu URL:** `/kategori/{slug}` rotası (Örn: `/kategori/elektronik`, `/kategori/ev-yasam`).
- **24 Ürünlük Sayfalama:** `CategoryShowController` üzerinde `$query->paginate(24)` ile endüstri standardı sayfalama düzeni (`{{ $products->links() }}`).
- **Sıralama Seçenekleri (4 Mod):**
  - **En Yeniler (`newest`):** Son eklenen ürünler ilk sırada.
  - **Fiyat: Düşükten Yükseğe (`price_asc`):** Varyant fiyatına göre artan sıralama.
  - **Fiyat: Yüksekten Düşüğe (`price_desc`):** Varyant fiyatına göre azalan sıralama.
  - **En Çok Değerlendirilenler (`rating`):** Puan ve popülerliğe göre listeleme.
- **Gelişmiş Filtreler:** Marka seçimi, minimum-maksimum fiyat aralığı ve "Sadece Stoktakiler" (`in_stock`) filtre kutusu.

### 3. Canlı Arama Çubuğu (Debounced Live Search)
- Navbar üzerinde anlık ürün arama.
- Kullanıcı yazarken veritabanından isim, açıklama ve kategori bazlı filtreleme ile anlık açılır öneri menüsü.

### 4. Ana Sayfa Vitrini (Showcase)
- **Banner Slider:** Alpine.js tabanlı otomatik geçişli (5 sn), önceki/sonraki butonlu ve noktalı kampanya slider'ı.
- **Öne Çıkan Kategoriler:** İkonlu, alt kategori adetli ve doğrudan kategori sayfasına yönlendiren kart ızgarası.
- **Çok Satanlar & Yeni Gelenler:** Veritabanından dinamik çekilen, fırsat etiketli ürün vitrinleri.
- **Zengin ve Gerçekçi Katalog:** Seeder ile 100 adet gerçekçi, yüksek çözünürlüklü ve kategorisine uygun ürün (kozmetik, teknoloji, ev & yaşam, spor vb.).

### 5. N+1 Sorgu Koruması & Laravel Debugbar
- Tüm sayfalarda `with(['seller', 'variants', 'brand', 'category', 'media'])` kullanılarak N+1 sorgu problemi sıfıra indirilmiştir.
- Geliştirme ortamında sorguları görsel olarak denetlemek için `barryvdh/laravel-debugbar` entegre edilmiştir.

### 6. Bonus Görevler & İnovasyonlar (+6 Puan ve Fazlası)
- 🌟 **Bonus 1 (+2 Puan) - "Bu Ürünü Alanlar Bunları da Aldı":** Ürün detay sayfasında çapraz kategori ve ilgili ürün öneri algoritması.
- 🌟 **Bonus 2 (+2 Puan) - Satıcıya Soru Sor & Cevap Sistemi:** 
  - Müşterinin ürün sayfasından Livewire ile satıcıya soru sorabilmesi.
  - Onaylı satıcının kendi mağaza panelinden (`/satici/sorular`) gelen soruları görüntüleyip yanıtlaması.
  - Yanıtlanan soruların ürün detay sayfasında Soru-Cevap bloğunda anında yayınlanması.
- 🌟 **Bonus 3 (+2 Puan) - Çoklu Dil:** Türkçe ve İngilizce tam yerelleştirme (`/dil/tr` & `/dil/en`).
- 🤖 **Bonus 4 (İnovasyon) - AlSepete AI Akıllı Alışveriş Asistanı:**
  - Tüm sayfalarda sağ alt köşede yer alan canlı sohbet balonu (`Livewire` + `Alpine.js`).
  - **Google Gemini Entegrasyonu:** Google AI Studio `gemini-3.5-flash-lite` / `gemini-3.5-flash` modelleriyle entegre.
  - **Çok Turlu Sohbet Hafızası (Multi-Turn Conversation):** Kullanıcının önceki sorularını unutmaz; zamirleri, bütçe kıyaslamalarını ve devam sorularını akıllıca yanıtlar.
  - **Tıklanabilir Ürün Kartları:** Doğal dil ile bütçe ve anahtar kelimelere göre (örn: *"3000 TL altı kulaklık öner"*) veritabanını tarayarak canlı ürün kartı tavsiye etme.
  - **Kurumsal Bilgi:** Kargo, iade şartları, satıcı olma gibi platform kurallarına anında yanıt verme.
  - **Offline Fallback:** API anahtarı girilmediğinde veya kota bittiğinde dahili semantik arama motoruna pürüzsüz geçiş.

---

## 🚀 Hafta 2 (v0.2) Teslim Edilen Modüller

- **Kimlik Doğrulama & Roller (Spatie / Breeze):** `admin`, `seller`, `customer` rolleri.
- **Rol Tabanlı Rota Koruması (Middleware):** `EnsureUserIsAdmin` (`/admin/*`) ve `EnsureSellerIsApproved` (`/satici/*`).
- **Kategori Yönetimi (Admin CRUD) - İnce Controller & Action Mimarisi:**
  - `CreateCategoryAction`, `UpdateCategoryAction`, `DeleteCategoryAction`.
  - `CategoryStoreRequest`, `CategoryUpdateRequest` Türkçe validasyonları ve `CategoryPolicy`.
- **Ortak Düzen & Dinamik Mega Menü:** `View::composer` ile önbellekli kategori ağacı.
- **Gösterge Panelleri (Dashboards):** Admin Dashboard (`/admin`) ve Satıcı Dashboard (`/satici`).

---

## 🔐 Hazır Test Hesapları

Veritabanı seed edildiğinde kullanıma hazır gelen hesaplar:

| Rol | E-Posta | Şifre | Erişim Alanı |
|---|---|---|---|
| **Admin** | `admin@alsepete.com` | `password` | `/admin` ve `/admin/categories` |
| **Onaylı Satıcı** | `satici1@alsepete.com` | `password` | `/satici` (Mağaza Paneli & Soru-Cevap) |
| **Müşteri** | `musteri1@alsepete.com` | `password` | Ana sayfa & Kullanıcı profili |

---

## 💻 Sistem Gereksinimleri
- **PHP:** 8.2 veya 8.3+ (Eklentiler: `pdo_sqlite` veya `pdo_mysql`, `mbstring`, `openssl`, `curl`, `intl`)
- **Composer:** 2.x
- **Veritabanı:** SQLite (varsayılan) veya MySQL 8.x
- **Node.js:** v18+ veya v20+

---

## 🚀 Hızlı Başlangıç & Kurulum Rehberi

### 1. Projeyi Klonlayın ve Bağımlılıkları Kurun
```bash
git clone https://github.com/ismailkorkmaz6806/alsepete.git
cd alsepete
composer install
npm install
```

### 2. Ortam Değişkenlerini Yapılandırın
```bash
cp .env.example .env
php artisan key:generate
```
*(İsteğe Bağlı: Google Gemini AI Asistanını canlı LLM olarak kullanmak için `.env` dosyanıza `GEMINI_API_KEY=your_key_here` ekleyebilirsiniz. Anahtar girilmezse dahili akıllı motor otomatik devreye girer).*

### 3. Veritabanını Oluşturun ve Seed Edin (100 Ürün + Test Kullanıcıları)
```bash
php artisan migrate:fresh --seed
```

### 4. Depolama Köprüsünü Kurun ve Projeyi Başlatın
```bash
php artisan storage:link
npm run build
php artisan serve
```
Tarayıcınızda açın: **`http://127.0.0.1:8000`**

---

## 🧪 Test ve Kod Kalitesi

Tüm iş kuralları, güvenlik mekanizmaları, sayfalama, sıralama ve yapay zeka asistanı **67 adet otomatik test** ile korunmaktadır:

```bash
# Tüm Feature ve Unit Testlerini Çalıştırın (67 Test, 176 Assertion)
php artisan test

# Sadece Kategori & Sıralama Testlerini Çalıştırın
php artisan test --filter=CategoryBrowseTest

# Sadece AI Asistan Testlerini Çalıştırın
php artisan test --filter=AiShoppingAssistantTest

# Sadece Satıcı Soru-Cevap & Bonus Testlerini Çalıştırın
php artisan test --filter=BonusFeaturesTest

# PSR-12 Standart Kontrolü (Laravel Pint)
./vendor/bin/pint --test
```

---

## 📁 Hafta 1, 2 ve 3 Teslimat Belgeleri
- **ER Diyagramı:** `docs/er-diagram.dbml` ([dbdiagram.io](https://dbdiagram.io) uyumlu)
- **Arayüz Tel Çerçevesi (Wireframe):** `docs/wireframe.md`
- **10 Haftalık Kanban Planı:** `docs/kanban-tasks.md`
- **Kategori & Sıralama Controller:** `app/Http/Controllers/CategoryShowController.php`
- **Livewire Bileşenleri:** `app/Livewire/ProductDetail.php`, `AiShoppingAssistant.php`
- **AI Alışveriş Servisi:** `app/Services/AiShoppingAssistantService.php`
- **İş Mantığı (Actions):** `app/Actions/Category/`
- **Yetkilendirme Politikaları (Policies):** `app/Policies/CategoryPolicy.php`
- **Dil Dosyaları:** `lang/tr/`, `lang/en.json`, `lang/tr.json`
