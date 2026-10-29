# 🛍️ AlSepete - Çok Satıcılı E-Ticaret Pazaryeri Platformu

Amazon ve Hepsiburada iş modeline dayalı, çok satıcılı, modern bir Laravel e-ticaret pazaryeri projesi.

[![Laravel Version](https://img.shields.io/badge/Laravel-11%20%2F%2012-red?logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%2F%208.3-blue?logo=php)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-54%20Passing-brightgreen?logo=pest)](https://pestphp.com)
[![Code Style](https://img.shields.io/badge/Code%20Style-PSR--12%20(Pint)-success)](https://laravel.com/docs/pint)
[![Release](https://img.shields.io/badge/Release-v0.3%20(Hafta%203)-indigo)](https://github.com/ismailkorkmaz6806/alsepete/releases/tag/v0.3)

---

## 📌 Proje Özeti ve Çekirdek Mimari

- **Çok Satıcılı Mimari:** Satıcılar mağaza açar (`sellers`), ürün listeler ve kendi siparişlerini bağımsız yönetir.
- **Sepet ve Sipariş Bölme Mantığı:** Müşteri tek sepette 3 farklı satıcıdan ürün aldığında; sistem **1 ana sipariş (`orders`)** ve satıcı başına **1 alt sipariş (`sub_orders`)** oluşturur. Her satıcı yalnızca kendi alt siparişini görür ve kargolar.
- **Dinamik Komisyon Modeli:** Kategori (`categories.commission_rate`) ve satıcı bazlı komisyon otomatik hesaplanır.
- **3 Zorunlu Tasarım Kuralı:**
  1. Fiyatlar `decimal(10,2)` olarak saklanır (asla float kullanılmaz).
  2. Sipariş anındaki ürün adı, fiyat ve teslimat adresi sipariş tablolarına kopyalanır (`snapshot`).
  3. Stok `product_variants` tablosundadır ve `DB::transaction` + `lockForUpdate()` ile korunur.

---

## 🌟 Hafta 3 (v0.3) Teslim Edilen Modüller & Bonuslar

Hafta 3 şartnamesine ait gezilebilir vitrin, reaktif varyant yönetimi ve seçilen bonus özellikler:

### 1. Canlı Varyant Seçimi & Ürün Detay (Livewire Component)
- **Sayfa Yenilenmeden Reaktif Değişim:** Renk ve beden seçildiğinde anlık fiyat, indirim oranı ve stok miktarı Livewire ile dinamik güncellenir.
- **SEO Uyumlu URL:** `/urun/{slug}` rotası.
- **Görsel Galerisi:** Büyük ürün önizlemesi ve tıklanabilir çoklu küçük resim (thumbnail) galerisi.
- **Stok Koruması:** Stok bittiğinde *"Tükendi"* uyarısı ve sepete ekleme butonunun otomatik pasifleştirilmesi.

### 2. Kategori Sayfası (24 Ürünlük Sayfalama & Sıralama)
- **SEO Uyumlu URL:** `/kategori/{slug}` rotası.
- **24'lük Sayfalama:** `paginate(24)` ile standart e-ticaret sayfalama düzeni.
- **Sıralama Seçenekleri:** Fiyat: Düşükten Yükseğe (`price_asc`), Fiyat: Yüksekten Düşüğe (`price_desc`), En Yeniler (`newest`), En Çok Değerlendirilenler (`rating`).
- **Yan Filtreler:** Marka filtreleme ve sadece stokta olan ürünleri listeleme (`in_stock`).

### 3. Ana Sayfa Vitrini (Showcase)
- **Banner Slider:** Alpine.js tabanlı otomatik geçişli (5 sn), önceki/sonraki butonlu ve noktalı kampanya slider'ı.
- **Öne Çıkan Kategoriler:** İkonlu, alt kategori adetli ve doğrudan kategori sayfasına yönlendiren kart ızgarası.
- **Çok Satanlar & Yeni Gelenler:** Veritabanından dinamik çekilen, fırsat etiketli ürün vitrinleri.

### 4. N+1 Sorgu Koruması & Laravel Debugbar
- Tüm sayfalarda `with(['seller', 'variants', 'brand', 'category', 'media'])` kullanılarak N+1 sorgu problemi sıfıra indirilmiştir.
- Geliştirme ortamında sorguları görsel olarak denetlemek için `barryvdh/laravel-debugbar` entegre edilmiştir.

### 5. Bonus Görevler (+4 Puan)
- 🌟 **Bonus 1 (+2 Puan) - "Bu Ürünü Alanlar Bunları da Aldı":** Ürün detay sayfasında çapraz kategori ve ilgili ürün öneri algoritması.
- 🌟 **Bonus 2 (+2 Puan) - Satıcıya Soru Sor & Cevap Sistemi:** 
  - Müşterinin ürün sayfasından Livewire ile satıcıya soru sorabilmesi.
  - Onaylı satıcının kendi mağaza panelinden (`/satici/sorular`) gelen soruları görüntüleyip yanıtlaması.
  - Yanıtlanan soruların ürün detay sayfasında Soru-Cevap bloğunda otomatik yayınlanması.
- 🌟 **Bonus 3 (+2 Puan) - Çoklu Dil:** Türkçe ve İngilizce tam yerelleştirme (Hafta 2'de tamamlandı).

---

## 🚀 Hafta 2 (v0.2) Teslim Edilen Modüller

Hafta 2 gereksinimlerinin tamamı ve bonus özellikler eksiksiz tamamlanmıştır:

### 1. Kimlik Doğrulama & Roller (Spatie / Breeze)
- **Roller:** `spatie/laravel-permission` entegrasyonu ile 3 hiyerarşik rol (`admin`, `seller`, `customer`).
- **Güvenli Kayıt:** Yeni kayıt olan kullanıcılara varsayılan olarak `customer` rolü atanır.
- **Modern UI:** Giriş, Kayıt, Şifre Sıfırlama ve Profil ekranları modern Tailwind CSS, Plus Jakarta Sans tipografisi ve AlSepete marka kimliği ile baştan tasarlandı.

### 2. Rol Tabanlı Rota Koruması (Middleware)
- **Admin Koruması:** `EnsureUserIsAdmin` middleware'i ile `/admin/*` rotaları yalnızca `admin` rolüne açıktır.
- **Onaylı Satıcı Koruması:** `EnsureSellerIsApproved` (`seller.approved`) middleware'i ile `/satici/*` rotaları yalnızca sistem yöneticisince onaylanmış (`status = onayli`) satıcılara açıktır. Onaysız satıcılar ve müşteriler `403 Forbidden` yanıtı alır.

### 3. Kategori Yönetimi (Admin CRUD) - İnce Controller & Action Mimarisi
- **Thin Controller:** `CategoryController` yalnızca HTTP orkestrasyonu yapar; hiçbir metodu 7 satırı geçmez.
- **Single Responsibility (Action Sınıfları):**
  - `CreateCategoryAction`: Otomatik benzersiz slug üretimi, parent kategori ve komisyon kaydı.
  - `UpdateCategoryAction`: Güncelleme ve slug senkronizasyonu.
  - `DeleteCategoryAction`: Alt kategorilerin ve ilişkilerin güvenli silinmesi/taşınması.
- **FormRequest Validasyonu:** `CategoryStoreRequest` ve `CategoryUpdateRequest` ile Türkçe kurallar.
- **Yetkilendirme (Policy):** `CategoryPolicy` ile yalnızca admin kullanıcılar CRUD işlemi yapabilir.
- **Hiyerarşik Yapı:** Sınırsız alt kategori desteği (`parent_id`), komisyon oranı (`commission_rate`) ve vitrin sıralaması (`order`).

### 4. Ortak Düzen & Dinamik Mega Menü
- **Ortak Düzen:** `layouts.app` ve `layouts.guest` ile tutarlı navbar, header ve footer.
- **Dinamik Veri Akışı:** `AppServiceProvider` üzerinden `View::composer('*')` ile veritabanından çekilen kategoriler tüm sayfalarda önbellekli ve dinamik olarak sunulur.
- **Mega Menü:** Tıklanabilir ve açılır (dropdown) katmanlı hiyerarşik kategori ağacı.

### 5. Türkçe & Çoklu Dil Desteği (+2 Bonus Puan)
- **Türkçe Hata Mesajları:** `lang/tr/` ve `lang/tr.json` dizinleri altında Laravel doğrulama mesajları ve arayüz metinleri Türkçeleştirildi.
- **Dinamik TR / EN Dil Değiştirici:** Navbar üzerinden tek tıkla oturum ve cookie tabanlı anlık Türkçe-İngilizce geçişi (`/dil/{locale}`).

### 6. Gelişmiş Gösterge Panelleri (Dashboards)
- **Admin Dashboard (`/admin`):** Kategori sayısı (CRUD bağlantısı), onaylı satıcılar, toplam kayıtlı kullanıcı ve vitrindeki ürün metrikleri.
- **Satıcı Dashboard (`/satici`):** Mağaza adı, vergi numarası, IBAN, komisyon oranı ve Hafta 3–6 geliştirme yol haritası.

---

## 🔐 Hazır Test Hesapları

Veritabanı seed edildiğinde kullanıma hazır gelen hesaplar:

| Rol | E-Posta | Şifre | Erişim Alanı |
|---|---|---|---|
| **Admin** | `admin@alsepete.com` | `password` | `/admin` ve `/admin/categories` |
| **Onaylı Satıcı** | `satici1@alsepete.com` | `password` | `/satici` (Mağaza Paneli) |
| **Müşteri** | `musteri1@alsepete.com` | `password` | Ana sayfa & Kullanıcı profili |

---

## 💻 Sistem Gereksinimleri
- **PHP:** 8.2 veya 8.3+ (Gerekli eklentiler: `pdo_mysql` veya `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `intl`)
- **Composer:** 2.x
- **Veritabanı:** MySQL 8.x veya SQLite
- **Node.js:** LTS (v18+ veya v20+)

---

## 🚀 Hızlı Başlangıç & Kurulum Rehberi

### 1. Projeyi İndirin ve Bağımlılıkları Kurun
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
*(Not: Hızlı test için varsayılan `DB_CONNECTION=sqlite` kullanılabilir. MySQL için `.env` dosyasındaki `DB_*` alanlarını düzenleyin).*

### 3. Veritabanını Oluşturun ve Seed Edin
```bash
php artisan migrate:fresh --seed
```

### 4. Depolama Köprüsünü Kurun ve Sunucuyu Başlatın
```bash
php artisan storage:link
php artisan serve
```
Tarayıcınızda açın: **`http://127.0.0.1:8000`**

---

## 🧪 Test ve Kod Kalitesi

Projede yazılan tüm iş kuralları ve güvenlik kontrolleri otomatik testlerle güvence altındadır:

```bash
# Pest Feature ve Unit Testlerini Çalıştırın (39 Test, 88 Assertion)
php artisan test

# PSR-12 Kod Standartlarını Denetleyin
./vendor/bin/pint --test
```

---

## 📁 Hafta 1 & Hafta 2 Teslimat Belgeleri
- **ER Diyagramı:** `docs/er-diagram.dbml` ([dbdiagram.io](https://dbdiagram.io) uyumlu)
- **Arayüz Tel Çerçevesi (Wireframe):** `docs/wireframe.md`
- **10 Haftalık Kanban Planı:** `docs/kanban-tasks.md`
- **İş Mantığı (Actions):** `app/Actions/Category/`
- **Yetkilendirme Politikaları (Policies):** `app/Policies/CategoryPolicy.php`
- **Middleware Sınıfları:** `app/Http/Middleware/EnsureUserIsAdmin.php`, `EnsureSellerIsApproved.php`, `SetLocale.php`
- **Dil Dosyaları:** `lang/tr/`, `lang/en.json`, `lang/tr.json`
