<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center text-2xl font-black shadow-md shadow-indigo-600/20 shrink-0">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ __('Onaylı Satıcı Mağazası') }}
                        </span>
                        <span class="text-xs text-slate-400">|</span>
                        <span class="text-xs text-slate-500 font-medium">{{ __('Kayıt:') }} {{ $seller->created_at->format('M Y') }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        {{ $seller->store_name }}
                    </h1>
                </div>
            </div>

            <!-- Sağ Taraf: Sıralama Dropdown'ı -->
            <form action="{{ route('stores.show', $seller->slug) }}" method="GET" class="flex items-center gap-2">
                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('brand') || request('marka')) <input type="hidden" name="brand" value="{{ request('brand') ?: request('marka') }}"> @endif
                @if(request('min') || request('min_price')) <input type="hidden" name="min" value="{{ request('min') ?: request('min_price') }}"> @endif
                @if(request('max') || request('max_price')) <input type="hidden" name="max" value="{{ request('max') ?: request('max_price') }}"> @endif
                @if(request('renk') || request('color')) <input type="hidden" name="renk" value="{{ request('renk') ?: request('color') }}"> @endif
                @if(request('beden') || request('size')) <input type="hidden" name="beden" value="{{ request('beden') ?: request('size') }}"> @endif
                @if(request('in_stock') || request('stok')) <input type="hidden" name="in_stock" value="1"> @endif

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
            
            <!-- Sol Filtreleme Menüsü -->
            <div class="w-full lg:w-64 shrink-0 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-600"></i> {{ __('Mağaza Filtreleri') }}
                        </span>
                        @if(request()->anyFilled(['category', 'brand', 'marka', 'min', 'max', 'min_price', 'max_price', 'renk', 'color', 'beden', 'size', 'in_stock', 'stok']))
                            <a href="{{ route('stores.show', $seller->slug) }}" class="text-[11px] text-rose-600 hover:underline font-bold">
                                {{ __('Temizle') }}
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('stores.show', $seller->slug) }}" method="GET" class="space-y-6">
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <!-- Kategoriler -->
                        @if($categories->count() > 0)
                            <div>
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Kategoriler') }}</label>
                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                    @foreach($categories as $cat)
                                        <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600">
                                            <input type="radio" name="category" value="{{ $cat->id }}" onchange="this.form.submit()" 
                                                   {{ request('category') == $cat->id ? 'checked' : '' }}
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 text-xs">
                                            <span class="{{ request('category') == $cat->id ? 'font-bold text-indigo-600' : 'text-slate-600' }}">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Markalar -->
                        @if($brands->count() > 0)
                            <div class="border-t border-slate-100 pt-4">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">{{ __('Markalar') }}</label>
                                <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 text-xs">
                                    @foreach($brands as $brand)
                                        <label class="flex items-center gap-2 cursor-pointer hover:text-indigo-600">
                                            <input type="radio" name="brand" value="{{ $brand->slug }}" onchange="this.form.submit()" 
                                                   {{ (request('brand') == $brand->slug || request('marka') == $brand->slug) ? 'checked' : '' }}
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 text-xs">
                                            <span class="{{ (request('brand') == $brand->slug || request('marka') == $brand->slug) ? 'font-bold text-indigo-600' : 'text-slate-600' }}">{{ $brand->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Fiyat Aralığı -->
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

                        <!-- Stok Durumu -->
                        <div class="border-t border-slate-100 pt-4">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="checkbox" name="in_stock" value="1" onchange="this.form.submit()" 
                                       {{ (request('in_stock') || request('stok')) ? 'checked' : '' }}
                                       class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span>{{ __('Sadece Stoktakiler') }}</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sağ Ürün Kartları Izgarası -->
            <div class="flex-1 space-y-6">
                <div class="flex items-center justify-between text-xs text-slate-500 font-semibold">
                    <span>
                        <strong class="text-slate-900">{{ $products->total() }}</strong> {{ __('ürün listeleniyor') }}
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
                                            <span class="font-bold text-indigo-600 uppercase tracking-wider">{{ $product->brand->name ?? $seller->store_name }}</span>
                                            <div class="flex items-center text-amber-400 gap-1 font-bold text-xs">
                                                <i class="fa-solid fa-star text-[10px]"></i>
                                                <span class="text-slate-700">4.8</span>
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

                    <!-- Sayfalama (24 Ürün) -->
                    <div class="pt-6 border-t border-slate-200">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">{{ __('Bu mağazada filtrelere uygun ürün bulunamadı.') }}</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">{{ __('Farklı bir filtre deneyebilir veya mağaza sayfasına geri dönebilirsiniz.') }}</p>
                        <div>
                            <a href="{{ route('stores.show', $seller->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors">
                                {{ __('Filtreleri Sıfırla') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
