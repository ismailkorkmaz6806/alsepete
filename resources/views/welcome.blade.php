<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AlSepete - {{ __('Güvenli ve Modern Çok Satıcılı Alışveriş') }}</title>
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
        [x-cloak] { display: none !important; }
        .subtle-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.07) 0px, transparent 50%),
                radial-gradient(at 90% 15%, rgba(14, 165, 233, 0.06) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(168, 85, 247, 0.05) 0px, transparent 50%);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="subtle-mesh text-slate-800 min-h-screen antialiased flex flex-col selection:bg-indigo-600 selection:text-white">

    <!-- Ortak Header, Mega Menü & Dil Seçici -->
    @include('layouts.navigation')

    <!-- 4. Ana İçerik -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-12 flex-1 w-full">

        <!-- Luminous Hero Banner -->
        <section class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 text-white p-8 md:p-14 shadow-xl shadow-indigo-500/10">
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-48 h-48 bg-sky-300/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-4">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">{{ __('Haftanın Öne Çıkan Fırsatları') }}</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-[1.15]">
                    {{ __('AlSepete ile Güvenli Alışveriş, Tek Sepette Binlerce Ürün.') }}
                </h1>

                <p class="text-indigo-100 text-sm md:text-base leading-relaxed max-w-xl">
                    {{ __('Farklı satıcılardan tek seferde alışveriş yapın, her satıcının bağımsız kargo takibini ve onaylı ürün kalitesini yaşayın.') }}
                </p>

                <div class="pt-3 flex flex-wrap gap-4 items-center">
                    <a href="#urunler" class="bg-white hover:bg-slate-50 text-indigo-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all hover:scale-105 text-sm flex items-center gap-2">
                        {{ __('Alışverişe Başla') }} <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ url('/satici') }}" class="bg-indigo-700/50 hover:bg-indigo-700 text-white border border-white/20 font-semibold px-6 py-3.5 rounded-2xl backdrop-blur-md transition-all text-sm">
                        {{ __('Satıcı Portali') }}
                    </a>
                </div>
            </div>
        </section>

        <!-- 5. Kategori Kartları -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Kategorileri Keşfet') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('Platformdaki 15 hiyerarşik kategori ağacı') }}</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ __('15 Kategori Aktif') }}</span>
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
                        <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ __($cat->name) }}</h3>
                        <span class="text-[11px] text-slate-400 mt-1 block font-medium">{{ $cat->children->count() }} {{ __('Alt Kategori') }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 6. Ürün Vitrini (E-Ticaret Katalog Çekimleri) -->
        <section id="urunler" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Günün Fırsatları & Çok Satanlar') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('Sizin için özenle seçilen en popüler ürünler') }}</p>
                </div>
                <a href="#urunler" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-full transition-colors inline-flex items-center gap-1.5">
                    {{ __('Tümünü Gör') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- E-Ticaret Ürün Fotoğrafları (Katalog Tarzı) -->
            @php
                $productImages = [
                    1 => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?auto=format&fit=crop&w=600&q=80', // 4K Smart TV
                    2 => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80', // Kablosuz Kulaklık
                    3 => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=600&q=80', // Gaming Laptop
                    4 => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80', // Akıllı Telefon
                    5 => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=600&q=80', // RGB Mekanik Oyuncu Klavyesi (Katalog Çekimi)
                    6 => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=600&q=80', // Erkek Düğmeli Pamuklu Gömlek
                    7 => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=600&q=80', // Düz Beyaz Kapüşonlu Sweatshirt (Katalog Ürün Çekimi)
                    8 => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80', // Kırmızı Koşu Ayakkabısı (Sneaker)
                ];
                $fallbackImages = [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&w=600&q=80',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $index => $product)
                    @php
                        $firstVariant = $product->variants->first();
                        $price = $firstVariant ? $firstVariant->price : 0;
                        $discountPrice = $firstVariant ? $firstVariant->discount_price : null;
                        $imageUrl = $productImages[$index + 1] ?? $fallbackImages[$index % count($fallbackImages)];
                    @endphp
                    <div class="bg-white border border-slate-200/80 hover:border-indigo-300 rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5 flex flex-col justify-between group">
                        
                        <!-- Katalog Ürün Fotoğrafı -->
                        <div class="relative bg-slate-50 aspect-square overflow-hidden flex items-center justify-center p-3">
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">

                            @if($discountPrice)
                                <span class="absolute top-3 left-3 bg-rose-600 text-white text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-md">
                                    {{ __('Fırsat') }}
                                </span>
                            @endif

                            <button class="absolute top-3 right-3 w-8 h-8 rounded-xl bg-white/90 hover:bg-white text-slate-500 hover:text-rose-500 flex items-center justify-center transition-colors shadow-sm">
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
                                    {{ __($product->category->name ?? 'Kategori') }}
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
                                <button class="bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 hover:scale-105 shadow-sm">
                                    <i class="fa-solid fa-plus text-[10px]"></i> {{ __('Ekle') }}
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <!-- Ortak Footer -->
    @include('layouts.footer')

</body>
</html>
