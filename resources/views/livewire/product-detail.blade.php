<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 lg:p-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Sol: Görsel Galerisi -->
        <div class="lg:col-span-6 space-y-4">
            <!-- Ana Görsel (Büyük Önizleme) -->
            <div class="relative aspect-square rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden group">
                <img src="{{ $activeImage }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-contain p-6 transition-transform duration-500 group-hover:scale-105">
                
                @if($this->selectedVariant && $this->selectedVariant->discount_price)
                    <div class="absolute top-4 left-4 bg-rose-500 text-white font-extrabold text-xs px-3 py-1.5 rounded-full shadow-md">
                        %{{ round((($this->selectedVariant->price - $this->selectedVariant->discount_price) / $this->selectedVariant->price) * 100) }} {{ __('İndirim') }}
                    </div>
                @endif

                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-xs px-2.5 py-1 rounded-full text-xs font-semibold text-slate-600 border border-slate-200/60 shadow-xs flex items-center gap-1">
                    <i class="fa-solid fa-camera text-indigo-500 text-[10px]"></i>
                    <span>{{ __('HD Fotoğraf') }}</span>
                </div>
            </div>

            <!-- Küçük Resim Galerisi (Thumbnails) -->
            @php
                $mediaThumbs = $product->getMedia('images')->map(fn($m) => $m->getUrl())->toArray();
                if (count($mediaThumbs) > 0) {
                    $thumbnails = $mediaThumbs;
                } else {
                    $gallery = is_array($product->gallery) ? $product->gallery : [];
                    $thumbnails = array_values(array_unique(array_merge([$product->featured_image], $gallery)));
                }
            @endphp

            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                @foreach($thumbnails as $thumbUrl)
                    <button type="button" 
                            wire:click="setActiveImage('{{ $thumbUrl }}')" 
                            class="w-20 h-20 rounded-xl border-2 overflow-hidden shrink-0 transition-all {{ $activeImage === $thumbUrl ? 'border-indigo-600 ring-2 ring-indigo-600/20' : 'border-slate-200 hover:border-slate-300' }}">
                        <img src="{{ $thumbUrl }}" class="w-full h-full object-contain p-1" alt="Thumbnail">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Sağ: Ürün Bilgileri ve Canlı Varyant Seçimi -->
        <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
            
            <div class="space-y-4">
                <!-- Kategori & Marka -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-lg transition-colors">
                        {{ $product->category->name }}
                    </a>
                    @if($product->brand)
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">
                            <i class="fa-solid fa-tag text-[10px] me-1 text-slate-400"></i>{{ $product->brand->name }}
                        </span>
                    @endif
                </div>

                <!-- Ürün Başlığı -->
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>

                <!-- Puan & Satıcı -->
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5 text-amber-500 bg-amber-50 px-2.5 py-1 rounded-lg font-bold">
                        <i class="fa-solid fa-star"></i>
                        <span class="text-slate-800">4.8</span>
                        <span class="text-slate-400 font-normal">({{ __('24 Değerlendirme') }})</span>
                    </div>

                    <div class="text-slate-400">|</div>

                    <div class="flex items-center gap-1.5 text-slate-600">
                        <i class="fa-solid fa-store text-indigo-600"></i>
                        <span>{{ __('Satıcı:') }}</span>
                        <span class="font-bold text-slate-900 hover:text-indigo-600 transition-colors">
                            {{ $product->seller->store_name }}
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                            {{ __('Onaylı') }}
                        </span>
                    </div>

                    <div class="text-slate-400">|</div>

                    <a href="#soru-cevap" class="flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg font-bold transition-colors">
                        <i class="fa-solid fa-comments"></i>
                        <span>{{ $product->questions()->where('is_approved', true)->count() }} {{ __('Soru & Cevap') }}</span>
                    </a>
                </div>

                <!-- Fiyat Bloğu (Livewire ile Reaktif Güncellenir) -->
                <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-50 to-indigo-50/30 border border-slate-200/80">
                    <div class="flex items-baseline gap-3">
                        @if($this->selectedVariant && $this->selectedVariant->discount_price)
                            <div class="text-3xl sm:text-4xl font-black text-indigo-600 tracking-tight">
                                {{ number_format($this->selectedVariant->discount_price, 2, ',', '.') }} TL
                            </div>
                            <div class="text-lg text-slate-400 line-through font-semibold">
                                {{ number_format($this->selectedVariant->price, 2, ',', '.') }} TL
                            </div>
                        @elseif($this->selectedVariant)
                            <div class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                                {{ number_format($this->selectedVariant->price, 2, ',', '.') }} TL
                            </div>
                        @endif
                    </div>

                    <div class="mt-2 flex items-center gap-2 text-xs">
                        <span class="text-emerald-700 font-bold flex items-center gap-1">
                            <i class="fa-solid fa-truck-fast"></i> {{ __('Ücretsiz Kargo') }}
                        </span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="text-slate-500">{{ __('14 Gün Koşulsuz İade') }}</span>
                    </div>
                </div>

                <!-- Varyant Seçenekleri (Renk & Beden) -->
                <div class="space-y-4 pt-2">
                    <!-- Renk Seçimi -->
                    @if(count($this->availableColors) > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                    {{ __('Renk Seçimi:') }} <span class="text-indigo-600 capitalize font-extrabold">{{ $selectedColor }}</span>
                                </label>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($this->availableColors as $col)
                                    <button type="button" 
                                            wire:click="selectColor('{{ $col }}')" 
                                            class="px-3.5 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2 {{ $selectedColor === $col ? 'border-indigo-600 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-600/20 shadow-xs' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white' }}">
                                        @if($selectedColor === $col)
                                            <i class="fa-solid fa-check text-[10px] text-indigo-600"></i>
                                        @endif
                                        <span>{{ $col }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Beden / Boyut Seçimi -->
                    @if(count($this->availableSizes) > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                    {{ __('Seçenek / Beden:') }} <span class="text-indigo-600 font-extrabold">{{ $selectedSize }}</span>
                                </label>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($this->availableSizes as $sz)
                                    <button type="button" 
                                            wire:click="selectSize('{{ $sz }}')" 
                                            class="min-w-[48px] px-3.5 py-2 rounded-xl text-xs font-bold border text-center transition-all {{ $selectedSize === $sz ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm shadow-indigo-600/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white' }}">
                                        {{ $sz }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Stok Durumu & SKU -->
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-slate-400">{{ __('Stok Durumu:') }}</span>
                        @if($this->selectedVariant && $this->selectedVariant->stock > 0)
                            <span class="inline-flex items-center gap-1.5 font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ __('Stokta Var') }} ({{ $this->selectedVariant->stock }} {{ __('Adet') }})
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 font-bold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                                {{ __('Tükendi') }}
                            </span>
                        @endif
                    </div>

                    @if($this->selectedVariant)
                        <div class="text-slate-400 font-mono text-[11px]">
                            SKU: <span class="text-slate-600">{{ $this->selectedVariant->sku }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Adet ve Sepete Ekle Butonları -->
            <div class="space-y-3 pt-4 border-t border-slate-100">
                
                @if(session()->has('cart_success'))
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs font-bold flex items-center gap-2 animate-fade-in">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('cart_success') }}</span>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <!-- Adet Sayacı -->
                    <div class="flex items-center border border-slate-200 rounded-2xl bg-slate-50 p-1">
                        <button type="button" 
                                wire:click="decrementQuantity" 
                                class="w-10 h-10 rounded-xl bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-black transition-colors focus:outline-none shadow-xs">
                            <i class="fa-solid fa-minus text-xs"></i>
                        </button>
                        <span class="w-12 text-center font-black text-slate-900 text-sm">
                            {{ $quantity }}
                        </span>
                        <button type="button" 
                                wire:click="incrementQuantity" 
                                class="w-10 h-10 rounded-xl bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center font-black transition-colors focus:outline-none shadow-xs">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>

                    <!-- Sepete Ekle Butonu -->
                    @if($this->selectedVariant && $this->selectedVariant->stock > 0)
                        <button type="button" 
                                wire:click="addToCart" 
                                class="flex-1 py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-600/25 transition-all flex items-center justify-center gap-2 transform active:scale-95">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>{{ __('Sepete Ekle') }}</span>
                        </button>
                    @else
                        <button type="button" 
                                disabled 
                                class="flex-1 py-3.5 px-6 rounded-2xl bg-slate-200 text-slate-400 font-extrabold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i>
                            <span>{{ __('Stokta Yok') }}</span>
                        </button>
                    @endif
                </div>

                <!-- Satıcıya Soru Sor Hızlı Buton -->
                <div class="pt-1">
                    <a href="#soru-cevap" class="w-full py-2.5 px-4 rounded-xl border border-indigo-200 bg-indigo-50/70 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition-all flex items-center justify-center gap-2 shadow-2xs">
                        <i class="fa-solid fa-circle-question text-indigo-600"></i>
                        <span>{{ __('Satıcıya Bu Ürün Hakkında Soru Sor') }}</span>
                    </a>
                </div>

                <!-- Güvenilirlik Rozetleri -->
                <div class="grid grid-cols-3 gap-2 pt-2 text-[11px] text-center text-slate-500 font-medium">
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-shield-halved text-indigo-600 text-sm"></i>
                        <span>{{ __('Orijinal Ürün') }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-truck text-indigo-600 text-sm"></i>
                        <span>{{ __('Hızlı Gönderi') }}</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-arrows-rotate text-indigo-600 text-sm"></i>
                        <span>{{ __('Kolay Değişim') }}</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
