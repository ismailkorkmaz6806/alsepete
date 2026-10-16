<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PazarYeri - Yeni Nesil Çok Satıcılı Alışveriş</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mesh-gradient {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(14, 165, 233, 0.2) 0px, transparent 50%);
        }
        .hero-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glow-indigo {
            box-shadow: 0 0 40px -10px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-indigo-500 selection:text-white">

    <!-- 1. Üst Atmosferik Ambient Glow Efektleri -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-40 left-1/4 w-96 h-96 bg-indigo-600/15 rounded-full blur-3xl"></div>
        <div class="absolute top-60 right-10 w-[30rem] h-[30rem] bg-violet-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-10 w-80 h-80 bg-cyan-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">

        <!-- 2. Üst İnce Bilgi Barı -->
        <div class="border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md text-xs py-2 px-4 text-slate-400">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <span class="flex items-center gap-1.5 text-slate-300">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Onaylı Satıcı Ekosistemi
                    </span>
                    <span class="hidden sm:inline text-slate-400">
                        <i class="fa-solid fa-bolt text-indigo-400 mr-1"></i> Aynı Gün Kargoda
                    </span>
                </div>
                <div class="flex items-center space-x-5 text-slate-300">
                    <a href="/satici" class="hover:text-indigo-400 transition-colors flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-shop text-xs text-indigo-400"></i> Mağaza Aç & Satıcı Ol
                    </a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="hover:text-white transition-colors">Yardım Merkezi</a>
                </div>
            </div>
        </div>

        <!-- 3. Ana Gezinme Çubuğu (Navbar) -->
        <header class="sticky top-0 z-50 border-b border-slate-800/80 bg-slate-900/90 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 py-3.5 flex items-center justify-between gap-6">
                <!-- Özel Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-500 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-cube text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black tracking-tight text-white flex items-center">
                            Pazar<span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">Yeri</span>
                        </span>
                        <span class="text-[9px] uppercase tracking-widest font-bold text-slate-400 -mt-1">Çok Satıcılı Pazar</span>
                    </div>
                </a>

                <!-- Fütüristik Arama Barı -->
                <div class="flex-1 max-w-2xl relative">
                    <div class="relative group">
                        <input type="text" placeholder="Ürün adı, marka, kategori veya satıcı ara..." 
                               class="w-full bg-slate-800/80 border border-slate-700/80 focus:border-indigo-500 focus:bg-slate-800 focus:outline-none rounded-2xl py-3 pl-12 pr-28 text-sm text-slate-100 placeholder-slate-400 transition-all duration-300 shadow-inner">
                        <i class="fa-solid fa-magnifying-glass absolute left-4.5 top-3.5 text-slate-400 text-sm group-focus-within:text-indigo-400 transition-colors"></i>
                        <button class="absolute right-2 top-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all shadow-md shadow-indigo-600/30">
                            Keşfet
                        </button>
                    </div>
                </div>

                <!-- Aksiyonlar (Hesabım, Favoriler, Sepet) -->
                <div class="flex items-center space-x-4">
                    <!-- Giriş / Profil -->
                    <a href="/hesabim" class="flex items-center gap-3 px-3.5 py-2 rounded-xl border border-slate-800 hover:border-slate-700 bg-slate-900/60 hover:bg-slate-800/80 transition-all text-slate-200 group">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                            <i class="fa-regular fa-user text-xs"></i>
                        </div>
                        <div class="hidden lg:flex flex-col text-left">
                            <span class="text-[10px] text-slate-400 font-medium">Hoş Geldiniz</span>
                            <span class="text-xs font-bold text-white">Hesabım</span>
                        </div>
                    </a>

                    <!-- Favoriler -->
                    <a href="#" class="relative p-2.5 rounded-xl border border-slate-800 hover:border-slate-700 bg-slate-900/60 hover:bg-slate-800/80 text-slate-300 hover:text-rose-400 transition-all">
                        <i class="fa-regular fa-heart text-base"></i>
                        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </a>

                    <!-- Sepet -->
                    <a href="/sepet" class="flex items-center gap-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white px-4 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                        <div class="relative">
                            <i class="fa-solid fa-basket-shopping text-sm"></i>
                            <span class="absolute -top-2 -right-2 bg-white text-indigo-600 text-[9px] font-black rounded-full w-3.5 h-3.5 flex items-center justify-center">0</span>
                        </div>
                        <span class="hidden sm:inline">Sepetim</span>
                    </a>
                </div>
            </div>

            <!-- Kategori Çubuğu -->
            <div class="border-t border-slate-800/60 bg-slate-900/40">
                <div class="max-w-7xl mx-auto px-4 flex items-center space-x-7 overflow-x-auto text-xs font-semibold py-2.5 scrollbar-none">
                    <span class="text-indigo-400 flex items-center gap-1.5 uppercase tracking-wider text-[11px] font-bold">
                        <i class="fa-solid fa-compass"></i> Kategoriler:
                    </span>
                    @foreach($categories as $category)
                        <a href="/kategori/{{ $category->slug }}" class="text-slate-300 hover:text-indigo-400 transition-colors whitespace-nowrap py-0.5 px-1.5 rounded-md hover:bg-slate-800/50">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <!-- 4. İçerik Alanı -->
        <main class="max-w-7xl mx-auto px-4 py-8 space-y-12 flex-1 w-full">

            <!-- Yaratıcı Hero Banner -->
            <section class="relative rounded-3xl overflow-hidden border border-slate-800 mesh-gradient p-8 md:p-14 hero-pattern glow-indigo">
                <div class="max-w-2xl space-y-5">
                    <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/30 px-3.5 py-1.5 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                        <span class="text-xs font-bold text-indigo-300 uppercase tracking-widest">Premium Pazar Yeri Deneyimi</span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
                        Doğrulanmış Satıcılar, <br>
                        <span class="bg-gradient-to-r from-indigo-400 via-violet-300 to-cyan-300 bg-clip-text text-transparent">
                            Sınırsız Seçenek.
                        </span>
                    </h1>

                    <p class="text-slate-300 text-sm md:text-base leading-relaxed max-w-xl">
                        Amazon ve Hepsiburada pazaryeri standardında; çoklu satıcı sepeti, bağımsız kargo takibi ve güvenli altyapı ile alışverişinizi başlatın.
                    </p>

                    <div class="pt-3 flex flex-wrap gap-4 items-center">
                        <a href="#urunler" class="bg-white hover:bg-slate-100 text-slate-950 font-extrabold px-6 py-3.5 rounded-2xl shadow-xl transition-all hover:scale-[1.02] flex items-center gap-2 text-sm">
                            Ürünleri İncele <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <a href="/satici" class="border border-slate-700 hover:border-slate-500 bg-slate-900/50 hover:bg-slate-900 text-slate-200 font-semibold px-6 py-3.5 rounded-2xl transition-all text-sm">
                            Satıcı Başvurusu
                        </a>
                    </div>
                </div>

                <!-- Arka Planda Fütüristik İkon Rozetleri -->
                <div class="hidden lg:block absolute right-14 top-1/2 -translate-y-1/2 space-y-4">
                    <div class="bg-slate-900/90 border border-slate-700/80 p-4 rounded-2xl backdrop-blur-xl shadow-2xl flex items-center gap-4 w-64 transform translate-x-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-white block">100+ Gerçek Ürün</span>
                            <span class="text-[10px] text-slate-400">Veritabanında Aktif</span>
                        </div>
                    </div>
                    <div class="bg-slate-900/90 border border-slate-700/80 p-4 rounded-2xl backdrop-blur-xl shadow-2xl flex items-center gap-4 w-64 transform -translate-x-2">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl font-bold">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-white block">5 Onaylı Satıcı</span>
                            <span class="text-[10px] text-slate-400">Lisanslı & Güvenilir</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. Kategoriler Kartları -->
            <section class="space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div>
                        <h2 class="text-lg md:text-xl font-extrabold text-white tracking-tight">Kategori Ağacı</h2>
                        <p class="text-xs text-slate-400">Platformdaki 15 hiyerarşik ana ve alt kategori</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-400">15 Kategori Hazır</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($categories as $cat)
                        <a href="/kategori/{{ $cat->slug }}" class="bg-slate-900/60 hover:bg-slate-900 border border-slate-800/80 hover:border-indigo-500/50 p-5 rounded-2xl transition-all duration-300 text-center group hover:-translate-y-1 shadow-sm">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-folder-tree"></i>
                            </div>
                            <h3 class="font-bold text-slate-100 text-sm group-hover:text-indigo-300 transition-colors">{{ $cat->name }}</h3>
                            <span class="text-[11px] text-slate-400 mt-1 block font-medium">{{ $cat->children->count() }} Alt Kategori</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <!-- 6. Ürün Vitrini (Günün Fırsatları) -->
            <section id="urunler" class="space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div>
                        <h2 class="text-lg md:text-xl font-extrabold text-white tracking-tight">Öne Çıkan Ürün Kataloğu</h2>
                        <p class="text-xs text-slate-400">Veritabanından canlı çekilen 100 üründen seçilenler</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-400">Stok & Varyant Destekli</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $price = $firstVariant ? $firstVariant->price : 0;
                            $discountPrice = $firstVariant ? $firstVariant->discount_price : null;
                        @endphp
                        <div class="bg-slate-900/70 border border-slate-800/90 hover:border-indigo-500/50 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10 flex flex-col justify-between group">
                            
                            <!-- Görsel Alanı -->
                            <div class="relative bg-slate-950/60 aspect-square flex items-center justify-center p-6 border-b border-slate-800/60 overflow-hidden">
                                <div class="w-20 h-20 rounded-2xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center text-slate-500 group-hover:scale-110 group-hover:text-indigo-400 group-hover:border-indigo-500/30 transition-all duration-300">
                                    <i class="fa-solid fa-cube text-3xl"></i>
                                </div>

                                @if($discountPrice)
                                    <span class="absolute top-3 left-3 bg-rose-500/90 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">
                                        Fırsat
                                    </span>
                                @endif

                                <button class="absolute top-3 right-3 w-8 h-8 rounded-xl bg-slate-900/80 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-slate-800 flex items-center justify-center transition-colors">
                                    <i class="fa-regular fa-heart text-xs"></i>
                                </button>
                            </div>

                            <!-- Bilgi Alanı -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-1.5 text-[11px] text-indigo-400 font-semibold">
                                        <i class="fa-solid fa-store text-[10px]"></i>
                                        <span>{{ $product->seller->store_name ?? 'Onaylı Satıcı' }}</span>
                                    </div>
                                    <h3 class="font-bold text-slate-100 text-sm leading-snug line-clamp-2 group-hover:text-indigo-300 transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <span class="text-[10px] text-slate-400 block">
                                        {{ $product->category->name ?? 'Kategori' }}
                                    </span>
                                </div>

                                <!-- Fiyat ve Buton -->
                                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between">
                                    <div>
                                        @if($discountPrice)
                                            <span class="block text-[11px] text-slate-400 line-through">{{ number_format($price, 2, ',', '.') }} TL</span>
                                            <span class="text-lg font-black text-white tracking-tight">{{ number_format($discountPrice, 2, ',', '.') }} <span class="text-xs font-semibold text-indigo-400">TL</span></span>
                                        @else
                                            <span class="text-lg font-black text-white tracking-tight">{{ number_format($price, 2, ',', '.') }} <span class="text-xs font-semibold text-indigo-400">TL</span></span>
                                        @endif
                                    </div>
                                    <button class="bg-indigo-600 hover:bg-indigo-500 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-1.5 hover:scale-105">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Ekle
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </section>
        </main>

        <!-- 7. Alt Bilgi (Footer) -->
        <footer class="border-t border-slate-800/80 bg-slate-900/60 mt-20 text-slate-400 text-xs">
            <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="space-y-3">
                    <span class="text-base font-black text-white flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-xs">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        PazarYeri
                    </span>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Çok satıcılı pazaryeri altyapısı, onaylı mağazalar ve güvenli ödeme sistemi.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm">Platform</h4>
                    <ul class="space-y-2">
                        <li><a href="/admin" class="hover:text-indigo-400 transition-colors">Yönetici Paneli (/admin)</a></li>
                        <li><a href="/satici" class="hover:text-indigo-400 transition-colors">Satıcı Paneli (/satici)</a></li>
                        <li><a href="/hesabim" class="hover:text-indigo-400 transition-colors">Müşteri Hesabı (/hesabim)</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm">Mimari Standartlar</h4>
                    <ul class="space-y-2">
                        <li><span>PHP 8.3 + Laravel 13</span></li>
                        <li><span>decimal(10,2) Fiyat Standardı</span></li>
                        <li><span>Sub-Orders Pazaryeri Mantığı</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-sm">Satıcı Ağı</h4>
                    <p class="mb-3 text-slate-400 leading-relaxed">Kendi mağazanızı açarak binlerce müşteriye hemen ulaşın.</p>
                    <a href="/satici" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2 rounded-xl transition-colors">
                        Satıcı Başvurusu
                    </a>
                </div>
            </div>
            <div class="border-t border-slate-800 py-6 text-center text-slate-400">
                &copy; 2026 PazarYeri Çok Satıcılı E-Ticaret Platformu.
            </div>
        </footer>

    </div>

</body>
</html>
