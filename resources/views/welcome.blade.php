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
    @livewireStyles
</head>
<body class="subtle-mesh text-slate-800 min-h-screen antialiased flex flex-col selection:bg-indigo-600 selection:text-white">

    <!-- Ortak Header, Mega Menü & Dil Seçici -->
    @include('layouts.navigation')

    <!-- Ana İçerik -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-12 flex-1 w-full">

        <!-- 1. Hafta 3: Dinamik Banner Slider (Alpine.js) -->
        <section x-data="{
                    activeSlide: 0,
                    slidesCount: 3,
                    timer: null,
                    startTimer() {
                        this.timer = setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                        }, 5000);
                    },
                    stopTimer() {
                        clearInterval(this.timer);
                    }
                 }" 
                 x-init="startTimer()"
                 @mouseenter="stopTimer()"
                 @mouseleave="startTimer()"
                 class="relative rounded-3xl overflow-hidden shadow-xl shadow-indigo-500/10 min-h-[300px] sm:min-h-[380px] bg-slate-900">
            
            <!-- Slayt 1: Genel Kampanya -->
            <div x-show="activeSlide === 0" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full h-full min-h-[300px] sm:min-h-[380px] bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 text-white p-8 sm:p-12 flex items-center justify-between gap-8">
                <div class="relative z-10 max-w-xl space-y-4">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-bold text-white uppercase tracking-wider">{{ __('Haftanın Öne Çıkan Fırsatları') }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-[1.15]">
                        {{ __('AlSepete ile Güvenli Alışveriş, Tek Sepette Binlerce Ürün.') }}
                    </h1>
                    <p class="text-indigo-100 text-sm sm:text-base leading-relaxed max-w-xl">
                        {{ __('Farklı satıcılardan tek seferde alışveriş yapın, bağımsız kargo takibini ve onaylı satıcı kalitesini yaşayın.') }}
                    </p>
                    <div class="pt-2 flex flex-wrap gap-3">
                        <a href="#cok-satanlar" class="bg-white hover:bg-slate-50 text-indigo-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all hover:scale-105 text-xs sm:text-sm flex items-center gap-2">
                            {{ __('Fırsatları Keşfet') }} <i class="fa-solid fa-arrow-down text-xs"></i>
                        </a>
                        <a href="{{ route('categories.show', 'elektronik') }}" class="bg-indigo-900/40 hover:bg-indigo-900/60 text-white border border-white/20 font-bold px-6 py-3.5 rounded-2xl backdrop-blur-md transition-all text-xs sm:text-sm">
                            {{ __('Elektronik Vitrini') }}
                        </a>
                    </div>
                </div>

                <!-- Slayt 1 Görseli: Teknoloji & Cihazlar -->
                <div class="hidden lg:flex items-center justify-center flex-1 relative pr-4">
                    <div class="relative w-72 h-72 xl:w-80 xl:h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=80" alt="Elektronik Vitrini" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-slate-900 text-xs font-black shadow-lg flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-laptop text-indigo-600"></i> {{ __('Akıllı Teknoloji') }}</span>
                            <span class="text-indigo-600 font-extrabold bg-indigo-50 px-2 py-0.5 rounded-full">%30 {{ __('İndirim') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slayt 2: Moda & Trendler -->
            <div x-show="activeSlide === 1" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full h-full min-h-[300px] sm:min-h-[380px] bg-gradient-to-r from-rose-600 via-pink-600 to-amber-500 text-white p-8 sm:p-12 flex items-center justify-between gap-8">
                <div class="relative z-10 max-w-xl space-y-4">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20">
                        <i class="fa-solid fa-fire text-amber-300 text-xs"></i>
                        <span class="text-xs font-bold text-white uppercase tracking-wider">{{ __('Yeni Sezon Trendleri') }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-[1.15]">
                        {{ __('Sezonun En Şık Tasarımları Şimdi AlSepete\'de!') }}
                    </h2>
                    <p class="text-pink-100 text-sm sm:text-base leading-relaxed max-w-xl">
                        {{ __('Seçkin tekstil üreticilerinden doğrudan gardırobunuza; özel indirimler ve aynı gün kargo imkanı.') }}
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('categories.show', 'moda-giyim') }}" class="bg-white hover:bg-slate-50 text-rose-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all hover:scale-105 text-xs sm:text-sm inline-flex items-center gap-2">
                            {{ __('Moda Ürünlerini İncele') }} <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Slayt 2 Görseli: Moda & Yaşam Tarzı -->
                <div class="hidden lg:flex items-center justify-center flex-1 relative pr-4">
                    <div class="relative w-72 h-72 xl:w-80 xl:h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 transform -rotate-1 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&auto=format&fit=crop&q=80" alt="Moda Vitrini" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-slate-900 text-xs font-black shadow-lg flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-rose-600"></i> {{ __('Yeni Sezon') }}</span>
                            <span class="text-rose-600 font-extrabold bg-rose-50 px-2 py-0.5 rounded-full">%40 {{ __('İndirim') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slayt 3: Ev & Yaşam / Mutfak -->
            <div x-show="activeSlide === 2" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full h-full min-h-[300px] sm:min-h-[380px] bg-gradient-to-r from-sky-600 via-blue-600 to-indigo-700 text-white p-8 sm:p-12 flex items-center justify-between gap-8">
                <div class="relative z-10 max-w-xl space-y-4">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/20">
                        <i class="fa-solid fa-shield-halved text-emerald-300 text-xs"></i>
                        <span class="text-xs font-bold text-white uppercase tracking-wider">{{ __('Onaylı Satıcı Ekosistemi') }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-[1.15]">
                        {{ __('Eviniz ve Çalışma Alanınız İçin İhtiyacınız Olan Her Şey.') }}
                    </h2>
                    <p class="text-sky-100 text-sm sm:text-base leading-relaxed max-w-xl">
                        {{ __('Ergonomik koltuklar, akıllı aksesuarlar ve mutfak gereçleri özel fiyatlarla sizleri bekliyor.') }}
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('categories.show', 'ev-yasam') }}" class="bg-white hover:bg-slate-50 text-sky-700 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition-all hover:scale-105 text-xs sm:text-sm inline-flex items-center gap-2">
                            {{ __('Koleksiyonu Keşfet') }} <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Slayt 3 Görseli: Ev & Mutfak -->
                <div class="hidden lg:flex items-center justify-center flex-1 relative pr-4">
                    <div class="relative w-72 h-72 xl:w-80 xl:h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&auto=format&fit=crop&q=80" alt="Ev ve Yaşam" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-2xl text-slate-900 text-xs font-black shadow-lg flex items-center justify-between">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-couch text-sky-600"></i> {{ __('Ev & Yaşam') }}</span>
                            <span class="text-sky-600 font-extrabold bg-sky-50 px-2 py-0.5 rounded-full">{{ __('Fırsat') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Okları (Next & Prev) -->
            <button @click="activeSlide = (activeSlide - 1 + slidesCount) % slidesCount" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-md text-white flex items-center justify-center transition-all focus:outline-none">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
            <button @click="activeSlide = (activeSlide + 1) % slidesCount" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-md text-white flex items-center justify-center transition-all focus:outline-none">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>

            <!-- Gösterge Noktaları (Dots) -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                <template x-for="i in slidesCount" :key="i">
                    <button @click="activeSlide = i - 1" 
                            class="h-2 rounded-full transition-all focus:outline-none"
                            :class="activeSlide === (i - 1) ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'">
                    </button>
                </template>
            </div>
        </section>

        <!-- 2. Hafta 3: Öne Çıkan Kategoriler -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ __('Öne Çıkan Kategoriler') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('İhtiyacınız olan ürünleri kategori bazında filtreleyin') }}</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ count($categories) }} {{ __('Kategori') }}</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @php
                    $categoryVisuals = [
                        'elektronik' => [
                            'img' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-laptop',
                            'badge' => 'bg-indigo-600',
                            'border' => 'hover:border-indigo-400',
                        ],
                        'moda-giyim' => [
                            'img' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-shirt',
                            'badge' => 'bg-rose-600',
                            'border' => 'hover:border-rose-400',
                        ],
                        'ev-yasam' => [
                            'img' => 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-couch',
                            'badge' => 'bg-emerald-600',
                            'border' => 'hover:border-emerald-400',
                        ],
                        'spor-outdoor' => [
                            'img' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-dumbbell',
                            'badge' => 'bg-amber-600',
                            'border' => 'hover:border-amber-400',
                        ],
                        'kozmetik-kisisel-bakim' => [
                            'img' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-sparkles',
                            'badge' => 'bg-fuchsia-600',
                            'border' => 'hover:border-fuchsia-400',
                        ],
                    ];
                @endphp
                @foreach($categories as $idx => $cat)
                    @php
                        $visual = $categoryVisuals[$cat->slug] ?? [
                            'img' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80',
                            'icon' => 'fa-tag',
                            'badge' => 'bg-slate-700',
                            'border' => 'hover:border-slate-400',
                        ];
                    @endphp
                    <a href="{{ route('categories.show', $cat->slug) }}" 
                       class="group bg-white rounded-2xl border border-slate-200/80 {{ $visual['border'] }} p-3 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between hover:-translate-y-1 overflow-hidden">
                        
                        <!-- Kategori Görseli -->
                        <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-slate-100 mb-3">
                            <img src="{{ $visual['img'] }}" 
                                 alt="{{ $cat->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            
                            <!-- İkon Rozeti -->
                            <div class="absolute top-2 right-2 w-7 h-7 rounded-lg {{ $visual['badge'] }} text-white flex items-center justify-center text-xs shadow-md">
                                <i class="fa-solid {{ $visual['icon'] }}"></i>
                            </div>

                            <!-- Alt Kategori Sayısı (Görsel İçi Rozet) -->
                            <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur-xs px-2 py-0.5 rounded-md text-[10px] font-black text-slate-800 shadow-xs">
                                {{ $cat->children->count() }} {{ __('Alt Kategori') }}
                            </div>
                        </div>

                        <!-- Başlık & Keşfet Linki -->
                        <div class="text-center px-1 pb-1">
                            <h3 class="font-extrabold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ __($cat->name) }}
                            </h3>
                            <span class="text-[11px] font-semibold text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1 mt-0.5">
                                <span>{{ __('Keşfet') }}</span>
                                <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- 3. Hafta 3: "Çok Satanlar" Vitrini (SEO URL: /urun/{slug}) -->
        <section id="cok-satanlar" class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 uppercase">
                            <i class="fa-solid fa-fire text-rose-600 me-1"></i> {{ __('Trend Ürünler') }}
                        </span>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ __('Çok Satanlar') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('AlSepete müşterilerinin en çok tercih ettiği popüler ürünler') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($bestsellers as $product)
                    @php
                        $primaryVariant = $product->variants->first();
                        $productImage = $product->featured_image;
                    @endphp
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="group bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-xl hover:border-indigo-300 transition-all flex flex-col justify-between">
                        
                        <div>
                            <!-- Ürün Görseli -->
                            <div class="relative aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3">
                                <img src="{{ $productImage }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                                
                                @if($primaryVariant && $primaryVariant->discount_price)
                                    <span class="absolute top-2 left-2 bg-rose-500 text-white font-extrabold text-[10px] px-2 py-0.5 rounded-full shadow-xs">
                                        %{{ round((($primaryVariant->price - $primaryVariant->discount_price) / $primaryVariant->price) * 100) }}
                                    </span>
                                @endif

                                @if($product->brand)
                                    <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-xs text-slate-700 font-bold text-[9px] px-2 py-0.5 rounded-md border border-slate-200/60">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Kategori & Başlık -->
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">
                                {{ __($product->category->name) }}
                            </span>
                            <h3 class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-relaxed">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-3 space-y-2">
                            <!-- Satıcı & Puan -->
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span class="truncate max-w-[120px] font-medium">{{ $product->seller->store_name }}</span>
                                <div class="flex items-center gap-1 text-amber-500 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    <span class="text-slate-700">4.9</span>
                                </div>
                            </div>

                            <!-- Fiyat -->
                            <div class="flex items-baseline gap-2">
                                @if($primaryVariant && $primaryVariant->discount_price)
                                    <span class="text-base font-black text-indigo-600">
                                        {{ number_format($primaryVariant->discount_price, 2, ',', '.') }} TL
                                    </span>
                                    <span class="text-xs text-slate-400 line-through">
                                        {{ number_format($primaryVariant->price, 2, ',', '.') }} TL
                                    </span>
                                @elseif($primaryVariant)
                                    <span class="text-base font-black text-slate-900">
                                        {{ number_format($primaryVariant->price, 2, ',', '.') }} TL
                                    </span>
                                @endif
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        </section>

        <!-- 4. Hafta 3: "Yeni Gelenler" Vitrini -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800 uppercase">
                            <i class="fa-solid fa-sparkles text-sky-600 me-1"></i> {{ __('Katalog Güncellemesi') }}
                        </span>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ __('Yeni Gelenler') }}</h2>
                    <p class="text-xs text-slate-500">{{ __('Satıcılarımızın kataloğa yeni eklediği güncel ürünler') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    @php
                        $primaryVariant = $product->variants->first();
                        $productImage = $product->featured_image;
                    @endphp
                    <a href="{{ route('products.show', $product->slug) }}" 
                       class="group bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-xl hover:border-indigo-300 transition-all flex flex-col justify-between">
                        
                        <div>
                            <!-- Ürün Görseli -->
                            <div class="relative aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3">
                                <img src="{{ $productImage }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                                
                                <span class="absolute top-2 left-2 bg-indigo-600 text-white font-extrabold text-[10px] px-2 py-0.5 rounded-full shadow-xs">
                                    {{ __('YENİ') }}
                                </span>
                            </div>

                            <!-- Kategori & Başlık -->
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-1">
                                {{ __($product->category->name) }}
                            </span>
                            <h3 class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-relaxed">
                                {{ $product->name }}
                            </h3>
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-3 space-y-2">
                            <!-- Satıcı & Puan -->
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span class="truncate max-w-[120px] font-medium">{{ $product->seller->store_name }}</span>
                                <div class="flex items-center gap-1 text-amber-500 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    <span class="text-slate-700">5.0</span>
                                </div>
                            </div>

                            <!-- Fiyat -->
                            <div class="flex items-baseline gap-2">
                                @if($primaryVariant && $primaryVariant->discount_price)
                                    <span class="text-base font-black text-indigo-600">
                                        {{ number_format($primaryVariant->discount_price, 2, ',', '.') }} TL
                                    </span>
                                    <span class="text-xs text-slate-400 line-through">
                                        {{ number_format($primaryVariant->price, 2, ',', '.') }} TL
                                    </span>
                                @elseif($primaryVariant)
                                    <span class="text-base font-black text-slate-900">
                                        {{ number_format($primaryVariant->price, 2, ',', '.') }} TL
                                    </span>
                                @endif
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        </section>

        <!-- 5. Güvenlik ve Alışveriş Garantileri -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-6">
            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ __('Hızlı & Güvenli Teslimat') }}</h4>
                    <p class="text-xs text-slate-500">{{ __('Satıcı bazında ayrıştırılmış kargo takibi') }}</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ __('Onaylı Satıcı Mağazaları') }}</h4>
                    <p class="text-xs text-slate-500">{{ __('Vergi ve kimlik kontrolünden geçmiş satıcılar') }}</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ __('14 Gün Kolay İade') }}</h4>
                    <p class="text-xs text-slate-500">{{ __('Koşulsuz şartsız iade ve değişim güvencesi') }}</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ __('256-Bit SSL Ödeme') }}</h4>
                    <p class="text-xs text-slate-500">{{ __('Tüm ödemeleriniz 3D Secure ile korunur') }}</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Ortak Footer -->
    @include('layouts.footer')

    @livewireScripts
</body>
</html>
