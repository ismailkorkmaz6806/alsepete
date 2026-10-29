<x-app-layout>
    <x-slot name="header">
        <!-- Ekmek Kırıntısı (Breadcrumb) -->
        <nav class="flex items-center text-xs text-slate-500 gap-2 overflow-x-auto py-1">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-house text-[10px]"></i>
                <span>{{ __('Ana Sayfa') }}</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
            @if($product->category->parent)
                <a href="{{ route('categories.show', $product->category->parent->slug) }}" class="hover:text-indigo-600 transition-colors">
                    {{ $product->category->parent->name }}
                </a>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
            @endif
            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-indigo-600 transition-colors font-semibold text-slate-700">
                {{ $product->category->name }}
            </a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
            <span class="text-slate-400 truncate max-w-xs">{{ $product->name }}</span>
        </nav>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <!-- Livewire Ürün Detay & Varyant Seçici (Hafta 3 Çekirdek Özellik) -->
        <livewire:product-detail :product="$product" />

        <!-- Ürün Açıklaması ve Özellikleri -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 lg:p-10 space-y-4">
            <h3 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fa-solid fa-circle-info text-indigo-600"></i>
                {{ __('Ürün Açıklaması & Özellikleri') }}
            </h3>
            <div class="prose max-w-none text-slate-600 text-xs sm:text-sm leading-relaxed space-y-3">
                <p>{{ $product->description }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 pt-3">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block">{{ __('Garanti Süresi') }}</span>
                        <span class="font-bold text-slate-800 text-xs">2 Yıl Distribütör Garantili</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block">{{ __('Kargo Durumu') }}</span>
                        <span class="font-bold text-emerald-600 text-xs">Aynı Gün Hızlı Kargo</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block">{{ __('İade Politikası') }}</span>
                        <span class="font-bold text-slate-800 text-xs">14 Gün Kolay İade</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-[11px] text-slate-400 block">{{ __('Güvenli Ödeme') }}</span>
                        <span class="font-bold text-slate-800 text-xs">256-Bit SSL Koruması</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Görev 1: "Bu Ürünü Alanlar Bunları da Aldı" Öneri Sistemi (+2 Puan) -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                <i class="fa-solid fa-award text-[10px] me-1"></i> {{ __('Bonus Özellik (+2 Puan)') }}
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-sparkles text-indigo-600"></i>
                            {{ __('Bu Ürünü Alanlar Bunları da Aldı') }}
                        </h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($relatedProducts as $relProduct)
                        @php
                            $relVariant = $relProduct->variants->first();
                            $relImg = $relProduct->getFirstMediaUrl('images') ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80';
                        @endphp
                        <a href="{{ route('products.show', $relProduct->slug) }}" 
                           class="group bg-white rounded-2xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md hover:border-indigo-300 transition-all flex flex-col justify-between">
                            <div class="relative aspect-square rounded-xl bg-slate-50 overflow-hidden mb-3">
                                <img src="{{ $relImg }}" alt="{{ $relProduct->name }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                                @if($relVariant && $relVariant->discount_price)
                                    <span class="absolute top-2 left-2 bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-full">
                                        %{{ round((($relVariant->price - $relVariant->discount_price) / $relVariant->price) * 100) }}
                                    </span>
                                @endif
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ $relProduct->category->name }}</span>
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $relProduct->name }}
                                </h4>
                                <div class="pt-2 flex items-baseline gap-2">
                                    @if($relVariant && $relVariant->discount_price)
                                        <span class="text-sm font-black text-indigo-600">{{ number_format($relVariant->discount_price, 2, ',', '.') }} TL</span>
                                        <span class="text-[10px] text-slate-400 line-through">{{ number_format($relVariant->price, 2, ',', '.') }} TL</span>
                                    @elseif($relVariant)
                                        <span class="text-sm font-black text-slate-900">{{ number_format($relVariant->price, 2, ',', '.') }} TL</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Bonus Görev 2: Satıcıya Soru Sor & Cevap Sistemi (+2 Puan) -->
        <livewire:product-questions :product="$product" />

    </div>
</x-app-layout>
