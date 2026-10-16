<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PazarYeri - Güvenli ve Modern Çok Satıcılı Alışveriş</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .subtle-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.07) 0px, transparent 50%),
                radial-gradient(at 90% 15%, rgba(14, 165, 233, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(168, 85, 247, 0.05) 0px, transparent 50%);
        }
    </style>
</head>
<body class="subtle-mesh text-slate-800 min-h-screen antialiased flex flex-col selection:bg-indigo-600 selection:text-white">

    <!-- 1. Üst İnce Bar (Hafif ve Ferah) -->
    <div class="border-b border-slate-200/80 bg-white/70 backdrop-blur-md text-xs py-2 px-4 text-slate-600">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <span class="flex items-center gap-1.5 font-medium text-slate-700">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Onaylı Mağazalar & Orijinal Ürünler
                </span>
                <span class="hidden sm:inline text-slate-500">
                    <i class="fa-solid fa-truck-fast text-indigo-500 mr-1"></i> Çoklu Satıcı Kargo Takibi
                </span>
            </div>
            <div class="flex items-center space-x-5 text-slate-600">
                <a href="/satici" class="hover:text-indigo-600 transition-colors flex items-center gap-1.5 font-semibold text-indigo-600">
                    <i class="fa-solid fa-store text-xs"></i> PazarYeri'nde Satıcı Ol
                </a>
                <span class="text-slate-300">|</span>
                <a href="#" class="hover:text-slate-900 transition-colors">Yardım</a>
            </div>
        </div>
    </div>

    <!-- 2. Ana Header (Ferah ve Canlı) -->
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-3.5 flex items-center justify-between gap-6">
            <!-- Özgün Logo (Indigo & Mor Gradyan) -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-600 flex items-center justify-center text-white text-xl font-black shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-cube text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900 flex items-center">
                        Pazar<span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Yeri</span>
                    </span>
                    <span class="text-[10px] uppercase tracking-widest font-bold text-slate-400 -mt-1">Çok Satıcılı Ekosistem</span>
                </div>
            </a>

            <!-- Modern Arama Kutusu -->
            <div class="flex-1 max-w-2xl relative">
                <div class="relative group">
                    <input type="text" placeholder="Ürün, marka, kategori veya satıcı ara..." 
                           class="w-full bg-slate-100/90 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-2.5 pl-11 pr-28 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
                    <button class="absolute right-1.5 top-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-semibold px-4 py-1.5 rounded-xl transition-all shadow-sm">
                        Ara
                    </button>
                </div>
            </div>

            <!-- Aksiyonlar -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <!-- Hesabım -->
                <a href="/hesabim" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-100 text-slate-700 hover:text-indigo-600 transition-colors group">
                    <div class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-indigo-50 text-slate-600 group-hover:text-indigo-600 flex items-center justify-center transition-colors">
                        <i class="fa-regular fa-user text-xs"></i>
                    </div>
                    <div class="hidden md:flex flex-col text-left">
                        <span class="text-[10px] text-slate-400 font-medium">Giriş Yap</span>
                        <span class="text-xs font-bold text-slate-800">Hesabım</span>
                    </div>
                </a>

                <!-- Favoriler -->
                <a href="#" class="relative p-2.5 rounded-xl hover:bg-slate-100 text-slate-600 hover:text-rose-500 transition-colors">
                    <i class="fa-regular fa-heart text-base"></i>
                    <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center shadow-sm">0</span>
                </a>

                <!-- Sepet -->
                <a href="/sepet" class="flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-colors">
                    <div class="relative">
                        <i class="fa-solid fa-bag-shopping text-sm text-indigo-600"></i>
                        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[9px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center">0</span>
                    </div>
                    <span class="hidden sm:inline">Sepetim</span>
                </a>
            </div>
        </div>

        <!-- 3. Kategori Navigasyonu -->
        <div class="border-t border-slate-100 bg-white/80">
            <div class="max-w-7xl mx-auto px-4 flex items-center space-x-8 overflow-x-auto text-xs font-semibold py-2.5 scrollbar-none text-slate-600">
                <span class="text-indigo-600 flex items-center gap-1.5 uppercase tracking-wider text-[11px] font-bold">
                    <i class="fa-solid fa-bars"></i> Tüm Kategoriler:
                </span>
                @foreach($categories as $category)
                    <a href="/kategori/{{ $category->slug }}" class="hover:text-indigo-600 transition-colors whitespace-nowrap py-0.5">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    <!-- 4. Ana İçerik -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-12 flex-1 w-full">

        <!-- Luminous Hero Banner (Canlı & Ferah Gradyan) -->
        <section class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 text-white p-8 md:p-14 shadow-xl shadow-indigo-500/10">
            <!-- Arka Plan Dekoratif Işıklar -->
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-48 h-48 bg-sky-300/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-4">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Haftanın Öne Çıkan Fırsatları</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-[1.15]">
                    Güvenli Pazar Yeri, <br>
                    <span class="text-sky-200">Tek Sepette Binlerce Ürün.</span>
                </h1>

                <p class="text-indigo-100 text-sm md:text-base leading-relaxed max-w-xl">
                    Farklı satıcılardan tek seferde alışveriş yapın, her satıcının bağımsız kargo takibini ve onaylı ürün kalitesini yaşayın.
                </p>

                <div class="pt-3 flex flex-wrap gap-4 items-center">
                    <a href="#urunler" class="bg-white hover:bg-slate-50 text-indigo-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all hover:scale-105 text-sm flex items-center gap-2">
                        Alışverişe Başla <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="/satici" class="bg-indigo-700/50 hover:bg-indigo-700 text-white border border-white/20 font-semibold px-6 py-3.5 rounded-2xl backdrop-blur-md transition-all text-sm">
                        Satıcı Portali
                    </a>
                </div>
            </div>
        </section>

        <!-- 5. Kategori Kartları (Ferah Beyaz Kartlar & Pastel İkonlar) -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Kategorileri Keşfet</h2>
                    <p class="text-xs text-slate-500">Platformdaki 15 hiyerarşik kategori ağacı</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">15 Kategori Aktif</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @php
                    $colors = [
                        ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'hover:border-indigo-300'],
                        ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'hover:border-rose-300'],
                        ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'hover:border-emerald-300'],
                        ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'hover:border-amber-300'],
                        ['bg' => 'bg-sky-50', 'text' => 'text-sky-600', 'border' => 'hover:border-sky-300'],
                    ];
                @endphp
                @foreach($categories as $idx => $cat)
                    @php $c = $colors[$idx % count($colors)]; @endphp
                    <a href="/kategori/{{ $cat->slug }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 {{ $c['border'] }} shadow-sm hover:shadow-md transition-all duration-300 text-center group hover:-translate-y-1">
                        <div class="w-12 h-12 mx-auto rounded-2xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $cat->name }}</h3>
                        <span class="text-[11px] text-slate-400 mt-1 block font-medium">{{ $cat->children->count() }} Alt Kategori</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 6. Ürün Vitrini (Açık, Net, Canlı) -->
        <section id="urunler" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Günün Fırsatları & Çok Satanlar</h2>
                    <p class="text-xs text-slate-500">Veritabanından çekilen 100 üründen seçilenler</p>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">
                    Ondalık Fiyat Standardı (decimal 10,2)
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $price = $firstVariant ? $firstVariant->price : 0;
                        $discountPrice = $firstVariant ? $firstVariant->discount_price : null;
                    @endphp
                    <div class="bg-white border border-slate-200/80 hover:border-indigo-300 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5 flex flex-col justify-between group">
                        
                        <!-- Görsel Alanı -->
                        <div class="relative bg-slate-50 aspect-square flex items-center justify-center p-6 border-b border-slate-100 overflow-hidden">
                            <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200/80 flex items-center justify-center text-slate-400 group-hover:scale-110 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-all duration-300 shadow-sm">
                                <i class="fa-solid fa-box-open text-3xl"></i>
                            </div>

                            @if($discountPrice)
                                <span class="absolute top-3 left-3 bg-rose-500 text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-sm">
                                    İndirim
                                </span>
                            @endif

                            <button class="absolute top-3 right-3 w-8 h-8 rounded-xl bg-white/90 hover:bg-rose-50 text-slate-400 hover:text-rose-500 border border-slate-200/80 flex items-center justify-center transition-colors shadow-sm">
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </div>

                        <!-- Ürün Detayları -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-1.5 text-[11px] text-indigo-600 font-semibold">
                                    <i class="fa-solid fa-store text-[10px]"></i>
                                    <span>{{ $product->seller->store_name ?? 'Onaylı Satıcı' }}</span>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                <span class="text-[11px] text-slate-400 block font-medium">
                                    {{ $product->category->name ?? 'Kategori' }}
                                </span>
                            </div>

                            <!-- Fiyat ve Sepet Butonu -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    @if($discountPrice)
                                        <span class="block text-[11px] text-slate-400 line-through font-medium">{{ number_format($price, 2, ',', '.') }} TL</span>
                                        <span class="text-lg font-black text-slate-900 tracking-tight">{{ number_format($discountPrice, 2, ',', '.') }} <span class="text-xs font-semibold text-indigo-600">TL</span></span>
                                    @else
                                        <span class="text-lg font-black text-slate-900 tracking-tight">{{ number_format($price, 2, ',', '.') }} <span class="text-xs font-semibold text-indigo-600">TL</span></span>
                                    @endif
                                </div>
                                <button class="bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Ekle
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- 7. Footer (Açık, Temiz ve Kurumsal) -->
    <footer class="border-t border-slate-200/80 bg-white mt-20 text-slate-500 text-xs">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <span class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    PazarYeri
                </span>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Amazon ve Hepsiburada mimarisinde çok satıcılı, güvenli alışveriş platformu.
                </p>
                <div class="flex space-x-3 text-slate-400 pt-1">
                    <a href="#" class="hover:text-indigo-600"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-indigo-600"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-indigo-600"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-3 text-sm">Hızlı Bağlantılar</h4>
                <ul class="space-y-2">
                    <li><a href="/admin" class="hover:text-indigo-600 transition-colors">Yönetici Paneli (/admin)</a></li>
                    <li><a href="/satici" class="hover:text-indigo-600 transition-colors">Satıcı Paneli (/satici)</a></li>
                    <li><a href="/hesabim" class="hover:text-indigo-600 transition-colors">Müşteri Hesabı (/hesabim)</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-3 text-sm">Mimari Standartlar</h4>
                <ul class="space-y-2">
                    <li><span class="text-slate-600">PHP 8.3 + Laravel 13</span></li>
                    <li><span class="text-slate-600">Ondalık Fiyat (decimal 10,2)</span></li>
                    <li><span class="text-slate-600">Alt Sipariş (sub_orders)</span></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-3 text-sm">Satıcı Ekosistemi</h4>
                <p class="mb-3 text-slate-500 leading-relaxed">Mağazanızı açın, ürünlerinizi listeleyin ve satışa başlayın.</p>
                <a href="/satici" class="inline-block bg-slate-900 hover:bg-indigo-600 text-white font-bold px-4 py-2 rounded-xl transition-colors">
                    Satıcı Başvurusu Yap
                </a>
            </div>
        </div>
        <div class="border-t border-slate-100 py-6 text-center text-slate-400">
            &copy; 2026 PazarYeri Çok Satıcılı E-Ticaret Platformu. Tüm hakları saklıdır.
        </div>
    </footer>

</body>
</html>
