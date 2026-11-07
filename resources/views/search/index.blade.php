<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">{{ __('Ana Sayfa') }}</a>
                    <span>/</span>
                    <span class="text-slate-600 font-semibold">{{ __('Arama Sonuçları') }}</span>
                </nav>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-indigo-600"></i>
                    @if($q)
                        <span>"{{ $q }}" {{ __('için arama sonuçları') }}</span>
                    @else
                        <span>{{ __('Tüm Ürünler') }}</span>
                    @endif
                </h1>
            </div>

            <!-- Sağ Taraf: Sıralama Dropdown'ı -->
            <form action="{{ route('search') }}" method="GET" class="flex items-center gap-2">
                @if($q) <input type="hidden" name="q" value="{{ $q }}"> @endif
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('marka') || request('brand')) <input type="hidden" name="marka" value="{{ request('marka') ?: request('brand') }}"> @endif
                @if(request('min') || request('min_price')) <input type="hidden" name="min" value="{{ request('min') ?: request('min_price') }}"> @endif
                @if(request('max') || request('max_price')) <input type="hidden" name="max" value="{{ request('max') ?: request('max_price') }}"> @endif
                @if(request('renk') || request('color')) <input type="hidden" name="renk" value="{{ request('renk') ?: request('color') }}"> @endif
                @if(request('beden') || request('size')) <input type="hidden" name="beden" value="{{ request('beden') ?: request('size') }}"> @endif
                @if(request('stok') || request('in_stock')) <input type="hidden" name="stok" value="1"> @endif

                <label for="sort" class="text-xs font-bold text-slate-600 hidden sm:inline">{{ __('Sırala:') }}</label>
                <select name="sort" id="sort" onchange="this.form.submit()" 
                        class="text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-600 shadow-xs cursor-pointer">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ __('En Yeniler') }}</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>{{ __('Fiyat: Düşükten Yükseğe') }}</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ __('Fiyat: Yüksekten Düşüğe') }}</option>
                    <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>{{ __('En Çok Değerlendirilenler') }}</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sol Filtreleme Menüsü (URL Parametreli) -->
            <div class="w-full lg:w-64 shrink-0 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-filter text-indigo-600"></i> {{ __('Filtreler') }}
                        </span>
                        @if(request()->anyFilled(['category', 'kategori', 'marka', 'brand', 'min', 'max', 'min_price', 'max_price', 'renk', 'color', 'beden', 'size', 'stok', 'in_stock']))
                            <a href="{{ route('search', ['q' => $q]) }}" class="text-[11px] text-rose-600 hover:underline font-bold">
                                {{ __('Temizle') }}
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('search') }}" method="GET" class="space-y-6">
                        @if($q) <input type="hidden" name="q" value="{{ $q }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <!-- Kategoriler -->
                        <div>
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Kategoriler') }}</label>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                @foreach($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600">
                                        <input type="radio" name="category" value="{{ $category->id }}" onchange="this.form.submit()" 
                                               {{ request('category') == $category->id ? 'checked' : '' }}
                                               class="rounded text-indigo-600 focus:ring-indigo-500 text-xs">
                                        <span class="{{ request('category') == $category->id ? 'font-bold text-indigo-600' : 'text-slate-600' }}">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Markalar -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Markalar') }}</label>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                @foreach($brands as $brand)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600">
                                        <input type="radio" name="marka" value="{{ $brand->slug }}" onchange="this.form.submit()" 
                                               {{ (request('marka') == $brand->slug || request('brand') == $brand->slug) ? 'checked' : '' }}
                                               class="rounded text-indigo-600 focus:ring-indigo-500 text-xs">
                                        <span class="{{ (request('marka') == $brand->slug || request('brand') == $brand->slug) ? 'font-bold text-indigo-600' : 'text-slate-600' }}">{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Fiyat Aralığı (min - max) -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Fiyat Aralığı (TL)') }}</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="min" value="{{ request('min') ?: request('min_price') }}" placeholder="Min" 
                                       class="w-full text-xs rounded-xl border-slate-200 py-1.5 px-2 focus:ring-indigo-600">
                                <span class="text-slate-400">-</span>
                                <input type="number" name="max" value="{{ request('max') ?: request('max_price') }}" placeholder="Max" 
                                       class="w-full text-xs rounded-xl border-slate-200 py-1.5 px-2 focus:ring-indigo-600">
                            </div>
                            <button type="submit" class="w-full mt-2 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                                {{ __('Filtrele') }}
                            </button>
                        </div>

                        <!-- Renk Seçimi -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Renk') }}</label>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['Siyah', 'Beyaz', 'Mavi', 'Kırmızı', 'Gri', 'Yeşil', 'Gold', 'Pembe'] as $colorName)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="renk" value="{{ $colorName }}" onchange="this.form.submit()" class="sr-only"
                                               {{ (request('renk') == $colorName || request('color') == $colorName) ? 'checked' : '' }}>
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium border transition-colors {{ (request('renk') == $colorName || request('color') == $colorName) ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                            {{ $colorName }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Beden / Boyut Seçimi -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Beden / Boyut') }}</label>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['S', 'M', 'L', 'XL', '128GB', '256GB', '512GB'] as $sizeName)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="beden" value="{{ $sizeName }}" onchange="this.form.submit()" class="sr-only"
                                               {{ (request('beden') == $sizeName || request('size') == $sizeName) ? 'checked' : '' }}>
                                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-medium border transition-colors {{ (request('beden') == $sizeName || request('size') == $sizeName) ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                            {{ $sizeName }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sadece Stoktakiler -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="checkbox" name="stok" value="1" onchange="this.form.submit()" 
                                       {{ (request('stok') || request('in_stock')) ? 'checked' : '' }}
                                       class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span>{{ __('Sadece Stoktakiler') }}</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sağ Taraf: Arama Sonuç Kartları Izgarası -->
            <div class="flex-1 space-y-6">
                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold">
                    <span>
                        <strong class="text-slate-900">{{ $products->total() }}</strong> {{ __('ürün bulundu') }}
                    </span>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            @php
                                $minPrice = $product->min_price;
                                $maxPrice = $product->max_price;
                                $hasDiscount = $maxPrice > $minPrice && $maxPrice > 0;
                                $discountPercent = $hasDiscount ? round((($maxPrice - $minPrice) / $maxPrice) * 100) : 0;
                            @endphp

                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="group bg-white rounded-3xl border border-slate-200/80 hover:border-indigo-500/30 overflow-hidden shadow-xs hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 flex flex-col relative">
                                
                                @if($discountPercent > 0)
                                    <div class="absolute top-3 left-3 z-10 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-sm">
                                        %{{ $discountPercent }} {{ __('İNDİRİM') }}
                                    </div>
                                @endif

                                <div class="relative w-full aspect-square bg-slate-50 overflow-hidden flex items-center justify-center p-4">
                                    <img src="{{ $product->featured_image }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                                         loading="lazy">
                                </div>

                                <div class="p-5 flex flex-col flex-1 justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] mb-1">
                                            <span class="font-bold text-indigo-600 uppercase tracking-wider">{{ $product->brand->name ?? $product->seller->store_name }}</span>
                                            <div class="flex items-center text-amber-400 gap-1 font-bold text-xs">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                <span class="text-slate-700">4.9</span>
                                            </div>
                                        </div>
                                        <h3 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                                            {{ $product->name }}
                                        </h3>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-baseline justify-between">
                                        <div>
                                            <span class="text-xs text-slate-400 font-semibold">{{ __('Fiyat:') }}</span>
                                            <div class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                {{ number_format($minPrice, 2, ',', '.') }} <span class="text-xs font-bold">TL</span>
                                            </div>
                                        </div>
                                        @if($discountPercent > 0)
                                            <span class="text-xs text-slate-400 line-through">
                                                {{ number_format($maxPrice, 2, ',', '.') }} TL
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Sayfalama (Pagination Links - 24 Ürün) -->
                    <div class="pt-6 border-t border-slate-200">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">{{ __('Aramanızla eşleşen ürün bulunamadı.') }}</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">{{ __('Farklı bir anahtar kelime deneyebilir veya filtreleri sıfırlayabilirsiniz.') }}</p>
                        <div>
                            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors">
                                {{ __('Tüm Ürünleri Gör') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
