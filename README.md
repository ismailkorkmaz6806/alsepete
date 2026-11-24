# 🛍️ AlSepete - Çok Satıcılı E-Ticaret Pazaryeri Platformu

Amazon ve Hepsiburada iş modeline dayalı; modern, yüksek performanslı ve tam teşekküllü bir Laravel e-ticaret pazaryeri projesi.

[![Laravel Version](https://img.shields.io/badge/Laravel-11%20%2F%2012-red?logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%20%2F%208.3-blue?logo=php)](https://php.net)
[![Livewire 3](https://img.shields.io/badge/Livewire-3.x-fb70a9?logo=livewire)](https://livewire.laravel.com)
[![Tests](https://img.shields.io/badge/Tests-102%20Passing%20(318%20Assertions)-brightgreen?logo=pest)](https://pestphp.com)
[![Code Style](https://img.shields.io/badge/Code%20Style-PSR--12%20(Pint)-success)](https://laravel.com/docs/pint)
[![AI Engine](https://img.shields.io/badge/AI-Google%20Gemini%203.5-orange?logo=google)](https://ai.google.dev)
[![Release](https://img.shields.io/badge/Release-v0.5%20(Hafta%205%20--%20Sepet%2C%20Favoriler%2C%20Adres%20ve%20Kupon)-indigo)](https://github.com/ismailkorkmaz6806/alsepete)

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

## 🌟 Hafta 5 (v0.5) Teslim Edilen Modüller & Kabul Kriterleri

Hafta 5 şartnamesi ve Kanban yol haritasında yer alan **Sepet, Favoriler, Adres Defteri ve Kupon Motoru** eksiksiz tamamlanmıştır:

### 1. Canlı Çok Satıcılı Sepet Yönetimi (Livewire 3 & CartService)
- **Çok Satıcılı Gruplama:** Sepetteki ürünler satıcı bazında ayrıştırılarak satıcı kartları halinde listelenir.
- **Satıcı Başına Kargo Mantığı:** Her satıcı için kargo ücreti 49.90 TL olarak bağımsız hesaplanır. **300 TL ve üzeri siparişlerde satıcı bazında kargo ücretsizdir.** Kalan tutara göre dinamik kargo baremi ilerleme çubuğu gösterilir.
- **Misafir Sepeti Birleştirme:** Giriş yapmamış kullanıcıların sepeti oturumda (`session`) tutulur. Kullanıcı giriş yaptığında (`login`) veya kayıt olduğunda (`register`), misafir sepeti kullanıcının veritabanı sepetine otomatik aktarılır.
- **Sepet Özeti & Sayacı:** Navbar'da anlık güncellenen sepet sayacı badge'i (`CartCounter`) ve sipariş özeti (Ara Toplam, Satıcı Kargo Ücretleri, Kupon İndirimi, Genel Toplam).

### 2. Stok Aşımı Koruması (Kabul Kriteri)
- Ürün detayında veya sepet sayfasında varyantın mevcut stok miktarından (`ProductVariant.stock`) fazla adet eklenemez veya artırılamaz.
- Aşım durumunda kullanıcıya Türkçe uyarı mesajı verilir ve işlem engellenir.

### 3. Favoriler Sistemi (Livewire 3)
- **Kalp Butonu (`FavoriteButton`):** Ürün kartlarında ve ürün detay sayfasında interaktif kalp butonu ile tek tıkla favorilere ekleme/çıkarma.
- **Misafir Yönlendirmesi:** Giriş yapmamış kullanıcı favori butonuna tıkladığında otomatik olarak giriş yapma sayfasına yönlendirilir.
- **Müşteri Favorilerim Sayfası:** `/favorilerim` rotasında favoriye eklenen ürünler listelenir, tek tıkla sepete ekleme veya favorilerden çıkarma imkanı sunulur.

### 4. Adres Defteri Yönetimi & Yetkilendirme (AddressPolicy)
- **Adres Yönetim Arayüzü (`/hesabim/adresler`):** Modal destekli teslimat ve fatura adresi ekleme, düzenleme ve silme.
- **81 İl ve İlçe Seçimi (`LocationService`):** İl seçildiğinde ilgili ile ait ilçelerin reaktif olarak doldurulduğu dinamik açılır menü.
- **Varsayılan Adres Belirleme:** Tek tıkla varsayılan teslimat adresi seçebilme özelliği.
- **AddressPolicy Koruması:** Bir kullanıcı başka bir kullanıcının adresini görüntüleyemez, güncelleyemez veya silemez (`403 Forbidden`).

### 5. Kupon Motoru & Kabul Kriterleri (CouponService)
- **Kupon Türleri:** Yüzde (`percentage`) ve sabit tutar (`fixed`) kuponları. Sabit kupon indirimi sepet ara toplamını aşamaz.
- **Doğrulama Kuralları:** Kupon başlangıç/bitiş tarihleri, toplam kullanım limiti (`used_count < usage_limit`) ve minimum sepet tutarı kontrolü.
- **Kabul Kriteri (Zorunlu):** Sepet hesabı (ara toplam, kargo, indirim, genel toplam) için **en az 5 birim test** yazılmıştır (`tests/Unit/CartCalculationTest.php` altında 8 birim test). Süresi dolmuş kupon denendiğinde Türkçe hata mesajı döner.

---

## 🌟 Hafta 4 (v0.4 - Ara Demo 1) Teslim Edilen Modüller & Kabul Kriterleri

Hafta 4 şartnamesi ve Kanban yol haritasında yer alan **Arama, Filtreleme ve Satıcı Paneli** ile seçilen ek bonus eksiksiz tamamlanmıştır:

### 1. Canlı Arama Kutusu & Autocomplete Önerisi (Livewire 3)
- **Yazarken Canlı Öneri:** Navbar üzerindeki arama kutusuna yazarken (250ms debounce) eşleşen ürünler (küçük resim, başlık, marka, fiyat), markalar ve kategoriler anlık açılır pencerede listelenir.
- **Gelişmiş Arama Sonuç Sayfası:** `/arama?q=...` rotası ile başlık, açıklama ve marka içinde tam metin arama.

### 2. URL Parametreli Gelişmiş Filtreler
- **Filtreler:** Kategori, Marka (`?marka=sony`), Fiyat Aralığı (`?min=100&max=500`), Renk (`?renk=siyah`), Beden/Boyut (`?beden=M`), Puan ve Stok durumu (`?stok=1`).
- **Kabul Kriteri:** Filtreler URL parametrelerinde saklanır; filtreli bir sayfanın linki paylaşıldığında veya sayfalama yapıldığında aynı sonuçlar birebir gelir.

### 3. Satıcı Başvuru Formu & Admin Onay/Red Paneli
- **Başvuru Formu:** Müşterilerin satıcı olmak için başvurduğu `/satici-ol` ekranı (Mağaza Adı, Vergi Kimlik No, IBAN).
- **Statü Güvenliği:** Başvuru yapıldığında satıcı durumu admin onaylayana kadar `"beklemede"` olarak kaydedilir ve onaylanana kadar satıcı paneline erişilemez (`403 Forbidden`).
- **Admin Yönetim Ekranı:** `/admin/saticilar` panelinden admin bekleyen başvuruları tek tıkla onaylar (`onayli`) veya reddeder. Onaylanan kullanıcıya otomatik `seller` rolü atanır.

### 4. Satıcı Paneli - Ürün Yönetimi (CRUD, Hızlı Stok & Durum Anahtarı)
- **Ürün Listesi:** `/satici/urunler` rotasında satıcı yalnızca kendi mağazasına ait ürünleri, varyantlarını ve anlık stoklarını listeler.
- **Ürün Ekleme & Düzenleme:** `/satici/urunler/yeni` ve `/satici/urunler/{id}/duzenle` ile görsel, kategori, marka, açıklama ve varyant (fiyat, indirimli fiyat, stok, sku, renk, beden) yönetimi.
- **Hızlı Stok & Durum Değiştirici:** Satıcı liste üzerinden doğrudan stok güncelleyebilir ve ürünü tek tıkla Aktif/Pasif yapabilir.

### 5. Yetkilendirme Politikası (ProductPolicy) - [Zorunlu Kabul Kriteri]
- **Kabul Kriteri:** *Satıcı A, Satıcı B'nin ürün düzenleme adresini (`/satici/urunler/{b_product}/duzenle`) elle yazarsa anında **403 Forbidden** alır.*
- Satıcılar yalnızca kendi mağazalarına ait ürünleri düzenleyebilir ve silebilir (`$user->seller->id === $product->seller_id`).
- Bu kural `tests/Feature/SellerProductAuthorizationTest.php` altındaki 5 adet uçtan uca otomatik test ile güvence altına alınmıştır.

### 6. Herkese Açık Mağaza Vitrin Sayfası
- **SEO Uyumlu Mağaza Rotası:** `/magaza/{slug}` (Örn: `/magaza/tekromarket`).
- **Mağaza Profili:** Satıcı adı, onaylı mağaza rozeti, üyelik tarihi, toplam aktif ürün sayısı.
- **Mağaza İçi Arama & Filtreleme:** Yalnızca o satıcıya ait aktif ürünlerin 24'lük sayfalamayla listelendiği, marka/kategori/fiyat filtreli vitrin.

### 7. Bonus Görev (+2 Puan) - İyzico Taksit Seçenekleri Tablosu
- **Taksit Motoru (`InstallmentService`):** Anlaşmalı 6 büyük banka kart ailesi (Bonus, Maximum, World, Axess, CardFinans, Paraf) için 2, 3, 6, 9, 12 taksit ve aylık ödeme planı hesaplama.
- **İnteraktif Arayüz:** Ürün detay sayfasında (`/urun/{slug}`) "Taksit Seçenekleri" butonu ve banka logolu, sekmeli açılır taksit tablosu modalı.

---

## 🌟 Hafta 3 (v0.3) Teslim Edilen Modüller & Kabul Kriterleri

- **Canlı Varyant Seçimi & Ürün Detay:** Livewire 3 ile renk, beden ve fiyat değişimi, stok koruması.
- **Kategori Sayfası:** 24 ürünlük sayfalama (`paginate(24)`), 4 mod sıralama, marka & stok filtreleri.
- **Google Gemini 3.5 Flash Alışveriş Asistanı:** Canlı çok turlu sohbet hafızası, veritabanı ürün grounding'i, kurumsal SSS.
- **Bonus 1 & 2:** "Bu Ürünü Alanlar Bunları da Aldı" önerisi ve Satıcı Soru-Cevap sistemi.

---

## 🚀 Hafta 2 (v0.2) Teslim Edilen Modüller

- **Kimlik Doğrulama & Roller (Spatie / Breeze):** `admin`, `seller`, `customer` rolleri.
- **Rol Tabanlı Rota Koruması (Middleware):** `EnsureUserIsAdmin` (`/admin/*`) ve `EnsureSellerIsApproved` (`/satici/*`).
- **Kategori Yönetimi (Admin CRUD) - İnce Controller & Action Mimarisi:** `CreateCategoryAction`, `UpdateCategoryAction`, `DeleteCategoryAction`, `CategoryPolicy`.
- **Ortak Düzen & Dinamik Mega Menü:** `View::composer` ile önbellekli kategori ağacı.
- **Çoklu Dil Desteği:** Türkçe & İngilizce tam yerelleştirme (`/dil/tr` & `/dil/en`).

---

## 📋 TODO & Gelecek Hafta Planı: Hafta 6 (Ödeme ve Sipariş Oluşturma) - [ARA DEMO 2] [IN PROGRESS]

- [ ] **Checkout Sayfası:** Adres seçimi, kargo özeti ve mesafeli satış sözleşmesi.
- [ ] **iyzico Sandbox Ödeme Entegrasyonu:** 3D Secure akışı ve ödeme doğrulama.
- [ ] **Sipariş Bölme (Sub-Orders):** 1 ana sipariş (`orders`) ve satıcı başına 1 alt sipariş (`sub_orders`).
- [ ] **Eşzamanlı Stok Düşümü:** `DB::transaction` ve `lockForUpdate()` ile yarış koşulu (race condition) koruması.
- [ ] **Sipariş Onay E-postası:** Kuyruk (`Queue`) üzerinden asenkron e-posta bildirimi.
- [ ] **Siparişlerim ve Sipariş Detay:** Müşteri sipariş takibi ve satıcı bazlı alt sipariş detayları.

---

## 🔐 Hazır Test Hesapları

Veritabanı seed edildiğinde kullanıma hazır gelen hesaplar:

| Rol | E-Posta | Şifre | Erişim Alanı |
|---|---|---|---|
| **Admin** | `admin@alsepete.com` | `password` | `/admin`, `/admin/categories` ve `/admin/saticilar` |
| **Onaylı Satıcı** | `satici1@alsepete.com` | `password` | `/satici`, `/satici/urunler` ve `/satici/sorular` |
| **Müşteri** | `musteri1@alsepete.com` | `password` | Ana sayfa, `/satici-ol` başvuru ve profil |

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
```

### 2. Ortam Değişkenlerini Yapılandırın
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Veritabanını Oluşturun ve Seed Edin (100 Ürün + Test Kullanıcıları)
```bash
php artisan migrate:fresh --seed
```

### 4. Depolama Köprüsünü Kurun ve Projeyi Başlatın
```bash
php artisan storage:link
php artisan serve
```
Tarayıcınızda açın: **`http://127.0.0.1:8000`**

---

## 🧪 Test ve Kod Kalitesi

Tüm iş kuralları, yetkilendirme politikaları, arama, filtreleme, sepet, adres, favoriler ve kupon hesaplamaları **102 adet otomatik test (318 assertion)** ile güvence altındadır:

```bash
# Tüm Feature ve Unit Testlerini Çalıştırın (102 Test, 318 Assertion)
php artisan test

# Hafta 5 Sepet & Kupon Hesaplama Birim Testleri (8 Unit Test - Zorunlu Kabul Kriteri)
php artisan test --filter=CartCalculationTest

# Hafta 5 Uçtan Uca Feature Testleri (Stok, Misafir Birleştirme, Adres, Favoriler)
php artisan test --filter=Hafta5FeatureTest

# Hafta 4 Satıcı ProductPolicy Yetkilendirme Testi (403 Testi)
php artisan test --filter=SellerProductAuthorizationTest

# Hafta 4 Arama ve URL Parametreli Filtre Testi
php artisan test --filter=SearchAndFilterTest

# PSR-12 Standart Kontrolü (Laravel Pint)
./vendor/bin/pint --test
```

---

## 📁 Hafta 1 - 5 Teslimat Belgeleri ve Çekirdek Servisler
- **ER Diyagramı:** `docs/er-diagram.dbml` ([dbdiagram.io](https://dbdiagram.io) uyumlu)
- **Arayüz Tel Çerçevesi (Wireframe):** `docs/wireframe.md`
- **10 Haftalık Kanban Planı:** `docs/kanban-tasks.md`
- **Sepet Servisi & Çok Satıcılı Kargo:** `app/Services/CartService.php`, `config/alsepete.php`
- **Kupon Doğrulama & İndirim Motoru:** `app/Services/CouponService.php`
- **81 İl & İlçe Servisi:** `app/Services/LocationService.php`
- **Adres Defteri Yetkilendirmesi:** `app/Policies/AddressPolicy.php`
- **Livewire Bileşenleri:** `CartPage.php`, `CartCounter.php`, `FavoriteButton.php`, `FavoriteCounter.php`, `FavoritesPage.php`, `AddressManager.php`
- **Arama & Canlı Autocomplete:** `app/Livewire/SearchDropdown.php`, `app/Http/Controllers/SearchController.php`
- **Satıcı Ürün Politikası:** `app/Policies/ProductPolicy.php`
- **Taksit Servisi:** `app/Services/InstallmentService.php`
