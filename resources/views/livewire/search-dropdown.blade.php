<div class="relative w-full" x-data="{ isOpen: true }" @click.outside="isOpen = false">
    <form action="{{ route('search') }}" method="GET" class="w-full relative group">
        <input type="text" 
               name="q" 
               wire:model.live.debounce.250ms="query"
               @focus="isOpen = true"
               @keydown.escape.window="isOpen = false"
               autocomplete="off"
               placeholder="{{ __('Ürün, marka, kategori veya satıcı ara...') }}" 
               class="w-full bg-slate-100 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 rounded-2xl py-2.5 pl-11 pr-24 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200">
        
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors"></i>
        
        <div wire:loading class="absolute right-20 top-3 text-indigo-600 text-xs">
            <i class="fa-solid fa-circle-notch animate-spin"></i>
        </div>

        <button type="submit" class="absolute right-1.5 top-1.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-xs font-semibold px-4 py-1.5 rounded-xl transition-all shadow-sm cursor-pointer">
            {{ __('Ara') }}
        </button>
    </form>

    <!-- Autocomplete Canlı Öneri Açılır Menüsü -->
    @if(mb_strlen(trim($query)) >= 2)
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl border border-slate-200 shadow-2xl overflow-hidden z-50 text-left">
            
            <div class="p-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-500">
                <span>{{ __('Arama Önerileri') }}</span>
                <span class="text-indigo-600">"{{ $query }}"</span>
            </div>

            <!-- Eşleşen Kategoriler & Markalar -->
            @if($categories->count() > 0 || $brands->count() > 0)
                <div class="p-3 bg-white border-b border-slate-100 flex flex-wrap gap-2 text-xs">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition">
                            <i class="fa-solid fa-layer-group text-[10px]"></i>
                            <span>{{ $category->name }}</span>
                        </a>
                    @endforeach

                    @foreach($brands as $brand)
                        <a href="{{ route('search', ['marka' => $brand->slug]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition">
                            <i class="fa-solid fa-tag text-[10px]"></i>
                            <span>{{ $brand->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Eşleşen Ürünler -->
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($products as $product)
                    @php $minPrice = $product->min_price; @endphp
                    <a href="{{ route('products.show', $product->slug) }}" class="flex items-center gap-3 p-3 hover:bg-slate-50 transition group">
                        <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-10 h-10 object-contain rounded-lg border border-slate-200 shrink-0 bg-slate-50">
                        <div class="flex-1 min-w-0">
                            <div class="text-[11px] font-bold text-indigo-600 uppercase">{{ $product->brand->name ?? 'AlSepete' }}</div>
                            <div class="text-xs font-semibold text-slate-800 truncate group-hover:text-indigo-600 transition">
                                {{ $product->name }}
                            </div>
                        </div>
                        <div class="text-xs font-black text-slate-900 shrink-0">
                            {{ number_format($minPrice, 2, ',', '.') }} TL
                        </div>
                    </a>
                @empty
                    @if($categories->isEmpty() && $brands->isEmpty())
                        <div class="p-6 text-center text-xs text-slate-400">
                            <i class="fa-solid fa-magnifying-glass mb-1 block text-lg"></i>
                            {{ __('Sonuç bulunamadı.') }}
                        </div>
                    @endif
                @endforelse
            </div>

            <!-- Tüm Sonuçları Gör Butonu -->
            <a href="{{ route('search', ['q' => $query]) }}" class="block p-3 text-center text-xs font-bold bg-slate-50 hover:bg-indigo-50 text-indigo-600 hover:text-indigo-700 transition border-t border-slate-100">
                <span>{{ __('Tüm Sonuçları Gör') }}</span> &rarr;
            </a>
        </div>
    @endif
</div>
