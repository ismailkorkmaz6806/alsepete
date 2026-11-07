<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('seller.products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline mb-1 inline-flex items-center gap-1">
                    &larr; {{ __('Ürünlerime Geri Dön') }}
                </a>
                <h2 class="font-black text-2xl text-slate-900 leading-tight">
                    {{ __('Yeni Ürün Ekle') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
            <form action="{{ route('seller.products.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Temel Bilgiler -->
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                        {{ __('1. Temel Ürün Bilgileri') }}
                    </h3>

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Ürün Adı') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Örn: Apple iPhone 15 Pro 128GB Titanyum"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        @error('name') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Kategori') }} <span class="text-rose-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                                <option value="">-- Kategori Seçin --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="brand_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Marka') }}
                            </label>
                            <select name="brand_id" id="brand_id" class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                                <option value="">-- Markasız / Seçilmedi --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Ürün Görsel URL') }}
                        </label>
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-..."
                               class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        <p class="text-[11px] text-slate-400 mt-1">{{ __('Boş bırakırsanız varsayılan şık bir ürün görseli atanır.') }}</p>
                        @error('image_url') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Ürün Açıklaması') }}
                        </label>
                        <textarea name="description" id="description" rows="4" placeholder="Ürün özellikleri, kullanım alanları ve garanti detayları..."
                                  class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Varyant, Fiyat ve Stok -->
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                        {{ __('2. Fiyat, Stok ve Varyant Bilgileri') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Satış Fiyatı (TL)') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required placeholder="1499.90"
                                   class="w-full text-sm font-bold text-slate-800 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('price') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="discount_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('İndirimli Fiyat (Opsiyonel)') }}
                            </label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" value="{{ old('discount_price') }}" placeholder="1299.90"
                                   class="w-full text-sm font-bold text-indigo-600 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('discount_price') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Stok Adedi') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 10) }}" min="0" required placeholder="50"
                                   class="w-full text-sm font-mono font-bold text-slate-800 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('stock') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="sku" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('SKU (Stok Kodu)') }}
                            </label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" placeholder="PRD-001"
                                   class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            <p class="text-[10px] text-slate-400 mt-1">Boş bırakılırsa otomatik üretilir.</p>
                        </div>

                        <div>
                            <label for="color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Renk') }}
                            </label>
                            <input type="text" name="color" id="color" value="{{ old('color') }}" placeholder="Örn: Siyah, Beyaz, Mavi"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        </div>

                        <div>
                            <label for="size" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Beden / Boyut') }}
                            </label>
                            <input type="text" name="size" id="size" value="{{ old('size') }}" placeholder="Örn: M, L, 128GB, 256GB"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('seller.products.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                        {{ __('İptal') }}
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-check mr-1.5"></i>
                        {{ __('Ürünü Kaydet ve Yayınla') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
