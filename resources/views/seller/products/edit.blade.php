<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('seller.products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline mb-1 inline-flex items-center gap-1">
                    &larr; {{ __('Ürünlerime Geri Dön') }}
                </a>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-indigo-600"></i>
                    {{ __('Ürünü Düzenle') }}: <span class="text-slate-600 font-bold text-lg">{{ Str::limit($product->name, 35) }}</span>
                </h2>
            </div>

            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 font-bold text-xs rounded-xl shadow-xs transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>{{ __('Vitrinde Gör') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
            <form action="{{ route('seller.products.update', $product) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Temel Bilgiler -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">
                            {{ __('1. Temel Ürün Bilgileri') }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <label for="status" class="text-xs font-bold text-slate-600">{{ __('Durum:') }}</label>
                            <select name="status" id="status" class="text-xs font-bold rounded-lg border-slate-200 py-1 px-2.5">
                                <option value="aktif" {{ old('status', $product->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="pasif" {{ old('status', $product->status) === 'pasif' ? 'selected' : '' }}>Pasif</option>
                                <option value="taslak" {{ old('status', $product->status) === 'taslak' ? 'selected' : '' }}>Taslak</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Ürün Adı') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        @error('name') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Kategori') }} <span class="text-rose-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" required class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $product->image_url) }}"
                               class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        @error('image_url') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Ürün Açıklaması') }}
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">{{ old('description', $product->description) }}</textarea>
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
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $primaryVariant->price ?? '') }}" required
                                   class="w-full text-sm font-bold text-slate-800 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('price') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="discount_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('İndirimli Fiyat (Opsiyonel)') }}
                            </label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" value="{{ old('discount_price', $primaryVariant->discount_price ?? '') }}"
                                   class="w-full text-sm font-bold text-indigo-600 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('discount_price') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Stok Adedi') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $primaryVariant->stock ?? 0) }}" min="0" required
                                   class="w-full text-sm font-mono font-bold text-slate-800 rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('stock') <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="sku" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('SKU (Stok Kodu)') }}
                            </label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $primaryVariant->sku ?? '') }}"
                                   class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        </div>

                        <div>
                            <label for="color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Renk') }}
                            </label>
                            <input type="text" name="color" id="color" value="{{ old('color', $primaryVariant->color ?? '') }}"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        </div>

                        <div>
                            <label for="size" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Beden / Boyut') }}
                            </label>
                            <input type="text" name="size" id="size" value="{{ old('size', $primaryVariant->size ?? '') }}"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('seller.products.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                        {{ __('İptal') }}
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                        {{ __('Değişiklikleri Kaydet') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
