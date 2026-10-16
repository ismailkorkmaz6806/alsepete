<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
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
     * 1 admin, 5 satıcı, 20 müşteri, 15 kategori, 100 ürün.
     */
    public function run(): void
    {
        // 1. Rolleri Olustur (Spatie tablolarina dogrudan guvenli ekleme)
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
            ['email' => 'admin@pazaryeri.com'],
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

        // 3. 15 Hiyerarsik Kategori (5 Ana Kategori, her birinde 2 Alt Kategori)
        $categoryTree = [
            'Elektronik' => [
                'commission' => 8.00,
                'sub' => ['Bilgisayar & Tablet', 'Telefon & Aksesuar']
            ],
            'Moda & Giyim' => [
                'commission' => 15.00,
                'sub' => ['Erkek Giyim', 'Kadın Giyim']
            ],
            'Ev & Yaşam' => [
                'commission' => 12.00,
                'sub' => ['Mobilya', 'Mutfak Gereçleri']
            ],
            'Spor & Outdoor' => [
                'commission' => 10.00,
                'sub' => ['Fitness & Kondisyon', 'Kamp Malzemeleri']
            ],
            'Kitap & Hobi' => [
                'commission' => 6.00,
                'sub' => ['Edebiyat Kitapları', 'Eğitici Oyunlar']
            ],
        ];

        $allCategories = collect();
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
            $allCategories->push($parentCat);

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
                $allCategories->push($subCat);
            }
        }

        // 4. Markalar
        $brandNames = ['Apple', 'Samsung', 'Nike', 'Adidas', 'Philips', 'Sony', 'Bosch', 'Zara', 'Puma', 'Xiaomi'];
        $createdBrands = collect();
        foreach ($brandNames as $bName) {
            $createdBrands->push(
                Brand::firstOrCreate(
                    ['slug' => Str::slug($bName)],
                    ['name' => $bName, 'logo' => null]
                )
            );
        }

        // 5. 5 Onayli Satici ve Kullanici Hesaplari
        $sellerData = [
            ['name' => 'Ahmet Satıcı', 'email' => 'satici1@pazaryeri.com', 'store' => 'TeknoMarket', 'rate' => 8.00],
            ['name' => 'Mehmet Tekstil', 'email' => 'satici2@pazaryeri.com', 'store' => 'ModaTrend', 'rate' => 14.00],
            ['name' => 'Ayşe Dizayn', 'email' => 'satici3@pazaryeri.com', 'store' => 'EvDekor', 'rate' => 12.00],
            ['name' => 'Caner Spor', 'email' => 'satici4@pazaryeri.com', 'store' => 'AktifSpor', 'rate' => 10.00],
            ['name' => 'Zeynep Kitap', 'email' => 'satici5@pazaryeri.com', 'store' => 'KitapKafası', 'rate' => 6.00],
        ];

        $createdSellers = collect();
        foreach ($sellerData as $idx => $s) {
            $user = User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'phone' => '0532000000' . ($idx + 1),
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
                    'tax_number' => '123456789' . $idx,
                    'iban' => 'TR' . str_pad($idx . '987654321', 24, '0', STR_PAD_RIGHT),
                    'status' => 'onayli',
                    'commission_rate' => $s['rate'],
                ]
            );

            $createdSellers->push($seller);
        }

        // 6. 20 Musteri ve Adresleri
        for ($i = 1; $i <= 20; $i++) {
            $customer = User::firstOrCreate(
                ['email' => "musteri{$i}@pazaryeri.com"],
                [
                    'name' => "Müşteri {$i}",
                    'phone' => '054400000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            DB::table('model_has_roles')->updateOrInsert(
                ['role_id' => $customerRoleId, 'model_type' => User::class, 'model_id' => $customer->id]
            );

            // Her müşteriye varsayılan adres
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

        // 7. 100 Urun ve Varyantlari (Kural 1: decimal(10,2) fiyat, Kural 3: stok varyantta)
        $sampleProducts = [
            ['title' => 'Ultra HD Smart LED Televizyon', 'base_price' => 14999.00, 'cat' => 'Elektronik'],
            ['title' => 'Kablosuz Gürültü Engelleyici Kulaklık', 'base_price' => 2499.00, 'cat' => 'Elektronik'],
            ['title' => 'Oyuncu Laptop 16GB RAM 512GB SSD', 'base_price' => 32999.00, 'cat' => 'Elektronik'],
            ['title' => 'Akıllı Telefon 128GB 5G', 'base_price' => 18500.00, 'cat' => 'Elektronik'],
            ['title' => 'Mekanik RGB Oyuncu Klavyesi', 'base_price' => 1899.00, 'cat' => 'Elektronik'],
            ['title' => 'Slim Fit Erkek Pamuklu Gömlek', 'base_price' => 649.00, 'cat' => 'Moda & Giyim'],
            ['title' => 'Oversize Kadın Kapüşonlu Sweatshirt', 'base_price' => 899.00, 'cat' => 'Moda & Giyim'],
            ['title' => 'Deri Tabanlı Koşu Ayakkabısı', 'base_price' => 2199.00, 'cat' => 'Moda & Giyim'],
            ['title' => 'Klasik Su Geçirmez Trençkot', 'base_price' => 2850.00, 'cat' => 'Moda & Giyim'],
            ['title' => 'Ergonomik Fileli Çalışma Koltuğu', 'base_price' => 4500.00, 'cat' => 'Ev & Yaşam'],
            ['title' => 'Çelik Gövdeli Döküm Tava Seti', 'base_price' => 1950.00, 'cat' => 'Ev & Yaşam'],
            ['title' => 'Pamuk Saten Çift Kişilik Nevresim Takımı', 'base_price' => 1250.00, 'cat' => 'Ev & Yaşam'],
            ['title' => 'Ayarlanabilir Ağırlık Dambıl Seti 20kg', 'base_price' => 1450.00, 'cat' => 'Spor & Outdoor'],
            ['title' => 'Otomatik Kurulum 4 Kişilik Kamp Çadırı', 'base_price' => 3200.00, 'cat' => 'Spor & Outdoor'],
            ['title' => 'Termos Çelik Matara 1 Litre', 'base_price' => 599.00, 'cat' => 'Spor & Outdoor'],
            ['title' => 'Dünya Klasikleri 10 Kitap Seti', 'base_price' => 450.00, 'cat' => 'Kitap & Hobi'],
            ['title' => 'Strateji ve Kutu Oyunu Koleksiyon Serisi', 'base_price' => 780.00, 'cat' => 'Kitap & Hobi'],
        ];

        $colors = ['Siyah', 'Beyaz', 'Gümüş Gri', 'Lacivert', 'Kırmızı'];
        $sizes = ['Standard', 'Small', 'Medium', 'Large', '128GB', '256GB'];

        for ($pIndex = 1; $pIndex <= 100; $pIndex++) {
            $template = $sampleProducts[($pIndex - 1) % count($sampleProducts)];
            $assignedSeller = $createdSellers[($pIndex - 1) % $createdSellers->count()];
            $assignedCategory = $allCategories[($pIndex - 1) % $allCategories->count()];
            $assignedBrand = $createdBrands[($pIndex - 1) % $createdBrands->count()];

            $productName = $template['title'] . ' Model ' . $pIndex;
            $slug = Str::slug($productName) . '-' . $pIndex;

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'seller_id' => $assignedSeller->id,
                    'category_id' => $assignedCategory->id,
                    'brand_id' => $assignedBrand->id,
                    'name' => $productName,
                    'description' => "Bu ürün {$assignedSeller->store_name} güvencesiyle sunulmaktadır. 2 yıl resmi üretici garantilidir.",
                    'status' => 'aktif',
                ]
            );

            // Her ürüne 2 adet varyant (Örn: Renk veya Beden seçenekleri)
            $var1Color = $colors[($pIndex) % count($colors)];
            $var2Color = $colors[($pIndex + 1) % count($colors)];
            $varSize = $sizes[($pIndex) % count($sizes)];

            $price = $template['base_price'] + (($pIndex % 10) * 15.00);
            $hasDiscount = ($pIndex % 3 === 0);
            $discountPrice = $hasDiscount ? round($price * 0.90, 2) : null;

            ProductVariant::firstOrCreate(
                ['sku' => "SKU-P{$pIndex}-VAR1"],
                [
                    'product_id' => $product->id,
                    'color' => $var1Color,
                    'size' => $varSize,
                    'price' => $price,
                    'discount_price' => $discountPrice,
                    'stock' => 25 + ($pIndex % 50),
                ]
            );

            ProductVariant::firstOrCreate(
                ['sku' => "SKU-P{$pIndex}-VAR2"],
                [
                    'product_id' => $product->id,
                    'color' => $var2Color,
                    'size' => $varSize,
                    'price' => $price + 50.00,
                    'discount_price' => $hasDiscount ? round(($price + 50.00) * 0.90, 2) : null,
                    'stock' => 15 + ($pIndex % 30),
                ]
            );
        }

        // 8. Ornek Bannerlar
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

        // 9. Ornek Kuponlar
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
    }
}
