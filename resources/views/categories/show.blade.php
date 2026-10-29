<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <!-- Ekmek Kırıntısı (Breadcrumbs) -->
                <nav class="flex items-center text-xs text-slate-500 gap-2 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">
                        {{ __('Ana Sayfa') }}
                    </a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                    @if($category->parent)
                        <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-indigo-600 transition-colors">
                            {{ $category->parent->name }}
                        </a>
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                    @endif
                    <span class="font-bold text-slate-800">{{ $category->name }}</span>
                </nav>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>{{ $category->name }}</span>
                    <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                        {{ $products->total() }} {{ __('Ürün') }}
                    </span>
                </h1>
            </div>

            <!-- Sıralama Açılır Menüsü (Hafta 3 Kabul Kriteri) -->
            <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-2">
                @if(request('brand'))
                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                @endif
                @if(request('min_price'))
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                @endif
                @if(request('max_price'))
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                @endif
                @if(request('in_stock'))
                    <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                @endif

                <label for="sort" class="text-xs font-bold text-slate-600 hidden sm:inline">{{ __('Sırala:') }}</label>
                <select name="sort" 
                        id="sort" 
                        onchange="this.form.submit()" 
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
        
        <!-- Alt Kategoriler Buton Çubuğu -->
        @if($category->children->count() > 0)
            <div class="mb-8 flex items-center gap-2 overflow-x-auto pb-2">
                <span class="text-xs font-bold text-slate-400 shrink-0">{{ __('Alt Kategoriler:') }}</span>
                @foreach($category->children as $child)
                    <a href="{{ route('categories.show', $child->slug) }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all shrink-0 shadow-xs">
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sol Sütun: Filtreler Yan Çubuğu -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-indigo-600"></i> {{ __('Filtreler') }}
                        </h3>
                        @if(request()->anyFilled(['brand', 'min_price', 'max_price', 'in_stock']))
                            <a href="{{ route('categories.show', $category->slug) }}" class="text-[11px] font-bold text-rose-500 hover:underline">
                                {{ __('Temizle') }}
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="space-y-6">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <!-- Stok Filtresi -->
                        <div>
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                <input type="checkbox" 
                                       name="in_stock" 
                                       value="1" 
                                       {{ request('in_stock') ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span>{{ __('Sadece Stoktakiler') }}</span>
                            </label>
                        </div>

                        <!-- Marka Filtresi -->
                        @if($brands->count() > 0)
                            <div class="space-y-3">
                                <label class="text-xs font-bold text-slate-900 uppercase tracking-wider block">
                                    {{ __('Markalar') }}
                                </label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                    @foreach($brands as $b)
                                        <label class="flex items-center justify-between text-xs text-slate-600 hover:text-slate-900 cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <input type="radio" 
                                                       name="brand" 
                                                       value="{{ $b->id }}" 
                                                       {{ request('brand') == $b->id ? 'checked' : '' }}
                                                       onchange="this.form.submit()"
                                                       class="text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                                <span>{{ $b->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Fiyat Aralığı -->
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider block">
                                {{ __('Fiyat Aralığı (TL)') }}
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}" 
                                       placeholder="En az" 
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600/20 p-2">
                                <span class="text-slate-400">-</span>
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}" 
                                       placeholder="En çok" 
                                       class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600/20 p-2">
                            </div>
                            <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors">
                                {{ __('Uygula') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sağ Sütun: 24 Ürünlük Grid ve Sayfalama -->
            <div class="lg:col-span-3 space-y-8">
                
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($products as $product)
                            @php
                                $primaryVariant = $product->variants->first();
                                $productImage = $product->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';
                            @endphp
                            <a href="{{ route('products.show', $product->slug) }}" 
                               class="group bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-lg hover:border-indigo-300 transition-all flex flex-col justify-between">
                                
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
                                        {{ $product->category->name }}
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
                                            <span class="text-slate-700">4.8</span>
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

                    <!-- Sayfalama (Pagination Links - 24 Ürün) -->
                    <div class="pt-6 border-t border-slate-200">
                        {{ $products->links() }}
                    </div>

                @else
                    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">{{ __('Bu kategoride seçilen filtrelere uygun ürün bulunamadı.') }}</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">{{ __('Farklı bir filtre deneyebilir veya kategori sayfasına geri dönebilirsiniz.') }}</p>
                        <div>
                            <a href="{{ route('categories.show', $category->slug) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors">
                                {{ __('Filtreleri Sıfırla') }}
                            </a>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
</x-app-layout>
