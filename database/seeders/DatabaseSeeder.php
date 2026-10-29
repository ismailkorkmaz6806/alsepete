<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductQuestion;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Hafta 1 Kabul Kriteri:
     * 1 admin, 5 satıcı, 20 müşteri, 15+ kategori, 100 ürün.
     */
    public function run(): void
    {
        // 1. Rolleri Olustur
        $roles = ['admin', 'seller', 'customer'];
        foreach ($roles as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $sellerRoleId = DB::table('roles')->where('name', 'seller')->value('id');
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');

        // 2. 1 Admin Kullanicisi
        $admin = User::firstOrCreate(
            ['email' => 'admin@alsepete.com'],
            [
                'name' => 'Sistem Yöneticisi',
                'phone' => '05550000001',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        DB::table('model_has_roles')->updateOrInsert(
            ['role_id' => $adminRoleId, 'model_type' => User::class, 'model_id' => $admin->id]
        );

        // 3. 5 Ana Kategori ve 15 Alt Kategori (Toplam 20 Hiyerarşik Kategori)
        $categoryTree = [
            'Elektronik' => [
                'commission' => 8.00,
                'sub' => ['Bilgisayar & Laptop', 'Telefon & Aksesuar', 'Kulaklık & Ses Sistemleri'],
            ],
            'Moda & Giyim' => [
                'commission' => 15.00,
                'sub' => ['Erkek Giyim', 'Kadın Giyim', 'Spor Ayakkabı & Çanta'],
            ],
            'Ev & Yaşam' => [
                'commission' => 12.00,
                'sub' => ['Mutfak Gereçleri', 'Elektrikli Ev Aletleri', 'Mobilya & Dekorasyon', 'Ev Tekstili'],
            ],
            'Spor & Outdoor' => [
                'commission' => 10.00,
                'sub' => ['Fitness & Kondisyon', 'Kamp & Doğa Malzemeleri', 'Bisiklet & Ekipman'],
            ],
            'Kozmetik & Kişisel Bakım' => [
                'commission' => 10.00,
                'sub' => ['Parfüm & Deodorant', 'Cilt & Yüz Bakımı', 'Saç Bakımı & Şekillendirme'],
            ],
        ];

        $categoriesByName = [];
        $sortOrder = 1;

        foreach ($categoryTree as $parentName => $data) {
            $parentCat = Category::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'parent_id' => null,
                    'name' => $parentName,
                    'commission_rate' => $data['commission'],
                    'sort' => $sortOrder++,
                ]
            );
            $categoriesByName[$parentName] = $parentCat;

            foreach ($data['sub'] as $subName) {
                $subCat = Category::firstOrCreate(
                    ['slug' => Str::slug($subName)],
                    [
                        'parent_id' => $parentCat->id,
                        'name' => $subName,
                        'commission_rate' => $data['commission'],
                        'sort' => $sortOrder++,
                    ]
                );
                $categoriesByName[$subName] = $subCat;
            }
        }

        // 4. Sektörlerine Göre Gerçekçi Markalar
        $brandMap = [
            'Apple' => 'Elektronik',
            'Samsung' => 'Elektronik',
            'Xiaomi' => 'Elektronik',
            'Sony' => 'Elektronik',
            'Asus' => 'Elektronik',
            'Philips' => 'Elektronik',
            'Nike' => 'Moda & Giyim',
            'Adidas' => 'Moda & Giyim',
            'Puma' => 'Moda & Giyim',
            'Zara' => 'Moda & Giyim',
            'Mavi' => 'Moda & Giyim',
            "Levi's" => 'Moda & Giyim',
            'Karaca' => 'Ev & Yaşam',
            'Tefal' => 'Ev & Yaşam',
            'Bosch' => 'Ev & Yaşam',
            'IKEA' => 'Ev & Yaşam',
            'English Home' => 'Ev & Yaşam',
            'The North Face' => 'Spor & Outdoor',
            'Columbia' => 'Spor & Outdoor',
            'Decathlon' => 'Spor & Outdoor',
            'Voit' => 'Spor & Outdoor',
            'Dyson' => 'Kozmetik & Kişisel Bakım',
            "L'Oréal" => 'Kozmetik & Kişisel Bakım',
            'Nivea' => 'Kozmetik & Kişisel Bakım',
            'Clinique' => 'Kozmetik & Kişisel Bakım',
            'Armani' => 'Kozmetik & Kişisel Bakım',
        ];

        $brandsByName = [];
        foreach ($brandMap as $bName => $sector) {
            $brandsByName[$bName] = Brand::firstOrCreate(
                ['slug' => Str::slug($bName)],
                ['name' => $bName, 'logo' => null]
            );
        }

        // 5. 5 Uzman Onaylı Satıcı Mağazası
        $sellerData = [
            'tekno' => ['name' => 'Ahmet Yılmaz', 'email' => 'satici1@alsepete.com', 'store' => 'TeknoMarket', 'rate' => 8.00],
            'moda' => ['name' => 'Mehmet Tekstil', 'email' => 'satici2@alsepete.com', 'store' => 'ModaTrend', 'rate' => 15.00],
            'ev' => ['name' => 'Ayşe Dizayn', 'email' => 'satici3@alsepete.com', 'store' => 'EvDekor', 'rate' => 12.00],
            'spor' => ['name' => 'Caner Spor', 'email' => 'satici4@alsepete.com', 'store' => 'AktifSpor', 'rate' => 10.00],
            'kozmetik' => ['name' => 'Zeynep Kozmetik', 'email' => 'satici5@alsepete.com', 'store' => 'KozmetikPlus', 'rate' => 10.00],
        ];

        $sellersByKey = [];
        $idx = 1;
        foreach ($sellerData as $key => $s) {
            $user = User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'phone' => '0532000000'.$idx,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $sellerRoleId, 'model_type' => User::class, 'model_id' => $user->id]
            );

            $seller = Seller::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'store_name' => $s['store'],
                    'slug' => Str::slug($s['store']),
                    'tax_number' => '123456789'.$idx,
                    'iban' => 'TR'.str_pad($idx.'987654321', 24, '0', STR_PAD_RIGHT),
                    'status' => 'onayli',
                    'commission_rate' => $s['rate'],
                ]
            );

            $sellersByKey[$key] = $seller;
            $idx++;
        }

        // 6. 20 Müşteri ve Gerçekçi Adresleri
        for ($i = 1; $i <= 20; $i++) {
            $customer = User::firstOrCreate(
                ['email' => "musteri{$i}@alsepete.com"],
                [
                    'name' => "Müşteri {$i}",
                    'phone' => '054400000'.str_pad($i, 2, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $customerRoleId, 'model_type' => User::class, 'model_id' => $customer->id]
            );

            Address::firstOrCreate(
                ['user_id' => $customer->id, 'is_default' => true],
                [
                    'title' => 'Ev Adresim',
                    'city' => ($i % 2 === 0) ? 'İstanbul' : 'Ankara',
                    'district' => ($i % 2 === 0) ? 'Kadıköy' : 'Çankaya',
                    'full_address' => "Atatürk Mahallesi, İnönü Caddesi No: {$i} Daire: 4",
                ]
            );
        }

        // 7. 100 Gerçekçi, Kategorisine, Markasına ve Fotoğrafına Tam Uygun Ürün Kataloğu
        $catalog = [
            // --- ELEKTRONİK (20 ÜRÜN) ---
            [
                'name' => 'Apple iPhone 15 Pro Max 256GB Titanyum',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 74999.00,
                'img' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&auto=format&fit=crop&q=80', 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Doğal Titanyum', 'size' => '256GB', 'stock' => 15], ['color' => 'Mavi Titanyum', 'size' => '512GB', 'stock' => 8]],
            ],
            [
                'name' => 'Apple MacBook Pro 16 M3 Max 36GB RAM 1TB SSD',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 119999.00,
                'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Uzay Siyahı', 'size' => '1TB', 'stock' => 10], ['color' => 'Gümüş', 'size' => '1TB', 'stock' => 5]],
            ],
            [
                'name' => 'Apple iPad Pro 12.9 M2 Çip 256GB Wi-Fi',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 45999.00,
                'img' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1561154464-82e9adf32764?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Uzay Grisi', 'size' => '256GB', 'stock' => 20], ['color' => 'Gümüş', 'size' => '512GB', 'stock' => 12]],
            ],
            [
                'name' => 'Apple Watch Ultra 2 Titanyum 49mm GPS + Cellular',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 39999.00,
                'img' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Turuncu Kordon', 'size' => '49mm', 'stock' => 25], ['color' => 'Mavi Kordon', 'size' => '49mm', 'stock' => 18]],
            ],
            [
                'name' => 'Apple AirPods Pro 2. Nesil USB-C MagSafe Şarj Kutulu',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 8499.00,
                'img' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz', 'size' => 'Standart', 'stock' => 40], ['color' => 'Beyaz', 'size' => 'Yedek Uçlu', 'stock' => 20]],
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB Titanyum Grisi',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Samsung', 'seller' => 'tekno', 'price' => 69999.00,
                'img' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Titanyum Grisi', 'size' => '512GB', 'stock' => 22], ['color' => 'Titanyum Siyahı', 'size' => '256GB', 'stock' => 30]],
            ],
            [
                'name' => 'Samsung 65 İnç QLED 4K Akıllı Smart TV',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Samsung', 'seller' => 'tekno', 'price' => 38999.00,
                'img' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1461151304267-38535e780c79?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Koyu Gri Çerçeve', 'size' => '65 İnç', 'stock' => 10], ['color' => 'Siyah Çerçeve', 'size' => '55 İnç', 'stock' => 14]],
            ],
            [
                'name' => 'Sony WH-1000XM5 Aktif Gürültü Engelleyici Kulak Üstü Kulaklık',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Sony', 'seller' => 'tekno', 'price' => 12999.00,
                'img' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => 'Standart', 'stock' => 35], ['color' => 'Gümüş Platin', 'size' => 'Standart', 'stock' => 20]],
            ],
            [
                'name' => 'Sony PlayStation 5 Slim 1TB Oyun Konsolu',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Sony', 'seller' => 'tekno', 'price' => 24999.00,
                'img' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz Standart', 'size' => '1TB Diskli', 'stock' => 18], ['color' => 'Siyah Özel Seri', 'size' => '1TB Dijital', 'stock' => 10]],
            ],
            [
                'name' => 'Xiaomi 14 Ultra 512GB Leica Dörtlü Kamera Akıllı Telefon',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Xiaomi', 'seller' => 'tekno', 'price' => 49999.00,
                'img' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Deri Siyah', 'size' => '512GB', 'stock' => 25], ['color' => 'Deri Beyaz', 'size' => '512GB', 'stock' => 15]],
            ],
            [
                'name' => 'Xiaomi Robot Süpürge X20+ Akıllı İstasyonlu Mop',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Xiaomi', 'seller' => 'tekno', 'price' => 22499.00,
                'img' => 'https://images.unsplash.com/photo-1616401784845-180882ba9ba8?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1558317374-067fb5f30001?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz', 'size' => 'İstasyonlu', 'stock' => 12], ['color' => 'Siyah', 'size' => 'İstasyonlu', 'stock' => 8]],
            ],
            [
                'name' => 'Asus ROG Strix G16 i9 14900HX RTX 4080 Oyuncu Laptopu',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Asus', 'seller' => 'tekno', 'price' => 89999.00,
                'img' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Eclipse Gray', 'size' => '32GB / 1TB', 'stock' => 14], ['color' => 'Eclipse Gray', 'size' => '64GB / 2TB', 'stock' => 6]],
            ],
            [
                'name' => 'Asus TUF Gaming 27 İnç 180Hz 1ms IPS Oyuncu Monitörü',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Asus', 'seller' => 'tekno', 'price' => 8499.00,
                'img' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1547119957-637f8679db1e?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah', 'size' => '27 İnç', 'stock' => 30], ['color' => 'Siyah', 'size' => '32 İnç Kavisli', 'stock' => 12]],
            ],
            [
                'name' => 'Philips Hue Akıllı Başlangıç Seti 3 Ampul + Bridge RGB',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Philips', 'seller' => 'tekno', 'price' => 5299.00,
                'img' => 'https://images.unsplash.com/photo-1550524514-9271887e467b?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'RGB Çok Renkli', 'size' => 'E27 Duy', 'stock' => 28], ['color' => 'Sıcak Beyaz', 'size' => 'GU10 Spot', 'stock' => 16]],
            ],
            [
                'name' => 'Philips Sonicare 9900 Prestige Akıllı Şarjlı Diş Fırçası',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Philips', 'seller' => 'tekno', 'price' => 9899.00,
                'img' => 'https://images.unsplash.com/photo-1559591937-e109d94cb459?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Şampanya Altın', 'size' => 'Seyahat Kutulu', 'stock' => 24], ['color' => 'Gece Mavisi', 'size' => 'Seyahat Kutulu', 'stock' => 18]],
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra 14.6 İnç AMOLED Tablet',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Samsung', 'seller' => 'tekno', 'price' => 37999.00,
                'img' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Grafit Gri', 'size' => '256GB + S-Pen', 'stock' => 20], ['color' => 'Bej', 'size' => '512GB + S-Pen', 'stock' => 10]],
            ],
            [
                'name' => 'Apple Magic Keyboard Nümerik Tuşlu Kablosuz Klavye',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Apple', 'seller' => 'tekno', 'price' => 5499.00,
                'img' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Gümüş Beyaz', 'size' => 'Türkçe Q', 'stock' => 30], ['color' => 'Uzay Siyahı', 'size' => 'Türkçe Q', 'stock' => 15]],
            ],
            [
                'name' => 'Sony DualSense Edge Profesyonel Kablosuz PS5 Kolu',
                'cat' => 'Kulaklık & Ses Sistemleri', 'brand' => 'Sony', 'seller' => 'tekno', 'price' => 8999.00,
                'img' => 'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz/Siyah', 'size' => 'Taşıma Çantalı', 'stock' => 20]],
            ],
            [
                'name' => 'Xiaomi 67W Hızlı Şarj Cihazı ve Örgülü Type-C Kablo',
                'cat' => 'Telefon & Aksesuar', 'brand' => 'Xiaomi', 'seller' => 'tekno', 'price' => 899.00,
                'img' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1609081219090-a6d8173087ec?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz', 'size' => '67W Turbo', 'stock' => 100]],
            ],
            [
                'name' => 'Asus ROG Harpe Ace Aim Lab Kablosuz Hafif Oyuncu Faresi',
                'cat' => 'Bilgisayar & Laptop', 'brand' => 'Asus', 'seller' => 'tekno', 'price' => 4599.00,
                'img' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah 54g', 'size' => '36.000 DPI', 'stock' => 25], ['color' => 'Beyaz Moonlight', 'size' => '36.000 DPI', 'stock' => 15]],
            ],

            // --- MODA & GİYİM (20 ÜRÜN) ---
            [
                'name' => 'Nike Air Jordan 1 Retro High OG Chicago Spor Ayakkabı',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Nike', 'seller' => 'moda', 'price' => 6499.00,
                'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1514989940723-e8e51635b782?w=800&auto=format&fit=crop&q=80', 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Kırmızı/Beyaz', 'size' => '42', 'stock' => 15], ['color' => 'Kırmızı/Beyaz', 'size' => '43', 'stock' => 20], ['color' => 'Kırmızı/Beyaz', 'size' => '44', 'stock' => 10]],
            ],
            [
                'name' => 'Nike Club Fleece Kapüşonlu Sweatshirt Polar Kumaş',
                'cat' => 'Erkek Giyim', 'brand' => 'Nike', 'seller' => 'moda', 'price' => 2199.00,
                'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1509967419530-da38b4704bc6?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Gri Melanj', 'size' => 'M', 'stock' => 25], ['color' => 'Gri Melanj', 'size' => 'L', 'stock' => 30], ['color' => 'Siyah', 'size' => 'XL', 'stock' => 15]],
            ],
            [
                'name' => "Nike Air Force 1 '07 İkonik Beyaz Sneaker Ayakkabı",
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Nike', 'seller' => 'moda', 'price' => 4599.00,
                'img' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Optik Beyaz', 'size' => '41', 'stock' => 18], ['color' => 'Optik Beyaz', 'size' => '42', 'stock' => 28], ['color' => 'Optik Beyaz', 'size' => '43', 'stock' => 22]],
            ],
            [
                'name' => 'Adidas Ultraboost Light Yüksek Yastıklamalı Koşu Ayakkabısı',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Adidas', 'seller' => 'moda', 'price' => 5899.00,
                'img' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah Boost', 'size' => '42.5', 'stock' => 15], ['color' => 'Beyaz Boost', 'size' => '43.5', 'stock' => 12]],
            ],
            [
                'name' => 'Adidas Originals Trefoil Klasik Kapüşonlu Üst',
                'cat' => 'Erkek Giyim', 'brand' => 'Adidas', 'seller' => 'moda', 'price' => 2399.00,
                'img' => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Lacivert', 'size' => 'S', 'stock' => 10], ['color' => 'Lacivert', 'size' => 'M', 'stock' => 25], ['color' => 'Siyah', 'size' => 'L', 'stock' => 20]],
            ],
            [
                'name' => 'Adidas Stan Smith Primegreen Çevre Dostu Deri Sneaker',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Adidas', 'seller' => 'moda', 'price' => 3799.00,
                'img' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz/Yeşil', 'size' => '41', 'stock' => 20], ['color' => 'Beyaz/Yeşil', 'size' => '42', 'stock' => 35], ['color' => 'Beyaz/Yeşil', 'size' => '43', 'stock' => 15]],
            ],
            [
                'name' => 'Puma Smash V2 L Hakiki Deri Unisex Günlük Ayakkabı',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Puma', 'seller' => 'moda', 'price' => 2299.00,
                'img' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah/Beyaz Çizgili', 'size' => '42', 'stock' => 30], ['color' => 'Tam Beyaz', 'size' => '43', 'stock' => 25]],
            ],
            [
                'name' => 'Puma ESS Small Logo %100 Pamuklu Erkek Tişört',
                'cat' => 'Erkek Giyim', 'brand' => 'Puma', 'seller' => 'moda', 'price' => 649.00,
                'img' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz', 'size' => 'M', 'stock' => 45], ['color' => 'Siyah', 'size' => 'L', 'stock' => 50]],
            ],
            [
                'name' => 'Zara Oversize Yün Karışımlı Kruvaze Kaşe Kaban',
                'cat' => 'Kadın Giyim', 'brand' => 'Zara', 'seller' => 'moda', 'price' => 4299.00,
                'img' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1544441893-675973e31985?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Deve Tüyü Bej', 'size' => 'S', 'stock' => 12], ['color' => 'Deve Tüyü Bej', 'size' => 'M', 'stock' => 18], ['color' => 'Antrasit', 'size' => 'L', 'stock' => 8]],
            ],
            [
                'name' => 'Zara Dökümlü Saten Kuşaklı Midi Elbise',
                'cat' => 'Kadın Giyim', 'brand' => 'Zara', 'seller' => 'moda', 'price' => 1899.00,
                'img' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Zümrüt Yeşili', 'size' => '36', 'stock' => 15], ['color' => 'Zümrüt Yeşili', 'size' => '38', 'stock' => 20], ['color' => 'Siyah', 'size' => '40', 'stock' => 10]],
            ],
            [
                'name' => 'Zara Regular Fit %100 Keten Uzun Kollu Gömlek',
                'cat' => 'Erkek Giyim', 'brand' => 'Zara', 'seller' => 'moda', 'price' => 1299.00,
                'img' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Doğal Keten Beyazı', 'size' => 'M', 'stock' => 20], ['color' => 'Açık Mavi', 'size' => 'L', 'stock' => 25]],
            ],
            [
                'name' => 'Mavi Jake Koyu Mavi Esnek Slim Fit Jean Pantolon',
                'cat' => 'Erkek Giyim', 'brand' => 'Mavi', 'seller' => 'moda', 'price' => 1499.00,
                'img' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1542272604-780c96856553?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Koyu Mavi Yıkamalı', 'size' => '31/32', 'stock' => 20], ['color' => 'Koyu Mavi Yıkamalı', 'size' => '32/32', 'stock' => 30], ['color' => 'Koyu Mavi Yıkamalı', 'size' => '34/32', 'stock' => 15]],
            ],
            [
                'name' => 'Mavi İçi Polar Su İtici Kapüşonlu Şişme Yelek',
                'cat' => 'Erkek Giyim', 'brand' => 'Mavi', 'seller' => 'moda', 'price' => 1799.00,
                'img' => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Haki Yeşil', 'size' => 'L', 'stock' => 20], ['color' => 'Siyah', 'size' => 'XL', 'stock' => 25]],
            ],
            [
                'name' => "Levi's 501 Original Fit Zamansız Düz Kesim Kot Pantolon",
                'cat' => 'Erkek Giyim', 'brand' => "Levi's", 'seller' => 'moda', 'price' => 2999.00,
                'img' => 'https://images.unsplash.com/photo-1542272604-780c96856553?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Klasik Taş Yıkama', 'size' => '32/32', 'stock' => 25], ['color' => 'Orijinal İndigo', 'size' => '33/32', 'stock' => 20]],
            ],
            [
                'name' => "Levi's Trucker Vintage İkonik Denim Kot Ceket",
                'cat' => 'Erkek Giyim', 'brand' => "Levi's", 'seller' => 'moda', 'price' => 3499.00,
                'img' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Orta Mavi Yıkama', 'size' => 'M', 'stock' => 15], ['color' => 'Orta Mavi Yıkama', 'size' => 'L', 'stock' => 22]],
            ],
            [
                'name' => 'Zara Deri Kapitone Zincirli Omuz Çantası',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Zara', 'seller' => 'moda', 'price' => 1699.00,
                'img' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Kuzgun Siyah', 'size' => 'Tek Ebat', 'stock' => 30], ['color' => 'Krem Ekru', 'size' => 'Tek Ebat', 'stock' => 20]],
            ],
            [
                'name' => 'Nike Heritage Çok Bölmeli Günlük Sırt Çantası 25L',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Nike', 'seller' => 'moda', 'price' => 1499.00,
                'img' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => '25 Litre', 'stock' => 35], ['color' => 'Bordo', 'size' => '25 Litre', 'stock' => 15]],
            ],
            [
                'name' => 'Adidas Essentials 3 Çizgili Fransız Havlu Eşofman Altı',
                'cat' => 'Kadın Giyim', 'brand' => 'Adidas', 'seller' => 'moda', 'price' => 1799.00,
                'img' => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah/Beyaz Çizgili', 'size' => 'S', 'stock' => 20], ['color' => 'Siyah/Beyaz Çizgili', 'size' => 'M', 'stock' => 25]],
            ],
            [
                'name' => 'Puma Phase Su Şişesi Cepli Okul ve Spor Çantası',
                'cat' => 'Spor Ayakkabı & Çanta', 'brand' => 'Puma', 'seller' => 'moda', 'price' => 999.00,
                'img' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Gece Siyahı', 'size' => '22 Litre', 'stock' => 40]],
            ],
            [
                'name' => 'Mavi Basic V Yaka Rahat Kesim Pamuklu Kadın Tişört',
                'cat' => 'Kadın Giyim', 'brand' => 'Mavi', 'seller' => 'moda', 'price' => 499.00,
                'img' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Saf Beyaz', 'size' => 'S', 'stock' => 30], ['color' => 'Gül Kurusu', 'size' => 'M', 'stock' => 25]],
            ],

            // --- EV & YAŞAM (20 ÜRÜN) ---
            [
                'name' => 'Karaca Bio Diamond Pro 7 Parça Granit Tencere Seti',
                'cat' => 'Mutfak Gereçleri', 'brand' => 'Karaca', 'seller' => 'ev', 'price' => 5499.00,
                'img' => '/images/products/granite-cookware.jpg',
                'gallery' => ['/images/products/granite-cookware.jpg'],
                'variants' => [['color' => 'Elmas Siyahı', 'size' => '7 Parça Set', 'stock' => 20], ['color' => 'Bakır Işıltılı', 'size' => '7 Parça Set', 'stock' => 15]],
            ],
            [
                'name' => 'Karaca Hatır Hüps Közde ve Sütlü Türk Kahvesi Makinesi',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Karaca', 'seller' => 'ev', 'price' => 1999.00,
                'img' => '/images/products/turkish-coffee-maker.jpg',
                'gallery' => ['/images/products/turkish-coffee-maker.jpg'],
                'variants' => [['color' => 'Rose Gold', 'size' => '5 Fincan Kapasiteli', 'stock' => 35], ['color' => 'Mat Antrasit', 'size' => '5 Fincan Kapasiteli', 'stock' => 25]],
            ],
            [
                'name' => 'Karaca Fine Pearl 62 Parça İnci Tozlu Yemek Takımı',
                'cat' => 'Mutfak Gereçleri', 'brand' => 'Karaca', 'seller' => 'ev', 'price' => 14999.00,
                'img' => '/images/products/porcelain-dinnerware.jpg',
                'gallery' => ['/images/products/porcelain-dinnerware.jpg'],
                'variants' => [['color' => 'Platin Yaldızlı', 'size' => '12 Kişilik (62 Parça)', 'stock' => 10], ['color' => 'Altın Yaldızlı', 'size' => '12 Kişilik (62 Parça)', 'stock' => 8]],
            ],
            [
                'name' => 'Tefal Ingenio Titanium Çıkarılabilir Saplı 12 Parça Tava Tencere Seti',
                'cat' => 'Mutfak Gereçleri', 'brand' => 'Tefal', 'seller' => 'ev', 'price' => 6799.00,
                'img' => '/images/products/tefal-ingenio.jpg',
                'gallery' => ['/images/products/tefal-ingenio.jpg'],
                'variants' => [['color' => 'Titanium Antrasit', 'size' => '12 Parça Full', 'stock' => 20], ['color' => 'Titanium Kırmızı', 'size' => '12 Parça Full', 'stock' => 12]],
            ],
            [
                'name' => 'Tefal Easy Fry & Grill XXL 6.5L Sıcak Hava Fritözü Airfryer',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Tefal', 'seller' => 'ev', 'price' => 4599.00,
                'img' => '/images/products/air-fryer.jpg',
                'gallery' => ['/images/products/air-fryer.jpg'],
                'variants' => [['color' => 'İnoks Çelik', 'size' => '6.5 Litre XXL', 'stock' => 30], ['color' => 'Parlak Siyah', 'size' => '6.5 Litre XXL', 'stock' => 15]],
            ],
            [
                'name' => 'Bosch Serie 6 Tam Otomatik Çekirdekten Fincana Espresso Kahve Makinesi',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Bosch', 'seller' => 'ev', 'price' => 24999.00,
                'img' => '/images/products/espresso_machine.jpg',
                'gallery' => ['/images/products/espresso_machine.jpg'],
                'variants' => [['color' => 'Fırçalanmış Paslanmaz Çelik', 'size' => '19 Bar Basınç', 'stock' => 15]],
            ],
            [
                'name' => 'Bosch Unlimited 7 Şarjlı Dikey Süpürge 66 Dakika Çalışma',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Bosch', 'seller' => 'ev', 'price' => 13499.00,
                'img' => '/images/products/cordless-vacuum.jpg',
                'gallery' => ['/images/products/cordless-vacuum.jpg'],
                'variants' => [['color' => 'Metalik Kırmızı', 'size' => 'Çift Bataryalı Pro', 'stock' => 20]],
            ],
            [
                'name' => 'IKEA STRANDMON Kanatlı Klasik Berjer Koltuk',
                'cat' => 'Mobilya & Dekorasyon', 'brand' => 'IKEA', 'seller' => 'ev', 'price' => 7499.00,
                'img' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Nordvalla Koyu Gri', 'size' => 'Tekli Berjer', 'stock' => 10], ['color' => 'Hardal Sarısı', 'size' => 'Tekli Berjer', 'stock' => 8]],
            ],
            [
                'name' => 'IKEA KALLAX 4 Bölmeli Çok Amaçlı Raf Ünitesi',
                'cat' => 'Mobilya & Dekorasyon', 'brand' => 'IKEA', 'seller' => 'ev', 'price' => 1899.00,
                'img' => 'https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz Mat', 'size' => '77x77 cm', 'stock' => 30], ['color' => 'Meşe Ağacı Kaplama', 'size' => '77x77 cm', 'stock' => 20]],
            ],
            [
                'name' => 'IKEA MARKUS Ergonomik Fileli Ayarlanabilir Çalışma Koltuğu',
                'cat' => 'Mobilya & Dekorasyon', 'brand' => 'IKEA', 'seller' => 'ev', 'price' => 4999.00,
                'img' => 'https://images.unsplash.com/photo-1505797149-43b0069ec26b?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1580481077197-04d8058728a3?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Koyu Gri Kumaş', 'size' => 'Yüksek Sırtlıklı', 'stock' => 25], ['color' => 'Glose Siyah Deri', 'size' => 'Yüksek Sırtlıklı', 'stock' => 12]],
            ],
            [
                'name' => 'English Home %100 Pamuk Saten Çift Kişilik Nevresim Takımı',
                'cat' => 'Ev Tekstili', 'brand' => 'English Home', 'seller' => 'ev', 'price' => 1699.00,
                'img' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Pudra Çiçekli', 'size' => 'Çift Kişilik (200x220)', 'stock' => 35], ['color' => 'Adaçayı Yeşili', 'size' => 'Çift Kişilik (200x220)', 'stock' => 25]],
            ],
            [
                'name' => 'English Home Bambu Karışımlı 4 Parça Banyo Havlu Seti',
                'cat' => 'Ev Tekstili', 'brand' => 'English Home', 'seller' => 'ev', 'price' => 899.00,
                'img' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Taş Grisi & Beyaz', 'size' => '4 Adet 50x90cm', 'stock' => 40]],
            ],
            [
                'name' => 'English Home Çift Kişilik Kaz Tüyü Hissi Veren Yorgan',
                'cat' => 'Ev Tekstili', 'brand' => 'English Home', 'seller' => 'ev', 'price' => 1299.00,
                'img' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Beyaz Kumaş', 'size' => '195x215 cm', 'stock' => 30]],
            ],
            [
                'name' => 'Karaca Mastermaid Chef Çift Kollu 1500W Stand Mikser Hamur Yoğurucu',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Karaca', 'seller' => 'ev', 'price' => 4999.00,
                'img' => 'https://images.unsplash.com/photo-1594385208974-2e75f8d7bb48?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1585515320310-259814833e62?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Vintage Kırmızı', 'size' => '5 Litre Paslanmaz Hazne', 'stock' => 20], ['color' => 'Mat Siyah', 'size' => '5 Litre Paslanmaz Hazne', 'stock' => 15]],
            ],
            [
                'name' => 'Tefal Ultimate Pure 3200W Kireç Filtreli Buharlı Ütü',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Tefal', 'seller' => 'ev', 'price' => 3299.00,
                'img' => '/images/products/steam_iron.jpg',
                'gallery' => ['/images/products/steam_iron.jpg'],
                'variants' => [['color' => 'Koyu Mavi', 'size' => '260g Şok Buhar', 'stock' => 25]],
            ],
            [
                'name' => 'IKEA NISSAFORS 3 Katlı Çok Amaçlı Tekerlekli Mutfak Arabası',
                'cat' => 'Mobilya & Dekorasyon', 'brand' => 'IKEA', 'seller' => 'ev', 'price' => 1199.00,
                'img' => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => '50.5x30x83 cm', 'stock' => 35], ['color' => 'Beyaz', 'size' => '50.5x30x83 cm', 'stock' => 20]],
            ],
            [
                'name' => 'English Home Jakarlı Pamuklu 2 Parça Banyo Paspas Takımı',
                'cat' => 'Ev Tekstili', 'brand' => 'English Home', 'seller' => 'ev', 'price' => 599.00,
                'img' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Bej', 'size' => '60x100 + 50x60 cm', 'stock' => 45]],
            ],
            [
                'name' => 'Bosch ErgoMixx 1000W 4 Bıçaklı Paslanmaz Çelik El Blender Seti',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Bosch', 'seller' => 'ev', 'price' => 2899.00,
                'img' => 'https://images.unsplash.com/photo-1570222094114-d054a817e56b?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1585515320310-259814833e62?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'İnoks Metalik', 'size' => '1000 Watt Motor', 'stock' => 30]],
            ],
            [
                'name' => 'Karaca Retro 1.7L Çelik Su Isıtıcı Kettle',
                'cat' => 'Elektrikli Ev Aletleri', 'brand' => 'Karaca', 'seller' => 'ev', 'price' => 1499.00,
                'img' => 'https://images.unsplash.com/photo-1588854337236-6889d631faa8?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Vintage Krem', 'size' => '1.7 Litre Kapasite', 'stock' => 35], ['color' => 'Bebek Mavisi', 'size' => '1.7 Litre Kapasite', 'stock' => 20]],
            ],
            [
                'name' => 'IKEA HEKTAR Koyu Gri Metal Zemin ve Okuma Lambaderi',
                'cat' => 'Mobilya & Dekorasyon', 'brand' => 'IKEA', 'seller' => 'ev', 'price' => 2199.00,
                'img' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Koyu Gri Mat Metal', 'size' => '181 cm Yükseklik', 'stock' => 20]],
            ],

            // --- SPOR & OUTDOOR (20 ÜRÜN) ---
            [
                'name' => 'The North Face 1996 Retro Nuptse 700 Kaz Tüyü Şişme Mont',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'The North Face', 'seller' => 'spor', 'price' => 14999.00,
                'img' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'İkonik Siyah', 'size' => 'M', 'stock' => 15], ['color' => 'İkonik Siyah', 'size' => 'L', 'stock' => 20], ['color' => 'Sarı/Siyah', 'size' => 'XL', 'stock' => 10]],
            ],
            [
                'name' => 'The North Face Borealis 28L Çok Bölmeli Outdoor Sırt Çantası',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'The North Face', 'seller' => 'spor', 'price' => 4599.00,
                'img' => 'https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Asfalt Grisi/Siyah', 'size' => '28 Litre', 'stock' => 30], ['color' => 'Zeytin Yeşili', 'size' => '28 Litre', 'stock' => 15]],
            ],
            [
                'name' => 'The North Face Stormbreak 2 Kişilik 3 Mevsim Kamp Çadırı',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'The North Face', 'seller' => 'spor', 'price' => 8999.00,
                'img' => 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Altın Sarısı / Gri', 'size' => '2 Kişilik (2.4 kg)', 'stock' => 12]],
            ],
            [
                'name' => 'Columbia Newton Ridge Plus II Su Geçirmez Outdoor Doğa Botu',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Columbia', 'seller' => 'spor', 'price' => 5499.00,
                'img' => 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Kahve Süet / Siyah', 'size' => '42', 'stock' => 18], ['color' => 'Kahve Süet / Siyah', 'size' => '43', 'stock' => 25], ['color' => 'Kahve Süet / Siyah', 'size' => '44', 'stock' => 14]],
            ],
            [
                'name' => 'Columbia Steens Mountain 2.0 Tam Fermuarlı Kalın Polar Ceket',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Columbia', 'seller' => 'spor', 'price' => 2199.00,
                'img' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1509967419530-da38b4704bc6?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Lacivert Çivit', 'size' => 'M', 'stock' => 20], ['color' => 'Kömür Grisi', 'size' => 'L', 'stock' => 30]],
            ],
            [
                'name' => 'Decathlon Quechua 2 Seconds 3 Kişilik Otomatik Kurulan Kamp Çadırı',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 3899.00,
                'img' => 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Petrol Yeşili', 'size' => '3 Kişilik Standart', 'stock' => 25], ['color' => 'Fresh & Black Güneş Korumalı', 'size' => '3 Kişilik Karartmalı', 'stock' => 15]],
            ],
            [
                'name' => 'Decathlon Forclaz 1 Litre Çift Cidarlı Paslanmaz Çelik Kamp Termosu',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 749.00,
                'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Fırçalanmış Paslanmaz Metal', 'size' => '1.0 Litre', 'stock' => 50], ['color' => 'Mat Haki Yeşil', 'size' => '1.0 Litre', 'stock' => 40]],
            ],
            [
                'name' => 'Decathlon Quechua Kol Destekli Katlanır Kamp ve Balıkçı Sandalyesi',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 699.00,
                'img' => 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1510312305653-8ed496efae75?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Koyu Mavi', 'size' => '110kg Taşıma Kapasitesi', 'stock' => 60], ['color' => 'Asker Yeşili', 'size' => '110kg Taşıma Kapasitesi', 'stock' => 45]],
            ],
            [
                'name' => 'Voit Alpha Katlanabilir Motorlu Masaj Aparatlı Koşu Bandı',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Voit', 'seller' => 'spor', 'price' => 21999.00,
                'img' => 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah/Gümüş', 'size' => '3 HP Motor / 120kg', 'stock' => 8]],
            ],
            [
                'name' => 'Voit Smart Bluetooth Bağlantılı Manyetik Kondisyon Bisikleti',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Voit', 'seller' => 'spor', 'price' => 9999.00,
                'img' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => '8 Kademeli Direnç', 'stock' => 14]],
            ],
            [
                'name' => 'Voit 20kg Krom Vidalı Çantalı Ayarlanabilir Dambıl Ağırlık Seti',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Voit', 'seller' => 'spor', 'price' => 1899.00,
                'img' => '/images/products/dumbbells.jpg',
                'gallery' => ['/images/products/dumbbells.jpg'],
                'variants' => [['color' => 'Parlak Krom', 'size' => '20kg Taşıma Çantalı', 'stock' => 30]],
            ],
            [
                'name' => 'Voit Kalın Kaydırmaz Askılı Egzersiz ve Yoga Pilates Matı',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Voit', 'seller' => 'spor', 'price' => 549.00,
                'img' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mor', 'size' => '15mm Ekstra Kalın', 'stock' => 40], ['color' => 'Gece Mavisi', 'size' => '15mm Ekstra Kalın', 'stock' => 35]],
            ],
            [
                'name' => 'Xiaomi Mi Electric Scooter 4 Pro 45km Menzil 700W Motor',
                'cat' => 'Bisiklet & Ekipman', 'brand' => 'Xiaomi', 'seller' => 'spor', 'price' => 24999.00,
                'img' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1519750157634-b6d493a0f77c?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => '10 İnç Tubeless Lastik', 'stock' => 15]],
            ],
            [
                'name' => 'Decathlon Triban RC120 Disk Frenli 16 Vites Yol Bisikleti',
                'cat' => 'Bisiklet & Ekipman', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 19999.00,
                'img' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Donanma Grisi', 'size' => 'M Kadro (165-177cm)', 'stock' => 10], ['color' => 'Donanma Grisi', 'size' => 'L Kadro (177-186cm)', 'stock' => 12]],
            ],
            [
                'name' => 'Decathlon Rockrider ST530 27.5 İnç Dağ Bisikleti',
                'cat' => 'Bisiklet & Ekipman', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 15499.00,
                'img' => 'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Fosforlu Sarı/Siyah', 'size' => 'L Kadro Tek Yaprak 9 Vites', 'stock' => 8]],
            ],
            [
                'name' => 'Nike Pro Dri-FIT Erkek Sıkıştırmalı Tayt ve Şort Seti',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Nike', 'seller' => 'spor', 'price' => 1699.00,
                'img' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1538805060514-97d9cc17730c?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Siyah Kompresyon', 'size' => 'M', 'stock' => 25], ['color' => 'Siyah Kompresyon', 'size' => 'L', 'stock' => 30]],
            ],
            [
                'name' => 'The North Face Su Geçirmez Korumalı Doğa Yürüyüş Şapkası',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'The North Face', 'seller' => 'spor', 'price' => 1299.00,
                'img' => 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1521369909029-2afed882baee?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Bej Haki', 'size' => 'Geniş Siperlikli UV Korumalı', 'stock' => 30]],
            ],
            [
                'name' => 'Columbia Silver Ridge Kargo Doğa Yürüyüş Pantolonu',
                'cat' => 'Kamp & Doğa Malzemeleri', 'brand' => 'Columbia', 'seller' => 'spor', 'price' => 2799.00,
                'img' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1542272604-780c96856553?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Fosilleşmiş Kum Beji', 'size' => '32/32', 'stock' => 20], ['color' => 'Kömür Yeşili', 'size' => '34/32', 'stock' => 15]],
            ],
            [
                'name' => 'Decathlon Btwin 500 Korumalı Güçlü Kask ve Aksesuar Paketi',
                'cat' => 'Bisiklet & Ekipman', 'brand' => 'Decathlon', 'seller' => 'spor', 'price' => 1199.00,
                'img' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => 'Mat Siyah', 'size' => 'L (59-62cm Ayarlı)', 'stock' => 30]],
            ],
            [
                'name' => 'Voit Direnç Lastiği 5 Farklı Sertlik Seviyeli Egzersiz Bandı Seti',
                'cat' => 'Fitness & Kondisyon', 'brand' => 'Voit', 'seller' => 'spor', 'price' => 449.00,
                'img' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80',
                'gallery' => ['https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&auto=format&fit=crop&q=80'],
                'variants' => [['color' => '5 Renk Takım', 'size' => 'Lateks Direnç Paketi', 'stock' => 50]],
            ],

            // --- KOZMETİK & KİŞİSEL BAKIM (20 ÜRÜN) ---
            [
                'name' => 'Dyson Airwrap Multi-Styler Saç Şekillendirici ve Kurutucu Set',
                'cat' => 'Saç Bakımı & Şekillendirme', 'brand' => 'Dyson', 'seller' => 'kozmetik', 'price' => 24999.00,
                'img' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1519735777090-ec97162dc266?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Bakır / Nikel Parlak', 'size' => 'Uzun Başlıklı Özel Seri', 'stock' => 12], ['color' => 'Mavi / Allık Pembesi', 'size' => 'Uzun Başlıklı Özel Seri', 'stock' => 10]],
            ],
            [
                'name' => 'Dyson Supersonic HD07 Akıllı Isı Kontrollü Saç Kurutma Makinesi',
                'cat' => 'Saç Bakımı & Şekillendirme', 'brand' => 'Dyson', 'seller' => 'kozmetik', 'price' => 17999.00,
                'img' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1519735777090-ec97162dc266?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Demir / Fuşya', 'size' => '5 Manyetik Başlıklı', 'stock' => 15]],
            ],
            [
                'name' => 'Giorgio Armani Acqua Di Gio Profondo Erkek Parfüm EDP 100ml',
                'cat' => 'Parfüm & Deodorant', 'brand' => 'Armani', 'seller' => 'kozmetik', 'price' => 4899.00,
                'img' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617897903246-719242758050?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Okyanus Mavisi Şişe', 'size' => '100 ml EDP', 'stock' => 25], ['color' => 'Okyanus Mavisi Şişe', 'size' => '200 ml EDP', 'stock' => 10]],
            ],
            [
                'name' => "L'Oréal Paris Revitalift Saf Hyaluronik Asit Kırışıklık Karşıtı Yüz Serumu 30ml",
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => "L'Oréal", 'seller' => 'kozmetik', 'price' => 649.00,
                'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Cam Şişe Damlalıklı', 'size' => '30 ml', 'stock' => 60], ['color' => 'Cam Şişe Damlalıklı', 'size' => '50 ml Mega Boy', 'stock' => 35]],
            ],
            [
                'name' => "L'Oréal Paris Telescopic Uzatıcı ve Ayırıcı Siyah Maskara",
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => "L'Oréal", 'seller' => 'kozmetik', 'price' => 429.00,
                'img' => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Karbon Siyah', 'size' => '8 ml', 'stock' => 80]],
            ],
            [
                'name' => "L'Oréal Paris Elseve Mucizevi Yağ Saç Bakım Serumu 100ml",
                'cat' => 'Saç Bakımı & Şekillendirme', 'brand' => "L'Oréal", 'seller' => 'kozmetik', 'price' => 349.00,
                'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Değerli Çiçek Özleri', 'size' => '100 ml Pompalı', 'stock' => 70]],
            ],
            [
                'name' => 'Nivea Q10 Kırışıklık Karşıtı Sıkılaştırıcı Gündüz Kremi SPF 15 50ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Nivea', 'seller' => 'kozmetik', 'price' => 449.00,
                'img' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Beyaz / Sarı Kavanoz', 'size' => '50 ml', 'stock' => 50]],
            ],
            [
                'name' => 'Nivea Luminous 630 Leke Karşıtı Aydınlatıcı Gece Bakım Serumu',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Nivea', 'seller' => 'kozmetik', 'price' => 799.00,
                'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Altın Yaldızlı Şişe', 'size' => '30 ml', 'stock' => 40]],
            ],
            [
                'name' => 'Nivea Men Black & White Invisible Leke Karşıtı 48 Saat Deodorant Sprey 200ml',
                'cat' => 'Parfüm & Deodorant', 'brand' => 'Nivea', 'seller' => 'kozmetik', 'price' => 199.00,
                'img' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617897903246-719242758050?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Siyah/Beyaz Şişe', 'size' => '200 ml Tekli', 'stock' => 90], ['color' => 'Siyah/Beyaz Şişe', 'size' => '3x200 ml Avantaj Paketi', 'stock' => 40]],
            ],
            [
                'name' => 'Clinique Moisture Surge 100 Saat Etkili Yoğun Nemlendirici Jel Krem 50ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Clinique', 'seller' => 'kozmetik', 'price' => 1899.00,
                'img' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Pembe Jel Kavanoz', 'size' => '50 ml', 'stock' => 30], ['color' => 'Pembe Jel Kavanoz', 'size' => '125 ml Mega Boy', 'stock' => 15]],
            ],
            [
                'name' => 'Clinique Take The Day Off İnatçı Makyaj Temizleme Katı Balmı 125ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Clinique', 'seller' => 'kozmetik', 'price' => 1649.00,
                'img' => 'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Mor Kavanoz', 'size' => '125 ml', 'stock' => 25]],
            ],
            [
                'name' => 'Dyson Corrale Kablosuz Esnek Plakalı Saç Düzleştirici',
                'cat' => 'Saç Bakımı & Şekillendirme', 'brand' => 'Dyson', 'seller' => 'kozmetik', 'price' => 19999.00,
                'img' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1519735777090-ec97162dc266?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Bakır / Fuşya', 'size' => 'Seyahat Şarj Üniteli', 'stock' => 10]],
            ],
            [
                'name' => 'Giorgio Armani Si Passione Kadın Parfüm EDP 100ml',
                'cat' => 'Parfüm & Deodorant', 'brand' => 'Armani', 'seller' => 'kozmetik', 'price' => 5299.00,
                'img' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1617897903246-719242758050?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Lüks Kırmızı Cam Şişe', 'size' => '100 ml EDP', 'stock' => 20]],
            ],
            [
                'name' => "L'Oréal Men Expert Hydra Energetic Yorgunluk Karşıtı C Vitamini Nemlendirici",
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => "L'Oréal", 'seller' => 'kozmetik', 'price' => 389.00,
                'img' => 'https://images.unsplash.com/photo-1617897903246-719242758050?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Turuncu/Siyah Pompa', 'size' => '50 ml', 'stock' => 45]],
            ],
            [
                'name' => 'Nivea Sun Koruma & Ferahlık Transparan Güneş Koruyucu Sprey SPF 50+',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Nivea', 'seller' => 'kozmetik', 'price' => 549.00,
                'img' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Mavi Şişe', 'size' => '200 ml Yüksek Koruma', 'stock' => 40]],
            ],
            [
                'name' => 'Clinique Dramatically Different Nemlendirici Yağsız Yüz Jeli 125ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Clinique', 'seller' => 'kozmetik', 'price' => 1749.00,
                'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Sarı Şeffaf Şişe', 'size' => '125 ml Pompalı', 'stock' => 30]],
            ],
            [
                'name' => "L'Oréal Paris True Match Hyaluronik Asitli Bakım Yapan Fondöten",
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => "L'Oréal", 'seller' => 'kozmetik', 'price' => 489.00,
                'img' => 'https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => '1.N Ivory Açık Ton', 'size' => '30 ml', 'stock' => 25], ['color' => '2.N Vanilla Doğal Ton', 'size' => '30 ml', 'stock' => 35], ['color' => '3.D/3.W Golden Beige Buğday', 'size' => '30 ml', 'stock' => 20]],
            ],
            [
                'name' => 'Nivea Soft E Vitaminli Yoğun Nemlendirici Hızlı Emilen Bakım Kremi 300ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Nivea', 'seller' => 'kozmetik', 'price' => 249.00,
                'img' => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Beyaz Geniş Kavanoz', 'size' => '300 ml Aile Boyu', 'stock' => 80]],
            ],
            [
                'name' => 'Clinique All About Eyes Göz Altı Morluk ve Torba Karşıtı Krem 15ml',
                'cat' => 'Cilt & Yüz Bakımı', 'brand' => 'Clinique', 'seller' => 'kozmetik', 'price' => 1499.00,
                'img' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Şeftali Tonlu Kavanoz', 'size' => '15 ml', 'stock' => 25]],
            ],
            [
                'name' => 'Dyson Airstrait Islak ve Kuru Saç Düzleştirici Sıcak Hava Teknolojisi',
                'cat' => 'Saç Bakımı & Şekillendirme', 'brand' => 'Dyson', 'seller' => 'kozmetik', 'price' => 23999.00,
                'img' => 'https://images.unsplash.com/photo-1607613009820-a29f7bb81c04?w=800&auto=format&fit=crop&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1519735777090-ec97162dc266?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                ],
                'variants' => [['color' => 'Prusya Mavisi / Parlak Bakır', 'size' => 'Plakasız Hava Akımı', 'stock' => 12]],
            ],
        ];

        // Kategoriye ve Satıcıya Göre Zengin Galeri Görsel Havuzları (Her Ürüne En Az 6 Farklı Görsel)
        $galleryPools = [
            'tekno' => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1616469829941-c7200edec809?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=800&auto=format&fit=crop&q=80',
            ],
            'moda' => [
                'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=800&auto=format&fit=crop&q=80',
            ],
            'ev' => [
                'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1616046229478-9901c5536a45?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1615873968403-89e068629265?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1540518614846-7ede433c4ef7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&auto=format&fit=crop&q=80',
            ],
            'spor' => [
                'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=800&auto=format&fit=crop&q=80',
            ],
            'kozmetik' => [
                'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1526947425960-945c6e72858f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        // 100 Ürünü ve Varyantlarını Veritabanına Yaz
        $createdProducts = [];
        foreach ($catalog as $pIdx => $item) {
            $cat = $categoriesByName[$item['cat']] ?? Category::first();
            $brand = $brandsByName[$item['brand']] ?? null;
            $seller = $sellersByKey[$item['seller']] ?? $sellersByKey['tekno'];

            $slug = Str::slug($item['name']).'-'.($pIdx + 1);

            // Her ürün için en az 5 galeri görseli + 1 ana görsel = toplamda en az 6 farklı görsel garantisi
            $gallery = $item['gallery'] ?? [];
            $mainImg = $item['img'];
            $sellerKey = $item['seller'] ?? 'tekno';
            $pool = $galleryPools[$sellerKey] ?? $galleryPools['tekno'];

            $poolFiltered = array_values(array_filter($pool, fn ($url) => $url !== $mainImg && ! in_array($url, $gallery)));
            $merged = array_values(array_unique(array_merge($gallery, $poolFiltered)));
            $finalGallery = array_values(array_filter($merged, fn ($url) => $url !== $mainImg));

            if (count($finalGallery) < 5) {
                foreach ($galleryPools as $altPool) {
                    foreach ($altPool as $candidate) {
                        if ($candidate !== $mainImg && ! in_array($candidate, $finalGallery)) {
                            $finalGallery[] = $candidate;
                            if (count($finalGallery) >= 5) {
                                break 2;
                            }
                        }
                    }
                }
            }

            // En az 5 farklı galeri görseli al (ana görselle birlikte en az 6 görsel)
            $finalGallery = array_values(array_slice($finalGallery, 0, max(5, count($gallery))));

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'seller_id' => $seller->id,
                    'category_id' => $cat->id,
                    'brand_id' => $brand?->id,
                    'name' => $item['name'],
                    'image_url' => $item['img'],
                    'gallery' => $finalGallery,
                    'description' => "Bu ürün resmi distribütör garantili olup, yetkili satıcı {$seller->store_name} tarafından AlSepete güvencesiyle gönderilmektedir. %100 orijinal ve faturalı üründür. 14 gün koşulsuz iade hakkınız bulunmaktadır.",
                    'status' => 'aktif',
                ]
            );

            $createdProducts[] = $product;

            $hasDiscount = (($pIdx + 1) % 3 === 0);
            $basePrice = $item['price'];

            foreach ($item['variants'] as $vIdx => $vData) {
                $varPrice = $basePrice + ($vIdx * 100.00);
                $discountPrice = $hasDiscount ? round($varPrice * 0.90, 2) : null;

                ProductVariant::updateOrCreate(
                    ['sku' => 'SKU-P'.($pIdx + 1).'-V'.($vIdx + 1)],
                    [
                        'product_id' => $product->id,
                        'color' => $vData['color'],
                        'size' => $vData['size'],
                        'price' => $varPrice,
                        'discount_price' => $discountPrice,
                        'stock' => $vData['stock'],
                    ]
                );
            }
        }

        // 8. Örnek Bannerlar
        Banner::firstOrCreate(
            ['title' => 'Elektronik Günleri İndirimi'],
            [
                'image' => '/images/banners/banner1.jpg',
                'url' => '/kategori/elektronik',
                'sort' => 1,
                'is_active' => true,
            ]
        );
        Banner::firstOrCreate(
            ['title' => 'Yeni Sezon Spor ve Outdoor'],
            [
                'image' => '/images/banners/banner2.jpg',
                'url' => '/kategori/spor-outdoor',
                'sort' => 2,
                'is_active' => true,
            ]
        );

        // 9. Örnek Kuponlar
        Coupon::firstOrCreate(
            ['code' => 'HOSGELDIN10'],
            [
                'type' => 'yuzde',
                'value' => 10.00,
                'min_total' => 250.00,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(6),
                'usage_limit' => 1000,
                'used_count' => 0,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'INDIRIM100'],
            [
                'type' => 'tutar',
                'value' => 100.00,
                'min_total' => 1000.00,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'usage_limit' => 500,
                'used_count' => 0,
            ]
        );

        // 10. Bonus 2: Gerçekçi ve Zengin Satıcı Soru & Cevapları
        $customerUser = User::where('email', 'musteri1@alsepete.com')->first();
        $customerId = $customerUser ? $customerUser->id : 1;

        // Ürün 1: iPhone 15 Pro Max
        if (isset($createdProducts[0])) {
            $p = $createdProducts[0];
            ProductQuestion::firstOrCreate(
                ['question' => 'Kutu içeriğinde şarj adaptörü bulunuyor mu yoksa sadece Type-C kablo mu çıkıyor?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => 'Merhaba, Apple küresel standartları gereği kutu içeriğinde sadece Type-C örgü şarj kablosu bulunmaktadır. 20W veya üzeri orijinal şarj adaptörünü mağazamızdan ayrıca temin edebilirsiniz. İlginiz için teşekkür ederiz.',
                    'answered_at' => now()->subHours(6),
                    'is_approved' => true,
                ]
            );

            ProductQuestion::firstOrCreate(
                ['question' => 'Cihaz Apple Türkiye garantili mi, garanti süresi ne zaman başlıyor?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => 'Merhaba, ürünümüz %100 sıfır, kapalı kutu ve 2 yıl resmi Apple Türkiye garantilidir. Fatura kesim tarihi ve cihazı ilk açıp Apple Kimliğinizi girdiğiniz gün garanti süresi otomatik olarak başlar.',
                    'answered_at' => now()->subHours(2),
                    'is_approved' => true,
                ]
            );

            ProductQuestion::firstOrCreate(
                ['question' => 'Bugün sipariş versem aynı gün kargoya teslim edilir mi? Hangi kargo firmasıyla gönderiyorsunuz?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => null, // Henüz cevaplanmamış soru örneği
                    'answered_at' => null,
                    'is_approved' => true,
                ]
            );
        }

        // Ürün 21 (Moda): Nike Air Jordan 1
        if (isset($createdProducts[20])) {
            $p = $createdProducts[20];
            ProductQuestion::firstOrCreate(
                ['question' => 'Kalıpları nasıl, normal ayakkabı numaramı mı almalıyım yoksa yarım numara büyük mü tercih etmeliyim?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => 'Merhaba, Air Jordan 1 modelleri standart tam kalıptır (True to Size). Ancak taraklı veya geniş ayak yapısına sahipseniz yarım numara büyük tercih etmeniz daha konforlu olacaktır.',
                    'answered_at' => now()->subHours(8),
                    'is_approved' => true,
                ]
            );
        }

        // Ürün 41 (Ev): Karaca Tencere Seti
        if (isset($createdProducts[40])) {
            $p = $createdProducts[40];
            ProductQuestion::firstOrCreate(
                ['question' => 'Bu tencere seti indüksiyonlu ocaklarda çalışıyor mu ve bulaşık makinesinde yıkanabilir mi?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => 'Merhaba, özel indüksiyon tabanı sayesinde indüksiyon dahil tüm ocak tipleriyle tam uyumludur. Bulaşık makinesinde hassas programda yıkanabilir, ancak ürün ömrünün maksimum seviyede kalması için elde yıkama önerilir.',
                    'answered_at' => now()->subHours(12),
                    'is_approved' => true,
                ]
            );
        }

        // Ürün 61 (Spor): The North Face Nuptse Mont
        if (isset($createdProducts[60])) {
            $p = $createdProducts[60];
            ProductQuestion::firstOrCreate(
                ['question' => 'Mont orijinal kaz tüyü mü ve kaç dolum tüy oranına sahip?'],
                [
                    'product_id' => $p->id,
                    'user_id' => $customerId,
                    'seller_id' => $p->seller_id,
                    'answer' => 'Merhaba, evet ürünümüz 700 fill power (dolum) RDS sertifikalı saf kaz tüyü dolguludur. En soğuk kış günlerinde dahi olağanüstü sıcaklık sağlar.',
                    'answered_at' => now()->subHours(4),
                    'is_approved' => true,
                ]
            );
        }
    }
}
