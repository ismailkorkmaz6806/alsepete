# 🎯 1. Hafta Sunumunda Ne Yapacaksın? (Adım Adım Tam Rehber)

Bu belge, 1. Hafta sunumunda hocanın karşısına geçtiğinde **hem teknolojileri derinlemesine açıklayabilmen hem de adımları sırasıyla gösterebilmen için** hazırlanmıştır.

---

## 🧠 BÖLÜM 1: Kullandığımız Teknolojiler Nedir ve Ne İşe Yarar? (Hocaya Anlatım)

Hoca *"Neden bu teknolojileri seçtiniz, bunlar ne işe yarıyor?"* diye sorduğunda anlatacağın detaylı açıklamalar:

### 1. Laravel 13 Nedir ve Ne İşe Yarar?
> **Hocaya Anlatım:**
> *"Hocam, Laravel PHP dünyasının en popüler ve en güçlü açık kaynaklı web uygulama çatısıdır (framework). 
> 
> Düz (saf) PHP yazmak yerine Laravel kullanmamızın sebepleri şunlardır:
> - **MVC Mimarisi:** Kodu Model (Veri/Veritabanı), View (Arayüz/Tasarım) ve Controller (İş Mantığı) olarak ayırır; projenin binlerce satırda bile düzenli kalmasını sağlar.
> - **Eloquent ORM:** Veritabanına karmaşık SQL sorguları yazmak yerine `$urun->variants` veya `$user->orders` gibi nesne tabanlı (Object-Oriented) ve son derece hızlı erişim sağlar.
> - **Artisan Komut Motoru:** Terminalden tek komutla migration'ları çalıştırmamızı (`php artisan migrate`), modeller üretmemizi ve yerel geliştirme sunucusunu (`php artisan serve`) ayağa kaldırmamızı sağlar.
> - **Dahili Güvenlik:** SQL Injection, XSS (siteler arası betik çalıştırma) ve CSRF saldırılarına karşı otomatik koruma kalkanıyla gelir.
> - **Pazaryeri İçin Uygunluğu:** Büyük ölçekli e-ticaret sitelerinin ihtiyaç duyduğu kuyruk yönetimi (Queue), bildirimler (Notifications) ve zamanlanmış görevleri (Cron) kutudan çıktığı gibi destekler."*

---

### 2. PHP 8.3 Nedir?
> **Hocaya Anlatım:**
> *"PHP'nin en güncel ve en hızlı kararlı sürümlerinden biridir. Eski PHP sürümlerine göre JIT (Just-In-Time) derleyicisi sayesinde %30 daha hızlı çalışır, bellek tüketimi çok düşüktür ve tip güvenliği (Typed Properties) sunar."*

---

### 3. MySQL 8 Nedir?
> **Hocaya Anlatım:**
> *"Dünyanın en güvenilir ilişkisel veritabanı yönetim sistemidir (RDBMS). 
> - Pazaryerimizde 20 tablonun birbirine Foreign Key (Yabancı Anahtar) ile bağlanmasını ve ilişkisel bütünlüğü (Referential Integrity) sağlar.
> - ACID standartlarına tam uyar; yani ödeme anında para çekilip stok düşülürken bir hata olursa işlemi geri alarak (Rollback) verinin bozulmasını engeller."*

---

### 4. Blade ve Tailwind CSS Nedir?
> **Hocaya Anlatım:**
> - **Blade:** Laravel'in şablon motorudur. HTML içerisine temiz PHP kodları ve döngüler (`@foreach`, `@if`) yazmamızı sağlar, kodu optimize ederek düz PHP'den bile hızlı derler.
> - **Tailwind CSS:** Modern 'Utility-first' CSS kütüphanesidir. Hazır hantal stiller yerine mikro sınıflarla mobil uyumlu (Responsive), esnek ve modern arayüzler tasarlamamızı sağlar.

---

### 5. Livewire Nedir?
> **Hocaya Anlatım:**
> *"React veya Vue gibi karmaşık JavaScript kütüphanelerine ve API yazma zahmetine gerek kalmadan; doğrudan Laravel içinde sayfayı yenilemeden dinamik (canlı) çalışan bileşenler üretmemizi sağlar (Örneğin 3. haftada varyant seçildiğinde fiyatın anında değişmesi Livewire ile yapılacaktır)."*

---

### 6. Spatie/Laravel-Permission Nedir?
> **Hocaya Anlatım:**
> *"Sektör standardı yetkilendirme paketidir. Kullanıcılara `admin`, `seller` ve `customer` rollerini atamamızı ve middleware üzerinden sayfaları korumamızı sağlar."*

---

### 7. Spatie/Laravel-MediaLibrary Nedir?
> **Hocaya Anlatım:**
> *"Ürünlerin çoklu fotoğraflarını yüklemek, otomatik olarak küçük önizleme (thumbnail) boyutlarını üretmek ve bunları veritabanıyla ilişkilendirmek için kullandığımız görsel yönetim kütüphanesidir."*

---

### 8. Pest PHP ve Laravel Pint Nedir?
> **Hocaya Anlatım:**
> - **Pest:** PHPUnit üzerine kurulu, insan diline çok yakın ve son derece modern bir test çatısıdır.
> - **Laravel Pint:** Ekip üyelerinin yazdığı kodları otomatik olarak tarayıp PSR-12 uluslararası kod standartlarına göre temizleyen ve hizalayan kod kalitesi aracıdır.

---

### 9. dbdiagram.io (DBML) Nedir?
> **Hocaya Anlatım:**
> *"Veritabanı tablolarını ve aralarındaki `1-N`, `1-1`, `N-N` ilişkilerini kodla (DBML) tanımlayıp görselleştiren, şartnamede hocamızın da önerdiği profesyonel ER diyagramı aracıdır."*

---

## 🖥️ BÖLÜM 2: Sunum Öncesi Hazırlık (2 Dakika Önce)

1. **Terminalde:** `cd ~/Desktop/pazaryeri && php artisan serve` çalışır durumda kalsın.
2. **Google Chrome'da 3 Sekme Aç:**
   - **Sekme 1:** `http://127.0.0.1:8000` (Projenin canlı ana sayfası)
   - **Sekme 2:** `https://dbdiagram.io` (ER Diyagramı)
   - **Sekme 3:** GitHub reponuz

---

## 🎤 BÖLÜM 3: Sunum Akışı ve Konuşma Senaryosu

### 1. Adım: Proje & Teknolojiler (1.5 Dakika)
> *"Hocam merhaba. Amazon ve Hepsiburada mimarisinde çok satıcılı bir E-Ticaret Pazaryeri geliştiriyoruz. 
> 
> Teknoloji yığınımızda şartnamenin zorunlu kıldığı **PHP 8.3, Laravel 13 ve MySQL 8** kullandık. Arayüzde **Blade, Livewire ve Tailwind CSS**, yetkilendirmede **Spatie Permission**, testlerde ise **Pest** kullanıyoruz.
> 
> Sistemin özü: Müşteri tek sepette 3 farklı satıcıdan ürün aldığında sistem 1 ana sipariş (`orders`) ve satıcı başına 3 alt sipariş (`sub_orders`) üretir. Her satıcı sadece kendi siparişini görür ve kargolar. 1. haftada bu mimarinin veritabanını ve altyapısını kurduk."*

---

### 2. Adım: ER Diyagramı ve 3 Zorunlu Kural (2 Dakika)
*`dbdiagram.io` sekmesini göster:*
> *"Şartnamedeki 20 tablonun tamamını eksiksiz modelledik ve 3 zorunlu kuralı uyguladık:
> 1. **Ondalık Fiyat:** Kuruş yuvarlama hatası olmasın diye fiyatlar `decimal(10,2)` tanımlandı.
> 2. **Snapshot Kuralı:** Satıcı ürün fiyatını veya adını sonradan değiştirirse eski siparişler bozulmasın diye sipariş anındaki değerler sipariş tablolarına kopyalandı.
> 3. **Stok Kuralı:** Stok varyant tablosunda tutuldu."*

---

### 3. Adım: Canlı Sitedeki Verileri Göster (2 Dakika)
*`http://127.0.0.1:8000` sayfasına geç, ürünleri göster:*
> *"Gördüğünüz gibi ana sayfamız doğrudan MySQL veritabanına bağlıdır. 1. haftanın kabul kriteri olan **15 Hiyerarşik Kategori**, ürünler, onaylı satıcı mağaza isimleri (`TeknoMarket`, `ModaTrend`) ve TL fiyatları az önce veritabanına aktardığımız gerçek verilerdir. 
> Seeder'ımız **1 admin, 5 satıcı, 20 müşteri, 15 kategori ve 100 ürünü** eksiksiz oluşturmuştur."*

---

### 4. Adım: Çapraz Platform ve Kapanış (1 Dakika)
> *"Biz projeyi Linux'ta geliştirdik ancak sizin Windows üzerinde (Herd veya Laragon ile) hatasız çalıştırabilmeniz için `.gitattributes` ile satır sonlarını normalize ettik ve PSR-4 standartlarına uyduk. README dosyamızda tüm adımlar hazırdır. Teşekkürler!"*
