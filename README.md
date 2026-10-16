# PazarYeri - Çok Satıcılı E-Ticaret Pazaryeri Platformu

Amazon ve Hepsiburada iş modeline dayalı, çok satıcılı, modern bir Laravel e-ticaret pazaryeri projesi.

---

## 📌 Proje Özeti ve Çekirdek Mantık
- **Çok Satıcılı Mimari:** Satıcılar mağaza açar (`sellers`), ürün listeler ve kendi siparişlerini yönetir.
- **Sepet ve Sipariş Bölme:** Müşteri 3 farklı satıcıdan ürün aldığında; sistem **1 ana sipariş (`orders`)** ve satıcı başına **1 alt sipariş (`sub_orders`)** oluşturur. Her satıcı sadece kendi ürünlerini görür ve kargolar.
- **Komisyon Modeli:** Kategori (`categories.commission_rate`) ve satıcı bazlı komisyon otomatik hesaplanır.
- **3 Zorunlu Tasarım Kuralı:**
  1. Fiyatlar `decimal(10,2)` olarak saklanır (asla float kullanılmaz).
  2. Sipariş anındaki ürün adı, fiyat ve teslimat adresi sipariş tablolarına kopyalanır (`snapshot`).
  3. Stok `product_variants` tablosundadır ve `DB::transaction` + `lockForUpdate()` ile korunur.

---

## 💻 Sistem Gereksinimleri
- **PHP:** 8.2 veya 8.3+ (Gerekli eklentiler: `pdo_mysql` veya `pdo_sqlite`, `mbstring`, `openssl`, `curl`)
- **Composer:** 2.x
- **Veritabanı:** MySQL 8.x veya SQLite
- **Node.js:** LTS (v18+ veya v20+)

---

## 🚀 Kurulum Rehberi (Çapraz Platform)

### A. Windows Ortamında Kurulum (Laravel Herd / Laragon / XAMPP)
1. **Projeyi Klonlayın:**
   ```powershell
   git clone <REPO_URL>
   cd pazaryeri
   ```

2. **Bağımlılıkları Yükleyin:**
   ```powershell
   composer install
   npm install
   ```

3. **Çevre Dosyasını (.env) Hazırlayın:**
   ```powershell
   copy .env.example .env
   php artisan key:generate
   ```
   *Not: MySQL kullanıyorsanız `.env` dosyasındaki `DB_DATABASE=pazaryeri` veritabanını MySQL'de oluşturun.*
   *Hızlı test için SQLite kullanmak isterseniz `.env` dosyasında `DB_CONNECTION=sqlite` yapabilirsiniz.*

4. **Veritabanını Oluşturun ve Örnek Verilerle Doldurun (Hafta 1 Kabul Kriteri):**
   ```powershell
   php artisan migrate:fresh --seed
   ```

5. **Projeyi Başlatın:**
   ```powershell
   php artisan serve
   ```
   *Arayüz derlemesi için ayrı bir terminalde:*
   ```powershell
   npm run dev
   ```

---

### B. Linux / macOS Ortamında Kurulum
1. **Projeyi Klonlayın:**
   ```bash
   git clone <REPO_URL>
   cd pazaryeri
   ```

2. **Bağımlılıkları Yükleyin:**
   ```bash
   composer install
   npm install
   ```

3. **Çevre Dosyasını (.env) Hazırlayın:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **İzinleri Düzenleyin:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

5. **Veritabanı ve Seed İşlemini Çalıştırın:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Projeyi Başlatın:**
   ```bash
   php artisan serve
   ```

---

## 🔑 Hafta 1 Demo Giriş Bilgileri

`php artisan migrate:fresh --seed` komutu çalıştırıldığında dökümandaki kabul kriterlerine uygun olarak aşağıdaki veriler otomatik üretilir:
- **1 Admin Hesabı**
  - E-posta: `admin@pazaryeri.com`
  - Şifre: `password`
  - Panel: `/admin`
- **5 Satıcı Hesabı**
  - E-postalar: `satici1@pazaryeri.com`, `satici2@pazaryeri.com`, ..., `satici5@pazaryeri.com`
  - Şifre: `password`
  - Panel: `/satici`
- **20 Müşteri Hesabı**
  - E-postalar: `musteri1@pazaryeri.com`, `musteri2@pazaryeri.com`, ..., `musteri20@pazaryeri.com`
  - Şifre: `password`
  - Panel: `/hesabim`
- **15 Hiyerarşik Kategori** (Elektronik, Moda, Ev & Yaşam, Spor, Kitap ve alt kategorileri)
- **100 Ürün ve 200 Varyant** (Gerçekçi stoklar, renk ve bedenler, `decimal(10,2)` fiyatlar)

---

## 📁 1. Hafta Teslimat Dosyaları
- **ER Diyagramı:** `docs/er-diagram.dbml` dosyasındaki kodu [dbdiagram.io](https://dbdiagram.io) sitesine yapıştırarak ilişkileri görüntüleyebilirsiniz.
- **Ana Sayfa Wireframe:** `docs/wireframe.md` dosyasında UI blok yapısı ve bileşen detayları bulunmaktadır.
- **Kanban Görev Panosu:** `docs/kanban-tasks.md` dosyasında 10 haftalık GitHub Projects görev listesi yer alır.
- **Veritabanı Modelleri:** `app/Models/` altında 18 Eloquent modeli ve ilişkileri tanımlanmıştır.
- **Migration Dosyaları:** `database/migrations/` altında 20 tablonun şeması eksiksiz yer almaktadır.
