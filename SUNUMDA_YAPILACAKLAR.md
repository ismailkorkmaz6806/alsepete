# 🎯 1. Hafta Sunumunda Ne Yapacaksın? (Adım Adım Kopya Kağıdı)

Bu belge, hocanın PDF şartnamesindeki 1. Hafta hedeflerine, kabul kriterlerine ve teknoloji yığınına **%100 uygun olarak** hazırlanmıştır.

---

## 🛠️ Kullandığımız Teknoloji Yığını (Hocaya Söyleyeceğin Liste)
*Hoca "Hangi teknolojileri kullandınız?" diye sorduğunda şartnamedeki bu tabloyu söyleyeceksin:*

| Katman | Kullandığımız Teknoloji | Açıklama |
| :--- | :--- | :--- |
| **Dil ve Çatı** | **PHP 8.3 + Laravel 13** | Şartnamenin zorunlu seçimi |
| **Veritabanı** | **MySQL 8** | 20 tablonun tamamı MySQL 8 üzerinde |
| **Arayüz (Frontend)** | **Blade + Livewire + Tailwind CSS** | Şartnamenin zorunlu seçimi |
| **Kimlik Doğrulama** | **Laravel Breeze** | Resmi starter kit |
| **Yetkilendirme (Roller)** | **spatie/laravel-permission** | Admin, Seller, Customer rolleri |
| **Görsel Yönetimi** | **spatie/laravel-medialibrary** | Çoklu ürün fotoğrafları için |
| **Test Çatısı** | **Pest PHP** | Modern birim ve özellik testleri |
| **Kod Kalitesi** | **Laravel Pint** | PSR-12 ve Laravel kod standardı |
| **ER Diyagramı** | **dbdiagram.io (DBML)** | 20 tablonun ilişkisel şeması |

---

## 📊 Veritabanı Diyagramı Şartnameye Uygun mu?
**Evet, %100 birebir uygundur!** 

PDF'in 3. ve 4. sayfalarındaki zorunlu 20 tablonun tamamı `docs/er-diagram.dbml` dosyasında çizilmiştir:
1. `users`, 2. `addresses`, 3. `sellers`, 4. `categories`, 5. `brands`, 6. `products`, 7. `product_variants`, 8. `media`, 9. `carts/cart_items`, 10. `favorites`, 11. `coupons`, 12. `orders`, 13. `sub_orders`, 14. `order_items`, 15. `payments`, 16. `returns`, 17. `reviews`, 18. `seller_payouts`, 19. `banners`, 20. `spatie permission tabloları`.

Ve şartnamedeki 3 zorunlu kural işlenmiştir:
- Fiyatlar `decimal(10,2)` (asla float değil).
- Sipariş anındaki ürün adı, fiyat ve adres kopyalandı (`snapshot`).
- Stok varyant tablosunda tutuldu.

---

## 🖥️ 1. AŞAMA: Sunumdan 2 Dakika Önce Bilgisayarında Hazır Olacaklar

1. **Terminal Açık Olsun:**
   ```bash
   cd ~/Desktop/pazaryeri
   php artisan serve
   ```
   *(Bu terminal arkada açık kalsın).*

2. **Google Chrome'da 3 Sekme Açık Olsun:**
   - **Sekme 1:** `http://127.0.0.1:8000` (Projenin açık ana sayfası)
   - **Sekme 2:** `https://dbdiagram.io` (ER Diyagramı)
   - **Sekme 3:** GitHub reponuz

---

## 🎤 2. AŞAMA: Sunum Başlayınca Sırasıyla Ne Yapacaksın? (Konuşma Senaryosu)

### 1. Adım: Projeyi ve Teknolojileri Tanıt (1.5 Dakika)
*Ekranda ana sayfa açıkken hocaya dön ve şunu söyle:*
> *"Hocam merhaba. Biz projemizde Amazon ve Hepsiburada mantığında çalışan, çok satıcılı bir E-Ticaret Pazaryeri platformu geliştiriyoruz.*
> 
> *Teknoloji yığını olarak şartnamenin zorunlu kıldığı **PHP 8.3, Laravel 13, MySQL 8, Blade, Livewire ve Tailwind CSS** kullandık. Yetkilendirmede `spatie/laravel-permission`, testlerde ise `Pest` kullanıyoruz.*
> 
> *Pazaryerimizin özü şudur: Bir müşteri tek bir sepette 3 farklı satıcıdan ürün aldığında sistem 1 ana sipariş (`orders`) ve satıcı başına 1 alt sipariş (`sub_orders`) oluşturur. Her satıcı sadece kendi siparişini görür ve kargolar.*
> 
> *1. haftadaki görevimiz bu mimarinin veritabanını tasarlamak, 20 tabloyu kurmak ve kabul kriteri olan örnek verileri üretmekti."*

---

### 2. Adım: ER Diyagramını Göster (2 Dakika)
*Chrome'da `dbdiagram.io` sekmesine tıkla, renkli tabloları göstererek şunu söyle:*
> *"Hocam, şartnamedeki zorunlu 20 tablonun tamamını eksiksiz modelledik ve dbdiagram.io üzerinde çizdik. Ayrıca dökümandaki 3 zorunlu kuralı veritabanına uyguladık:*
> 1. *Kuruş yuvarlama hatası olmasın diye tüm fiyatlar `decimal(10,2)` tanımlandı.*
> 2. *Satıcı ürün fiyatını sonradan değiştirirse eski sipariş bozulmasın diye; sipariş anındaki ürün adı, fiyat ve adres sipariş tablolarına anlık kopya (`snapshot`) olarak alındı.*
> 3. *Stok en alt kırılım olan `product_variants` tablosunda tutuldu."*

---

### 3. Adım: Canlı Sitedeki Verileri Göster (2 Dakika)
*Tekrar `http://127.0.0.1:8000` sekmesine geç, sayfayı aşağı kaydırarak ürünleri göster:*
> *"Hocam gördüğünüz gibi ana sayfamız doğrudan MySQL veritabanımıza bağlıdır:*
> - *1. Haftanın kabul kriteri olan **15 Hiyerarşik Kategori** üst menüde yer alıyor.*
> - *Aşağıda gördüğünüz ürünler, satıcı mağaza adları (`TeknoMarket`, `ModaTrend` vb.) ve `TL` fiyatları doğrudan MySQL veritabanımızdan çekilmektedir.*
> - *Seeder'ımız şartnamenin istediği gibi **1 admin, 5 satıcı, 20 müşteri, 15 kategori ve 100 ürünü** eksiksiz oluşturmuştur."*

---

### 4. Adım: Çapraz Platform ve Kapanış (1 Dakika)
*VS Code veya dosya yöneticisinde proje klasörünü göster:*
> *"Hocam kod mimarimizde:*
> - *Modellerimiz arasında `hasMany` ve `belongsTo` ilişkileri kuruldu.*
> - *Biz projeyi Linux'ta geliştirdik ancak sizin Windows üzerinde (Herd, Laragon veya XAMPP ile) hatasız çalıştırabilmeniz için `.gitattributes` ile satır sonlarını normalize ettik ve PSR-4 standartlarına uyduk.*
> - *Kurulum adımları `README.md` dosyamızda detaylıca yazmaktadır.*
> - *Git üzerinde `main` ve `develop` dallarımızı ayırdık ve 1. hafta teslimatımızı `v0.1` olarak etiketledik. Teşekkürler!"*

---

## ❓ 3. AŞAMA: Hoca Ne Sorabilir? (Hazır Cevaplar)

- **Hoca Sorarsa:** *"Veritabanında kaç tablo var?"*
  - **Cevabın:** *"Şartnamede zorunlu tuttuğunuz 20 tablonun tamamı var hocam (users, sellers, categories, products, variants, orders, sub_orders, media, carts, cart_items, coupons vb.)."*

- **Hoca Sorarsa:** *"Neden float değil de decimal yaptınız?"*
  - **Cevabın:** *"Float kayan noktalı hesaplama yaptığı için finansal işlemlerde kuruş yuvarlama hatası verir. E-ticarette finansal standart daima decimal(10,2)'dir."*

- **Hoca Sorarsa:** *"Siparişte adres neden JSON tutuluyor?"*
  - **Cevabın:** *"Müşteri 1 sene sonra adres defterindeki adresini silse veya değiştirse bile geçmiş siparişin faturasındaki teslimat adresi bozulmasın diye (Snapshot kuralı)."*

- **Hoca Sorarsa:** *"Canlı test edebilir miyiz?"*
  - **Cevabın:** *"Tabii ki hocam, terminalde `php artisan migrate:fresh --seed` komutunu çalıştırırsanız tüm tablolar sıfırdan hatasız kurulur ve 100 ürünle dolar."*
