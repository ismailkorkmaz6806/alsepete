# GitHub Projects - Kanban Görev Listesi (10 Hafta)

Bu dosyadaki görevleri GitHub reponuzda **Projects -> New Project -> Board (Kanban)** oluşturduktan sonra `Todo`, `In Progress`, `Done` sütunlarına kart / issue olarak ekleyebilirsiniz.

---

### Sütun 1: Hafta 1 (Veritabanı, Analiz ve Kurulum) [DONE]
- [x] Laravel 13 projesini yapılandır, .gitignore ve .gitattributes ekle
- [x] 20 tablonun migration dosyalarını yaz (decimal(10,2) fiyat kuralı ile)
- [x] Modelleri ve ilişkileri tanımla (hasMany, belongsTo, belongsToMany)
- [x] Seeder hazırla: 1 admin, 5 satıcı, 20 müşteri, 15 kategori, 100 ürün
- [x] dbdiagram.io için ER diyagramı DBML kodunu oluştur
- [x] Ana sayfa wireframe tasarım dokümanını hazırla
- [x] README kurulum kılavuzunu (Linux & Windows) hazırla

### Sütun 2: Hafta 2 (Kimlik Doğrulama, Roller ve Kategoriler) [DONE]
- [x] Breeze / Livewire starter kit kurulumu (Giriş/Kayıt arayüzleri)
- [x] spatie/laravel-permission rollerini aktifleştir (admin, seller, customer)
- [x] Rol bazlı middleware yaz (/admin yalnızca admin, /satici yalnızca onaylı satıcı)
- [x] Admin için Kategori CRUD (alt kategori hiyerarşisi, slug, sıralama, Action mimarisi)
- [x] Ortak layout: Header, Arama kutusu, Dinamik Mega menü, Footer
- [x] Form doğrulama mesajlarını Türkçeleştir (lang/tr) ve çoklu dil desteği (TR/EN)

### Sütun 3: Hafta 3 (Ürün Kataloğu ve Vitrin) [DONE]
- [x] Ürün ve varyant modelleri: renk, beden, fiyat, indirimli fiyat, stok
- [x] Çoklu görsel yükleme ve küçük resim üretimi (spatie/laravel-medialibrary)
- [x] Ana sayfa: Banner slider (Alpine.js), öne çıkan kategoriler, "Çok satanlar", "Yeni gelenler"
- [x] Kategori sayfası: Ürün kartları, sayfalama (24 ürün), sıralama (fiyat artan/azalan, en yeni, en çok değerlendirilen)
- [x] Ürün detay sayfası: Görsel galeri, varyant seçimi, fiyat, stok durumu, satıcı adı, "Sepete ekle" butonu
- [x] SEO uyumlu URL rotaları (/urun/{slug}, /kategori/{slug})
- [x] Kabul Kriteri: Canlı varyant değişimi (Livewire ile sayfa yenilenmeden) ve N+1 sorgu denetimi (with())
- [x] [BONUS +2 Puan] "Bu ürünü alanlar bunları da aldı" çapraz öneri sistemi
- [x] [BONUS +2 Puan] Satıcıya soru sor ve cevap sistemi (Ürün sayfasında Livewire & Satıcı panelinde yanıt)
- [x] [BONUS +2 Puan] Çoklu dil desteği (TR / EN)
- [x] Laravel Debugbar entegrasyonu (N+1 sorgu optimizasyonu kanıtı)

### Sütun 4: Hafta 4 (Arama, Filtreleme ve Satıcı Paneli) - [ARA DEMO 1]
- [ ] Arama kutusunda anlık autocomplete önerisi
- [ ] URL parametreli ürün filtreleri (?marka=x&min=100)
- [ ] Satıcı başvuru formu (mağaza adı, vergi no, IBAN)
- [ ] Satıcı paneli: Ürün ekleme, düzenleme, stok güncelleme
- [ ] Policy yetkilendirmesi: Satıcı sadece kendi ürününü düzenleyebilir
- [ ] Herkese açık mağaza vitrin sayfası (/magaza/{slug})

### Sütun 5: Hafta 5 (Sepet, Favoriler, Adres ve Kupon)
- [ ] Sepet yönetimi (ekle, sil, adet güncelle, misafir sepeti birleştirme)
- [ ] Sepette ürünlerin satıcı bazında gruplanması ve kargo hesaplama
- [ ] Stoktan fazla sepete eklemeyi engelleme
- [ ] Favoriler listesi (AJAX / Livewire kalp butonu)
- [ ] İl/ilçe seçimli adres defteri yönetimi
- [ ] Kupon doğrulama ve indirim hesaplama motoru (En az 5 birim test)

### Sütun 6: Hafta 6 (Ödeme ve Sipariş Oluşturma) - [ARA DEMO 2]
- [ ] Checkout sayfası ve mesafeli satış sözleşmesi
- [ ] iyzico sandbox ödeme entegrasyonu ve 3D secure dönüşü
- [ ] DB Transaction: 1 ana sipariş (orders) ve satıcı başına alt sipariş (sub_orders)
- [ ] lockForUpdate() ile eşzamanlı stok düşümü (race condition koruması)
- [ ] Sipariş onay e-postasını kuyruğa (Queue) atma
- [ ] Siparişlerim ve Sipariş Detay sayfaları

### Sütun 7: Hafta 7 (Sipariş Yönetimi, Kargo, İade ve Yorumlar)
- [ ] Alt sipariş durum makinesi (Enum: Onay bekliyor -> Hazırlanıyor -> Kargoda -> Teslim)
- [ ] Satıcı panelinde kargo firması ve takip numarası girişi
- [ ] Müşteri için sipariş bildirimleri (Laravel Notifications)
- [ ] 14 günlük iade talep sistemi
- [ ] Yalnızca teslim edilen ürünü alan müşterilerin puan ve yorum yazabilmesi

### Sütun 8: Hafta 8 (Filament Admin Paneli ve Raporlar)
- [ ] Filament admin paneli kurulumu ve kaynakların (Resources) tanımlanması
- [ ] Satıcı başvuru onay/red ekranı ve e-posta bildirimi
- [ ] Kategori bazlı komisyon hesaplama motoru ve birim testleri
- [ ] Admin dashboard: Ciro, sipariş sayısı, en çok satanlar grafikleri
- [ ] Satıcı dashboard: Hakediş ve komisyon raporları
- [ ] Siparişlerin Excel/CSV çıktısı

### Sütun 9: Hafta 9 (REST API, Performans ve Gelişmiş Arama)
- [ ] Laravel Sanctum ile API kimlik doğrulama (/api/v1/auth/login)
- [ ] API Resource sınıfları, sayfalama ve rate limiting
- [ ] Ana sayfa blokları ve kategori ağacı için önbellekleme (Cache)
- [ ] Laravel Scout ile tam metin arama
- [ ] Veritabanı indeksleri ve sorgu optimizasyonu

### Sütun 10: Hafta 10 (Testler, Güvenlik, Canlıya Alma ve Final)
- [ ] En az 40 adet Pest testi ile tüm kritik akışların kapsanması
- [ ] Güvenlik kontrol listesi: CSRF, XSS, SQLi, Mass Assignment, Dosya boyutu/tür denetimi
- [ ] GitHub Actions CI/CD hattı (Pint + Pest testleri)
- [ ] Canlı sunucu yayını (HTTPS, kuyruk çalıştırıcı, cron zamanlayıcı)
- [ ] Final sunum slaytı ve 15 dakikalık canlı demo provası
