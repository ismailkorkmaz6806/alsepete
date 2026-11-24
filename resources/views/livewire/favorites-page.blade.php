<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Başlık -->
    <div class="pb-6 border-b border-slate-200">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
            <i class="fa-solid fa-heart text-rose-500"></i>
            <span>{{ __('Favori Ürünlerim') }}</span>
            <span class="text-sm font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $favorites->total() }} {{ __('Ürün') }}
            </span>
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            {{ __('Beğendiğiniz ürünleri burada saklayabilir, fiyatlarını takip edebilir ve tek tıkla sepetinize ekleyebilirsiniz.') }}
        </p>
    </div>

    @if($message)
        <div class="mt-4 p-4 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-900 text-xs font-bold flex items-center justify-between gap-3 animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-600"></i>
                <span>{{ $message }}</span>
            </div>
            <button type="button" wire:click="$set('message', null)" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($favorites->isEmpty())
        <div class="py-16 text-center">
            <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-rose-50 border-2 border-rose-100 flex items-center justify-center text-rose-400 shadow-sm">
                <i class="fa-regular fa-heart text-4xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">{{ __('Henüz Favori Ürününüz Yok') }}</h2>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                {{ __('Beğendiğiniz ürünlerin üzerindeki kalp ikonuna tıklayarak favorilerinize ekleyebilirsiniz.') }}
            </p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/25 transition-all">
                <i class="fa-solid fa-compass"></i>
                <span>{{ __('Ürünleri Keşfet') }}</span>
            </a>
        </div>
    @else
        <!-- Favori Ürünler Izgarası -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($favorites as $product)
                @php
                    $mediaImg = $product->getFirstMediaUrl('gallery');
                    $imgUrl = $mediaImg ?: ($product->image_url ?: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600');
                    $primaryVariant = $product->variants->first();
                @endphp
                <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group relative">
                    
                    <!-- Çıkar Butonu -->
                    <button type="button" 
                            wire:click="remove({{ $product->id }})" 
                            title="{{ __('Favorilerden Çıkar') }}"
                            class="absolute top-6 right-6 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-rose-50 text-rose-500 flex items-center justify-center shadow-xs transition transform hover:scale-110">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>

                    <div>
                        <!-- Görsel -->
                        <a href="{{ route('products.show', $product->slug) }}" class="block aspect-square rounded-2xl overflow-hidden bg-slate-100 mb-3 relative">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </a>

                        <!-- Satıcı & Başlık -->
                        <div class="space-y-1">
                            <a href="{{ route('stores.show', $product->seller->slug) }}" class="text-[11px] font-bold text-indigo-600 hover:underline block truncate">
                                {{ $product->seller->store_name }}
                            </a>
                            <a href="{{ route('products.show', $product->slug) }}" class="font-extrabold text-sm text-slate-900 group-hover:text-indigo-600 transition line-clamp-2">
                                {{ $product->name }}
                            </a>
                        </div>
                    </div>

                    <!-- Fiyat ve Sepete Ekle Butonu -->
                    <div class="pt-4 border-t border-slate-100 mt-4 space-y-3">
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-black text-slate-900">
                                {{ number_format($product->min_price, 2, ',', '.') }} TL
                            </span>
                        </div>

                        <button type="button" 
                                wire:click="addToCart({{ $product->id }})" 
                                class="w-full py-2.5 px-4 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white font-extrabold text-xs transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-xs"></i>
                            <span>{{ __('Sepete Ekle') }}</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @endif

</div>
