# 🎯 1. Hafta Sunumunda Ne Yapacaksın? (Adım Adım Kopya Kağıdı)

Bu belge, 1. Hafta sunumunda hocanın karşısına geçtiğinde **panik yapmadan, sırasıyla ne göstereceğini ve ne söyleyeceğini** en basit haliyle anlatır.

---

## 🖥️ 1. AŞAMA: Sunuma Başlamadan Önce Bilgisayarında Hazır Olacaklar
*(Hoca çağırmadan 2 dakika önce bu 3 şeyi hazırla)*:

1. **Terminal Açık Olsun:**
   ```bash
   cd ~/Desktop/pazaryeri
   php artisan serve
   ```
   *(Bu terminal arkada açık kalsın).*

2. **Google Chrome'da 3 Sekme Açık Olsun:**
   - **Sekme 1:** `http://127.0.0.1:8000` (Projenin açık ana sayfası)
   - **Sekme 2:** `https://dbdiagram.io` (ER diyagramı)
   - **Sekme 3:** GitHub reponuz

---

## 🎤 2. AŞAMA: Sunum Başlayınca Sırasıyla Ne Yapacaksın? (Konuşma Senaryosu)

### 1. Adım: Projeyi Tanıt (1 Dakika)
*Ekranda projenin ana sayfası açıkken hocaya dön ve şunu söyle:*
> *"Hocam merhaba. Projemiz Amazon ve Hepsiburada mantığında çalışan çok satıcılı bir pazaryeri platformudur.*
> 
> *Sistemin özü şudur: Bir müşteri tek bir sepette birden fazla satıcıdan ürün aldığında sistem 1 ana sipariş (`orders`) ve satıcı başına 1 alt sipariş (`sub_orders`) oluşturur. Her satıcı sadece kendi siparişini görür ve kargolar.*
> 
> *1. haftadaki görevimiz bu mimarinin veritabanını tasarlamak, 20 tabloyu kurmak ve kabul kriteri olan örnek verileri üretmekti."*

---

### 2. Adım: ER Diyagramını Göster (2 Dakika)
*Chrome'da `dbdiagram.io` sekmesine tıkla, renkli tabloları göstererek şunu söyle:*
> *"Hocam, şartnamedeki zorunlu 20 tablonun tamamını eksiksiz modelledik. Ayrıca dökümandaki 3 zorunlu kuralı veritabanına uyguladık:*
> 
> 1. *Kuruş yuvarlama hatası olmasın diye tüm fiyatlar `decimal(10,2)` tanımlandı.*
> 2. *Satıcı ürün fiyatını sonradan değiştirirse eski sipariş bozulmasın diye; sipariş anındaki ürün adı, fiyat ve adres sipariş tablolarına anlık kopya (`snapshot`) olarak alındı.*
> 3. *Stok en alt kırılım olan `product_variants` tablosunda tutuldu."*

---

### 3. Adım: Canlı Sitedeki Verileri Göster (2 Dakika)
*Tekrar `http://127.0.0.1:8000` sekmesine geç, sayfayı aşağı kaydırarak ürünleri göster:*
> *"Hocam gördüğünüz gibi ana sayfamız dinamiktir ve veritabanına bağlıdır:*
> - *1. Haftanın kabul kriteri olan **15 Hiyerarşik Kategori** üst menüde yer alıyor.*
> - *Aşağıda gördüğünüz ürünler, satıcı mağaza adları (`TeknoMarket`, `ModaTrend` vb.) ve `TL` fiyatları doğrudan MySQL veritabanımızdan çekilmektedir.*
> - *Seeder'ımız şartnamenin istediği gibi **1 admin, 5 satıcı, 20 müşteri, 15 kategori ve 100 ürünü** eksiksiz oluşturmuştur."*

---

### 4. Adım: Kodları ve Çapraz Platformu Belirt (1 Dakika)
*VS Code veya dosya yöneticisinde proje klasörünü göster:*
> *"Hocam kod mimarimizde:*
> - *Modellerimiz arasında `hasMany` ve `belongsTo` ilişkileri kuruldu.*
> - *Biz projeyi Linux'ta geliştirdik ancak sizin Windows üzerinde (Herd, Laragon veya XAMPP ile) hatasız çalıştırabilmeniz için `.gitattributes` ile satır sonlarını normalize ettik ve PSR-4 standartlarına uyduk.*
> - *Kurulum adımları `README.md` dosyamızda detaylıca yazmaktadır.*
> - *Git üzerinde `main` ve `develop` dallarımızı ayırdık ve 1. hafta teslimatımızı `v0.1` olarak etiketledik."*

---

## ❓ 3. AŞAMA: Hoca Ne Sorabilir? (Hazır Cevaplar)

- **Hoca Sorarsa:** *"Veritabanında kaç tablo var?"*
  - **Cevabın:** *"Şartnamede zorunlu tuttuğunuz 20 tablonun tamamı var hocam (users, sellers, categories, products, variants, orders, sub_orders vb.)."*

- **Hoca Sorarsa:** *"Neden float değil de decimal yaptınız?"*
  - **Cevabın:** *"Float veri tipi kayan noktalı hesaplama yaptığı için finansal işlemlerde kuruş yuvarlama hatası verir. E-ticarette finansal standart daima decimal(10,2)'dir."*

- **Hoca Sorarsa:** *"Siparişte adres neden JSON tutuluyor?"*
  - **Cevabın:** *"Müşteri 1 sene sonra adres defterindeki adresini silse veya değiştirse bile geçmiş siparişin faturasındaki teslimat adresi bozulmasın diye (Snapshot kuralı)."*

- **Hoca Sorarsa:** *"Canlı test edebilir miyiz?"*
  - **Cevabın:** *"Tabii ki hocam, terminalde `php artisan migrate:fresh --seed` komutunu çalıştırırsanız tüm tablolar sıfırdan hatasız kurulur ve 100 ürünle dolar."*
