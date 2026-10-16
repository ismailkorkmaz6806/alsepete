<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PazarYeri - Güvenli ve Çok Satıcılı Alışveriş</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- 1. Üst Duyuru Çubuğu -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fa-solid fa-truck-fast text-orange-400 mr-1"></i> Tüm Türkiye'ye Hızlı Kargo</span>
                <span class="hidden sm:inline"><i class="fa-solid fa-shield-halved text-emerald-400 mr-1"></i> %100 Güvenli Ödeme</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/satici" class="text-orange-400 hover:text-orange-300 font-medium">PazarYeri'nde Satıcı Ol</a>
                <span class="text-slate-600">|</span>
                <a href="#" class="hover:text-white">Yardım & Destek</a>
            </div>
        </div>
    </div>

    <!-- 2. Ana Header & Arama Çubuğu -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-6">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-tr from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white text-xl font-black shadow-md shadow-orange-500/20">
                    P
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-gray-900 tracking-tight leading-none">Pazar<span class="text-orange-600">Yeri</span></span>
                    <span class="text-[10px] text-gray-400 font-semibold tracking-wider uppercase">Çok Satıcılı Pazar</span>
                </div>
            </a>

            <!-- Arama Kutusu -->
            <div class="flex-1 max-w-2xl relative">
                <div class="relative">
                    <input type="text" placeholder="Ürün, marka veya kategori ara..." class="w-full bg-gray-100 border border-transparent focus:border-orange-500 focus:bg-white focus:outline-none rounded-xl py-2.5 pl-11 pr-24 text-sm transition-all duration-200">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <button class="absolute right-1.5 top-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                        Ara
                    </button>
                </div>
            </div>

            <!-- Kullanıcı Aksiyonları -->
            <div class="flex items-center space-x-6">
                <!-- Hesabım -->
                <a href="/hesabim" class="flex items-center gap-2 text-gray-700 hover:text-orange-600 transition-colors group">
                    <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-orange-50 group-hover:text-orange-600 transition-colors">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-xs text-gray-400 font-medium leading-none">Giriş Yap</span>
                        <span class="text-sm font-semibold text-gray-800 leading-tight">Hesabım</span>
                    </div>
                </a>

                <!-- Favoriler -->
                <a href="#" class="relative text-gray-700 hover:text-orange-600 transition-colors p-1">
                    <i class="fa-regular fa-heart text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-gray-200 text-gray-700 text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
                </a>

                <!-- Sepetim -->
                <a href="/sepet" class="flex items-center gap-2 bg-orange-50 hover:bg-orange-100 text-orange-600 px-3.5 py-2 rounded-xl transition-colors font-semibold text-sm">
                    <div class="relative">
                        <i class="fa-solid fa-bag-shopping text-base"></i>
                        <span class="absolute -top-2 -right-2 bg-orange-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </div>
                    <span class="hidden sm:inline">Sepetim</span>
                </a>
            </div>
        </div>

        <!-- 3. Kategori Menüsü -->
        <nav class="border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 flex items-center space-x-8 overflow-x-auto text-sm font-medium py-2.5 scrollbar-none">
                <a href="#" class="text-gray-900 font-bold flex items-center gap-1.5 whitespace-nowrap hover:text-orange-600">
                    <i class="fa-solid fa-bars text-orange-600"></i> Tüm Kategoriler
                </a>
                @foreach($categories as $category)
                    <a href="/kategori/{{ $category->slug }}" class="text-gray-600 hover:text-orange-600 transition-colors whitespace-nowrap">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </nav>
    </header>

    <!-- 4. Hero Banner -->
    <main class="max-w-7xl mx-auto px-4 py-6 space-y-8">
        <section class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-orange-600 via-amber-600 to-rose-600 text-white p-8 md:p-12 shadow-lg">
            <div class="max-w-xl space-y-4">
                <span class="inline-block bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">
                    Büyük Sezon İndirimleri
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
                    Binlerce Üründe %40'a Varan Fırsatlar
                </h1>
                <p class="text-orange-100 text-sm md:text-base">
                    Onaylı satıcılardan elektronik, moda ve ev yaşam ürünlerinde kaçırılmayacak indirimler seni bekliyor.
                </p>
                <div class="pt-2">
                    <a href="#urunler" class="inline-block bg-white text-orange-600 hover:bg-orange-50 font-bold px-6 py-3 rounded-xl shadow-md transition-transform hover:-translate-y-0.5">
                        Alışverişe Başla <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- 5. Öne Çıkan Kategoriler -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Kategorileri Keşfet</h2>
                <a href="#" class="text-xs font-semibold text-orange-600 hover:text-orange-700">Tümünü Gör &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @foreach($categories as $cat)
                    <a href="/kategori/{{ $cat->slug }}" class="bg-white p-5 rounded-2xl border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all text-center group">
                        <div class="w-12 h-12 mx-auto rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-sm group-hover:text-orange-600 transition-colors">{{ $cat->name }}</h3>
                        <span class="text-xs text-gray-400 mt-1 block">{{ $cat->children->count() }} Alt Kategori</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 6. Öne Çıkan Ürünler Vitrini -->
        <section id="urunler" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Günün Fırsatları & Çok Satanlar</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Pazaryerimizin en popüler ve onaylı ürünleri</p>
                </div>
                <a href="#" class="text-xs font-semibold text-orange-600 hover:text-orange-700">Daha Fazla Ürün &rarr;</a>
            </div>

            <!-- Ürün Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $price = $firstVariant ? $firstVariant->price : 0;
                        $discountPrice = $firstVariant ? $firstVariant->discount_price : null;
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                        <!-- Görsel Alanı -->
                        <div class="relative bg-gray-50 aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <i class="fa-solid fa-box-open text-gray-300 text-5xl group-hover:scale-110 transition-transform duration-300"></i>
                            
                            <!-- İndirim Rozeti -->
                            @if($discountPrice)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider">
                                    İndirim
                                </span>
                            @endif

                            <!-- Favori Butonu -->
                            <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 hover:bg-white text-gray-400 hover:text-red-500 flex items-center justify-center transition-colors shadow-sm">
                                <i class="fa-regular fa-heart text-sm"></i>
                            </button>
                        </div>

                        <!-- Ürün Detayları -->
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <div class="space-y-1">
                                <!-- Satıcı Bilgisi -->
                                <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-medium">
                                    <i class="fa-solid fa-store text-orange-500"></i>
                                    <span>{{ $product->seller->store_name ?? 'PazarYeri Satıcısı' }}</span>
                                </div>
                                
                                <!-- Ürün Başlığı -->
                                <h3 class="font-semibold text-gray-800 text-sm line-clamp-2 group-hover:text-orange-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                            </div>

                            <!-- Fiyat ve Sepet Butonu -->
                            <div class="pt-2 border-t border-gray-50 flex items-center justify-between">
                                <div>
                                    @if($discountPrice)
                                        <span class="block text-xs text-gray-400 line-through">{{ number_format($price, 2, ',', '.') }} TL</span>
                                        <span class="text-base font-extrabold text-orange-600">{{ number_format($discountPrice, 2, ',', '.') }} TL</span>
                                    @else
                                        <span class="text-base font-extrabold text-gray-900">{{ number_format($price, 2, ',', '.') }} TL</span>
                                    @endif
                                </div>
                                <button class="bg-orange-50 hover:bg-orange-600 text-orange-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Ekle
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- 7. Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16 text-gray-600 text-sm">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold text-gray-900 mb-3">PazarYeri</h4>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">
                    Güvenli, hızlı ve binlerce satıcıyı tek çatı altında buluşturan yeni nesil e-ticaret platformu.
                </p>
                <div class="flex space-x-3 text-gray-400">
                    <a href="#" class="hover:text-orange-600"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-orange-600"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-orange-600"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-3">Kategoriler</h4>
                <ul class="space-y-2 text-xs">
                    @foreach($categories->take(5) as $cat)
                        <li><a href="/kategori/{{ $cat->slug }}" class="hover:text-orange-600">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-3">Müşteri Hizmetleri</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-orange-600">Sıkça Sorulan Sorular</a></li>
                    <li><a href="#" class="hover:text-orange-600">Kargo ve Teslimat</a></li>
                    <li><a href="#" class="hover:text-orange-600">İade ve Değişim</a></li>
                    <li><a href="#" class="hover:text-orange-600">Güvenli Alışveriş Rehberi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 mb-3">Satıcı Olun</h4>
                <p class="text-xs text-gray-500 mb-3">Milyonlarca müşteriye ulaşmak için hemen mağazanızı açın.</p>
                <a href="/satici" class="inline-block bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                    Satıcı Başvurusu Yap
                </a>
            </div>
        </div>
        <div class="border-t border-gray-100 py-6 text-center text-xs text-gray-400">
            &copy; 2026 PazarYeri E-Ticaret Platformu. Tüm hakları saklıdır.
        </div>
    </footer>

</body>
</html>
