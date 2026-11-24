<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Başlık & Temizle -->
    <div class="flex items-center justify-between pb-6 border-b border-slate-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-bag-shopping text-indigo-600"></i>
                <span>{{ __('Alışveriş Sepetim') }}</span>
                <span class="text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                    {{ $itemCount }} {{ __('Ürün') }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ __('Ürünler satıcı bazında gruplanır ve kargo ücretleri ayrı hesaplanır.') }}
            </p>
        </div>

        @if($itemCount > 0)
            <button type="button" 
                    wire:click="clearCart" 
                    wire:confirm="{{ __('Sepetinizdeki tüm ürünler silinecek. Emin misiniz?') }}"
                    class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-3 py-2 rounded-xl transition flex items-center gap-1.5">
                <i class="fa-regular fa-trash-can"></i>
                <span>{{ __('Sepeti Boşalt') }}</span>
            </button>
        @endif
    </div>

    <!-- Bildirim Mesajı (Kupon / Stok) -->
    @if($couponMessage)
        <div class="mt-4 p-4 rounded-2xl text-xs font-bold flex items-center justify-between gap-3 animate-fade-in {{ $couponMessageType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($couponMessageType === 'error' ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
            <div class="flex items-center gap-2">
                @if($couponMessageType === 'success')
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                @elseif($couponMessageType === 'error')
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                @else
                    <i class="fa-solid fa-circle-info text-slate-500 text-sm"></i>
                @endif
                <span>{{ $couponMessage }}</span>
            </div>
            <button type="button" wire:click="$set('couponMessage', null)" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($itemCount === 0)
        <!-- Boş Sepet Durumu -->
        <div class="py-16 text-center">
            <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-indigo-50 border-2 border-indigo-100 flex items-center justify-center text-indigo-400 shadow-sm">
                <i class="fa-solid fa-cart-shopping text-4xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('Sepetinizde Ürün Bulunmuyor') }}</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                {{ __('Binlerce kaliteli ürün ve fırsat sizi bekliyor. Hemen keşfetmeye başlayın!') }}
            </p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                <i class="fa-solid fa-store"></i>
                <span>{{ __('Alışverişe Başla') }}</span>
            </a>
        </div>
    @else
        <!-- Dolu Sepet İki Kolon Düzeni -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Sol Kolon: Satıcı Bazlı Gruplanmış Ürün Kartları (8 Kolon) -->
            <div class="lg:col-span-8 space-y-6">
                @foreach($groups as $sellerId => $group)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                        
                        <!-- Satıcı Başlık Barı -->
                        <div class="p-4 sm:p-5 bg-slate-50/80 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-500 font-medium block">{{ __('Satıcı:') }}</span>
                                    <a href="{{ route('stores.show', $group['seller']->slug) }}" class="font-extrabold text-sm text-slate-900 hover:text-indigo-600 transition flex items-center gap-1.5">
                                        <span>{{ $group['seller']->store_name }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                            {{ __('Onaylı') }}
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <!-- Kargo Durumu -->
                            <div class="text-right">
                                @if($group['is_free_shipping'])
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-truck-fast"></i>
                                        <span>{{ __('Kargo Bedava') }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900">
                                        <i class="fa-solid fa-truck"></i>
                                        <span>{{ __('Kargo:') }} {{ number_format($group['shipping_fee'], 2, ',', '.') }} TL</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Kargo İlerleme / Teşvik Çubuğu (300 TL Kuralı) -->
                        <div class="px-5 py-2.5 bg-slate-100/50 border-b border-slate-100 text-[11px] flex items-center justify-between">
                            @if($group['is_free_shipping'])
                                <span class="text-emerald-700 font-bold flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    {{ __('Tebrikler! Bu mağazadan verdiğiniz siparişte kargo ücretsiz.') }}
                                </span>
                            @else
                                <span class="text-slate-600 font-medium">
                                    {{ __('300 TL üzeri kargo bedava!') }} 
                                    <strong class="text-indigo-600 font-bold">{{ number_format($group['free_shipping_remaining'], 2, ',', '.') }} TL</strong> 
                                    {{ __('daha ürün ekleyin, kargo bedava olsun.') }}
                                </span>
                            @endif
                        </div>

                        <!-- Satıcıya Ait Ürünler Listesi -->
                        <div class="divide-y divide-slate-100">
                            @foreach($group['items'] as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $variant->product;
                                    $mediaImg = $product->getFirstMediaUrl('gallery');
                                    $imgUrl = $mediaImg ?: ($product->image_url ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400');
                                @endphp
                                <div class="p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    
                                    <!-- Ürün Görseli ve Bilgisi -->
                                    <div class="flex items-center gap-4 flex-1">
                                        <a href="{{ route('products.show', $product->slug) }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        </a>

                                        <div class="space-y-1">
                                            <a href="{{ route('products.show', $product->slug) }}" class="font-bold text-sm text-slate-900 hover:text-indigo-600 transition line-clamp-1">
                                                {{ $product->name }}
                                            </a>

                                            <!-- Varyant (Renk / Beden) -->
                                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-500">
                                                @if($variant->color)
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 font-medium">
                                                        {{ __('Renk:') }} <strong class="text-slate-700">{{ $variant->color }}</strong>
                                                    </span>
                                                @endif
                                                @if($variant->size)
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 font-medium">
                                                        {{ __('Beden:') }} <strong class="text-slate-700">{{ $variant->size }}</strong>
                                                    </span>
                                                @endif
                                                <span class="text-slate-400 font-mono text-[10px]">SKU: {{ $variant->sku }}</span>
                                            </div>

                                            <!-- Birim Fiyat -->
                                            <div class="flex items-baseline gap-2 pt-0.5">
                                                <span class="font-extrabold text-xs text-slate-800">
                                                    {{ number_format($variant->final_price, 2, ',', '.') }} TL
                                                </span>
                                                @if($variant->discount_price)
                                                    <span class="text-[10px] text-slate-400 line-through">
                                                        {{ number_format($variant->price, 2, ',', '.') }} TL
                                                    </span>
                                                @endif
                                                <span class="text-[10px] text-slate-400">/ {{ __('adet') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Adet Sayacı & Satır Toplamı & Sil Butonu -->
                                    <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                        
                                        <!-- Canlı Adet Butonları -->
                                        <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 p-1">
                                            <button type="button" 
                                                    wire:click="decrement({{ $item->id }}, {{ $item->quantity }})"
                                                    class="w-7 h-7 rounded-lg bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shadow-2xs transition">
                                                <i class="fa-solid fa-minus text-[10px]"></i>
                                            </button>
                                            <span class="w-9 text-center font-extrabold text-xs text-slate-900">
                                                {{ $item->quantity }}
                                            </span>
                                            <button type="button" 
                                                    wire:click="increment({{ $item->id }}, {{ $item->quantity }})"
                                                    class="w-7 h-7 rounded-lg bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shadow-2xs transition">
                                                <i class="fa-solid fa-plus text-[10px]"></i>
                                            </button>
                                        </div>

                                        <!-- Satır Toplam Tutarı -->
                                        <div class="text-right min-w-[90px]">
                                            <span class="font-black text-sm text-indigo-700 block">
                                                {{ number_format($variant->final_price * $item->quantity, 2, ',', '.') }} TL
                                            </span>
                                        </div>

                                        <!-- Sil -->
                                        <button type="button" 
                                                wire:click="removeItem({{ $item->id }})"
                                                title="{{ __('Sepetten Kaldır') }}"
                                                class="text-slate-400 hover:text-rose-600 transition p-1.5 rounded-lg hover:bg-rose-50">
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <!-- Mağaza Ara Toplamı -->
                        <div class="p-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">
                                {{ $group['seller']->store_name }} {{ __('Toplamı:') }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-900">
                                    {{ number_format($group['total'], 2, ',', '.') }} TL
                                </span>
                                @if(! $group['is_free_shipping'])
                                    <span class="text-[10px] text-slate-500">({{ __('kargo dahil') }})</span>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Sağ Kolon: Sipariş Özeti ve Kupon (4 Kolon) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- Kupon Kartı -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-ticket text-indigo-600"></i>
                        <span>{{ __('İndirim Kuponu') }}</span>
                    </h3>

                    @if($totals['coupon'])
                        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                                <div>
                                    <strong class="font-mono text-xs text-emerald-950 font-bold uppercase tracking-wider">
                                        {{ $totals['coupon']->code }}
                                    </strong>
                                    <span class="text-[11px] text-emerald-700 block">
                                        -{{ number_format($totals['discount'], 2, ',', '.') }} TL {{ __('indirim uygulandı') }}
                                    </span>
                                </div>
                            </div>
                            <button type="button" 
                                    wire:click="removeCoupon" 
                                    class="text-rose-600 hover:text-rose-800 text-xs font-bold px-2 py-1 rounded-lg hover:bg-rose-100/50 transition">
                                {{ __('Kaldır') }}
                            </button>
                        </div>
                    @else
                        <form wire:submit="applyCoupon" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="text" 
                                       wire:model="couponCode" 
                                       placeholder="{{ __('Örn: HOSGELDIN10') }}" 
                                       class="flex-1 uppercase font-mono text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-indigo-600 py-2.5">
                                <button type="submit" 
                                        class="px-4 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl text-xs font-extrabold transition shadow-xs">
                                    {{ __('Uygula') }}
                                </button>
                            </div>
                            <span class="text-[10px] text-slate-400 block">
                                {{ __('Test kuponları: HOSGELDIN10, INDIRIM50') }}
                            </span>
                        </form>
                    @endif
                </div>

                <!-- Sipariş Özeti Kartı -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs space-y-4">
                    <h2 class="text-sm font-extrabold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                        <span>{{ __('Sipariş Özeti') }}</span>
                        <span class="text-xs text-slate-400 font-normal">({{ $totals['seller_count'] }} {{ __('Mağaza') }})</span>
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>{{ __('Ürünler Toplamı') }}</span>
                            <span class="font-bold text-slate-900">{{ number_format($totals['subtotal'], 2, ',', '.') }} TL</span>
                        </div>

                        <div class="flex items-center justify-between text-slate-600">
                            <div class="flex items-center gap-1">
                                <span>{{ __('Kargo Toplamı') }}</span>
                                <span class="text-[10px] text-slate-400">({{ $totals['seller_count'] }} {{ __('satıcı') }})</span>
                            </div>
                            @if($totals['total_shipping'] == 0)
                                <span class="font-extrabold text-emerald-600 uppercase">{{ __('Bedava') }}</span>
                            @else
                                <span class="font-bold text-slate-900">{{ number_format($totals['total_shipping'], 2, ',', '.') }} TL</span>
                            @endif
                        </div>

                        @if($totals['discount'] > 0)
                            <div class="flex items-center justify-between text-emerald-700 bg-emerald-50/70 p-2 rounded-xl">
                                <span>{{ __('Kupon İndirimi') }} ({{ $totals['coupon']?->code }})</span>
                                <span class="font-black">-{{ number_format($totals['discount'], 2, ',', '.') }} TL</span>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-200 flex items-baseline justify-between">
                            <div>
                                <span class="font-black text-slate-900 text-sm block">{{ __('Ödenecek Tutar') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('KDV ve Kargo Dahil') }}</span>
                            </div>
                            <span class="text-2xl font-black text-indigo-600 tracking-tight">
                                {{ number_format($totals['grand_total'], 2, ',', '.') }} TL
                            </span>
                        </div>
                    </div>

                    <!-- Siparişi Tamamla Butonu -->
                    <div class="pt-2">
                        <a href="{{ route('checkout') }}" 
                           class="w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2 transform active:scale-98">
                            <span>{{ __('Siparişi Tamamla') }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <span class="text-[10px] text-slate-400 text-center block mt-2">
                            <i class="fa-solid fa-shield-halved text-indigo-500 mr-1"></i>
                            {{ __('256-Bit SSL ile Güvenli Alışveriş') }}
                        </span>
                    </div>

                </div>

            </div>

        </div>
    @endif

</div>
