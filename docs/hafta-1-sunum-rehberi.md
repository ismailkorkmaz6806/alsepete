# 🎙️ 1. Hafta Demo ve Sunum Kılavuzu (10 Dakika Tam Rehber)

Bu doküman, hocanıza ve jüriye yapacağınız **10 dakikalık 1. Hafta sunumunu** en yüksek puanı alacak şekilde, panik yapmadan ve profesyonelce sunabilmeniz için adım adım hazırlanmıştır.

---

## ⏱️ 10 Dakikalık Süre Dağılımı ve Konuşma Planı

| Süre | Bölüm | Ne Anlatacaksınız? | Ne Göstereceksiniz? |
| :--- | :--- | :--- | :--- |
| **00:00 - 02:00** | **Giriş & Pazar Yeri Analizi** | Projenin amacı, Amazon ve Hepsiburada modeli | Tarayıcıda Ana Sayfa |
| **02:00 - 04:30** | **Veritabanı Mimarisi & ER Diyagramı** | 20 tablo, 3 zorunlu tasarım kuralı | dbdiagram.io ER Şeması |
| **04:30 - 07:00** | **Canlı Terminal & Seed Demosu** | Kabul kriteri: Tek komutla sıfırdan kurma | Terminalde `migrate:fresh --seed` |
| **07:00 - 08:30** | **Kod & Model İncelemesi** | Eloquent ilişkileri (hasMany, belongsTo) | VS Code / Kod Editörü |
| **08:30 - 10:00** | **Soru - Cevap** | Hocanın soracağı teknik soruları yanıtlama | Hazır cevap taktikleri |

---

## 🎬 Adım Adım Sunum Akışı ve Konuşma Metni

### 1. Adım: Giriş ve Analiz (0 - 2. Dakika)
> **Ne Söyleyeceksiniz:**
> *"Hocam merhaba. Biz bu projede Amazon ve Hepsiburada mimarisinde çalışan, çok satıcılı bir E-Ticaret Pazaryeri platformu geliştiriyoruz. 
> 
> İncelemelerimizde pazaryerini normal bir e-ticaretten ayıran en temel farkı belirledik: **Bir müşteri tek bir sepetle farklı satıcılardan ürün aldığında sistem 1 ana sipariş (`orders`) ve satıcı başına 1 alt sipariş (`sub_orders`) üretir.** Her satıcı yalnızca kendi alt siparişini görür ve kargolar. 
> 
> 1. haftadaki hedefimiz bu mimarinin sağlam temellerini atmak, 20 tabloluk veritabanı şemasını kurmak ve test verilerini üretmekti."*

---

### 2. Adım: Veritabanı ve 3 Zorunlu Kural (2 - 4. Dakika)
*(Bu esnada ekranda [dbdiagram.io](https://dbdiagram.io) üzerindeki ER diyagramınızı açın).*

> **Ne Söyleyeceksiniz:**
> *"Veritabanımızı tasarlarken şartnamedeki 3 zorunlu kuralı eksiksiz uyguladık:
> 
> 1. **Ondalık Fiyat Kuralı:** Kuruş yuvarlama hatası yapmaması için tüm fiyat alanları `decimal(10,2)` yapıldı, asla `float` kullanılmadı.
> 2. **Snapshot (Tarihçe) Kuralı:** Satıcı ürünün fiyatını veya adını sonradan değiştirirse eski siparişler bozulmasın diye; sipariş anındaki ürün adı, fiyat ve teslimat adresi JSON ve sütun kopyası olarak sipariş tablolarına aktarıldı.
> 3. **Varyant Bazlı Stok & Kilit:** Stoklar ürünün kendisinde değil `product_variants` tablosundadır. Satın alma anında `DB transaction` ve `lockForUpdate()` ile korunacak şekilde tasarlandı."*

---

### 3. Adım: Canlı Kabul Kriteri Demosu (4 - 7. Dakika) - [EN KRİTİK AN]
*(Terminalinizi açın, hocanın gözü önünde şu komutu çalıştırın):*

```bash
php artisan migrate:fresh --seed
```

> **Ne Söyleyeceksiniz:**
> *"Hocam, 1. haftanın en önemli kabul kriteri veritabanının tek komutla sıfırdan kurulup örnek verilerle dolmasıydı. Şu anda canlı olarak `php artisan migrate:fresh --seed` komutunu çalıştırıyorum.
> 
> Gördüğünüz gibi 20 tablonun tamamı ardı ardına hatasız kuruldu. Seeder'ımız çalıştı ve şartnamedeki kabul sayılarını birebir üretti:
> - **1 Admin** (`admin@pazaryeri.com`)
> - **5 Onaylı Satıcı** (TeknoMarket, ModaTrend vb.)
> - **20 Müşteri** (Varsayılan adres defterleriyle)
> - **15 Hiyerarşik Kategori** (Elektronik, Moda vb. ana ve alt kategoriler)
> - **100 Gerçekçi Ürün ve 200 Varyant**"*

*(Ardından tarayıcıda `http://127.0.0.1:8000` sayfasını yenileyin):*
> *"Bu veriler doğrudan ana sayfamızdaki vitrine akıyor; kategoriler, ürünler, onaylı satıcı rozetleri ve Türk Lirası fiyatları dinamik olarak listeleniyor."*

---

### 4. Adım: Kod Mimarisi ve Çapraz Platform (7 - 8. Dakika)
*(Kod editörünüzde `app/Models/` veya `database/migrations/` açın).*

> **Ne Söyleyeceksiniz:**
> *"Kod standartlarına tam uyduk:
> - Modellerimiz arasında `hasMany`, `belongsTo`, `belongsToMany` ilişkileri kuruldu.
> - **Çapraz Platform Güvencesi:** Projeyi Linux üzerinde geliştirdik ancak sizin Windows üzerinde (Herd, Laragon veya XAMPP ile) hatasız çalıştırabilmeniz için `.gitattributes` ile satır sonlarını normalize ettik ve PSR-4 büyük/küçük harf kurallarına harfiyen uyduk. `README.md` dosyamızda hem Windows hem Linux için çalıştırma adımları hazır."*

---

## 🎯 Hocanın Sorabileceği Muhtemel Sorular ve Hazır Cevaplarınız

### Soru 1: "Neden veritabanında SQLite kullandınız veya MySQL ile uyumlu mu?"
> **Cevabınız:** *"Hocam mimarimiz tamamen platform bağımsızdır. `.env` dosyasında hem MySQL hem SQLite tam olarak yapılandırılmıştır. Hocamız projeyi çeker çekmez ekstra MySQL kurulumuyla uğraşmadan tek tıkla test edebilsin diye varsayılan olarak SQLite'a bağladık, ancak tek bir satırla MySQL 8'e geçiş yapılabilmektedir."*

### Soru 2: "Neden `product` tablosunda fiyat ve stok yok da `product_variants` tablosunda?"
> **Cevabınız:** *"Çünkü gerçek pazaryerlerinde (örneğin Trendyol'da bir tişört veya Amazon'da bir telefon) fiyat ve stok rengine veya bedenine göre değişir (Örn: Siyah XL tişörtün stoğu ile Beyaz S tişörtün stoğu farklıdır; 128GB telefon ile 256GB telefonun fiyatı farklıdır). Bu yüzden fiyat ve stok en alt kırılım olan varyant tablosunda tutulmalıdır."*

### Soru 3: "Sipariş tablosundaki adres neden JSON olarak tutuluyor?"
> **Cevabınız:** *"Şartnamedeki 2. zorunlu kural (Snapshot kuralı) gereğince. Müşteri siparişi verdikten 1 yıl sonra adres defterindeki adresini silse veya güncellese bile, faturadaki ve geçmiş siparişteki teslimat adresi asla bozulmamalıdır."*

### Soru 4: "Git ve ekip çalışmasını nasıl organize ettiniz?"
> **Cevabınız:** *"Doğrudan `main` dalına push yapmıyoruz. `develop` dalı oluşturuldu. 1. hafta teslimatımız için `v0.1` etiketi oluşturuldu. Görevleri ise `docs/kanban-tasks.md` üzerinden GitHub Projects panosunda takip ediyoruz."*

---

## 📋 Sunum Öncesi Kontrol Listesi (Sunuma Başlamadan 5 Dakika Önce)

1. [ ] Terminalde `php artisan serve` açık olsun (`http://127.0.0.1:8000` çalışır durumda).
2. [ ] Tarayıcınızda 3 sekme açık olsun:
   - **Sekme 1:** `http://127.0.0.1:8000` (Projenin ana sayfası)
   - **Sekme 2:** [dbdiagram.io](https://dbdiagram.io) (Açık ve `docs/er-diagram.dbml` kodu yapıştırılmış)
   - **Sekme 3:** GitHub reponuz (veya GitHub Projects panosu)
3. [ ] Boş bir terminal penceresi hazır beklesin (`cd ~/Desktop/pazaryeri`).
