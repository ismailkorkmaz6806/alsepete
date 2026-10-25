# AlSepete - Ana Sayfa Wireframe ve UI Mimarisi

Bu doküman, Hafta 1 teslimatında istenen **Ana Sayfa Wireframe** tasarımını, bileşen hiyerarşisini ve kullanıcı deneyimi (UX) akışını açıklar.

---

## 1. Ana Sayfa Blok Hiyerarşisi

```
+-----------------------------------------------------------------------------------+
|  [LOGO]    [   Arama Kutusu (Canlı Öneri / Autocomplete)   ]    [Giriş/Hesabım] [Sepet] |
+-----------------------------------------------------------------------------------+
|  Tüm Kategoriler v  | Elektronik | Moda | Ev & Yaşam | Spor | Kitap | Satıcı Ol!       |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|  [                      HERO BANNER SLIDER (%100 Genişlik)                      ] |
|  Kampanya Görseli / CTA Butonu ("Şimdi Keşfet", "Günün Fırsatları")               |
|                                                                                   |
+-----------------------------------------------------------------------------------+
|  ÖNE ÇIKAN KATEGORİLER (Dairesel İkonlar / Kartlar)                                |
|  [ Bilgisayar ] [ Telefon ] [ Erkek Giyim ] [ Kadın Giyim ] [ Mobilya ] [ Fitness]|
+-----------------------------------------------------------------------------------+
|  GÜNÜN ÇOK SATANLARI (Yatay Kaydırılabilir Ürün Kartları)                          |
|  +--------------+  +--------------+  +--------------+  +--------------+           |
|  | [Fotoğraf]   |  | [Fotoğraf]   |  | [Fotoğraf]   |  | [Fotoğraf]   |           |
|  | Ürün Başlığı |  | Ürün Başlığı |  | Ürün Başlığı |  | Ürün Başlığı |           |
|  | Satıcı Adı   |  | Satıcı Adı   |  | Satıcı Adı   |  | Satıcı Adı   |           |
|  | 1.250,00 TL  |  | 899,00 TL    |  | 3.400,00 TL  |  | 450,00 TL    |           |
|  | [Sepete Ekle]|  | [Sepete Ekle]|  | [Sepete Ekle]|  | [Sepete Ekle]|           |
|  +--------------+  +--------------+  +--------------+  +--------------+           |
+-----------------------------------------------------------------------------------+
|  GÜVENİLİR VE POPÜLER MAĞAZALAR (Satıcı Vitrini)                                   |
|  [ TeknoMarket ]     [ ModaTrend ]     [ EvDekor ]     [ AktifSpor ]              |
|  Puan: 4.8           Puan: 4.9         Puan: 4.7       Puan: 4.6                  |
+-----------------------------------------------------------------------------------+
|  YENİ GELEN ÜRÜNLER (Grid Düzeni - 4 Kolon)                                        |
|  [Ürün Kartı 1]     [Ürün Kartı 2]     [Ürün Kartı 3]     [Ürün Kartı 4]          |
|  [Ürün Kartı 5]     [Ürün Kartı 6]     [Ürün Kartı 7]     [Ürün Kartı 8]          |
+-----------------------------------------------------------------------------------+
|  FOOTER (Alt Bilgi)                                                               |
|  - Kurumsal: Hakkımızda, Kariyer, İletişim                                        |
|  - Müşteri Hizmetleri: İade Koşulları, Kargo Takibi, SSS                           |
|  - Satıcılar İçin: Satıcı Başvurusu, Komisyon Oranları, Satıcı Paneli             |
|  - Güvenli Ödeme: iyzico, SSL 256-Bit, 3D Secure Logoları                         |
+-----------------------------------------------------------------------------------+
```

---

## 2. Bileşen Detayları

### A. Üst Başlık (Header & Mega Menü)
- **Arama Çubuğu:** Canlı arama (Livewire ile debounce:300ms). Kullanıcı yazdıkça açılır menüde ürün adı ve marka önerileri görünür.
- **Sepet Butonu:** Sepetteki toplam ürün sayısını gösteren rozet (Badge). Tıklandığında mini sepet önizlemesi veya `/sepet` sayfasına yönlendirme.
- **Hesabım Menüsü:** 
  - Ziyaretçi ise: "Giriş Yap" / "Kayıt Ol"
  - Müşteri ise: "Siparişlerim", "Adreslerim", "Favorilerim", "Çıkış Yap"
  - Satıcı ise: "Satıcı Paneli (`/satici`)", "Ürünlerim", "Gelen Siparişler"
  - Admin ise: "Yönetici Paneli (`/admin`)"

### B. Ürün Kartı Anatomisi
1. **Ürün Görseli:** 1:1 kare formatta, lazy loading destekli.
2. **Favori Butonu (Kalp İkonu):** Sağ üst köşede; tıklandığında sayfayı yenilemeden favorilere ekler/çıkarır.
3. **Satıcı Bilgisi:** Küçük gri metin (örn: *"Satıcı: TeknoMarket"*); tıklandığında mağaza sayfasına gider (`/magaza/teknomarket`).
4. **Başlık:** En fazla 2 satır kırpılmış metin.
5. **Fiyat Alanı:**
   - İndirimli ise: Üstü çizili eski fiyat + Vurgulu yeni indirimli fiyat (`decimal(10,2)`).
6. **Aksiyon:** "Sepete Ekle" butonu (varyant varsa varyant seçici modalı tetikler).

---

## 3. Responsive (Mobil Uyumluluk) Kuralları
- **Mobil Ekranlar (< 768px):** Mega menü yerine hamburger menü çekmecesi (drawer). Ürünler 2 kolonlu grid halinde gösterilir.
- **Masaüstü Ekranlar (>= 1024px):** Tam mega menü açılır panelleri, ürünler 4 veya 5 kolonlu grid düzeninde listelenir.
