<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider">{{ $seller->store_name }}</span>
                    <span class="text-xs text-slate-400">|</span>
                    <span class="text-xs text-slate-500 font-medium">{{ __('Mağaza Ürünleri') }}</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-indigo-600"></i>
                    {{ __('Ürün Yönetimi & Stok') }}
                </h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 hover:from-indigo-700 hover:to-violet-700 transition-all cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span>{{ __('Yeni Ürün Ekle') }}</span>
                </a>
                <a href="{{ route('stores.show', $seller->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 font-bold text-xs rounded-xl shadow-xs transition-all">
                    <i class="fa-solid fa-store"></i>
                    <span>{{ __('Mağaza Vitrinini Gör') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Arama ve Durum Filtresi Çubuğu -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('seller.products.index') }}" method="GET" class="flex-1 w-full flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Ürün adı veya anahtar kelime ara...') }}" 
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2 pl-9 pr-3">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                    {{ __('Ara') }}
                </button>
                @if(request('q') || request('status'))
                    <a href="{{ route('seller.products.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 font-bold">
                        {{ __('Sıfırla') }}
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-1.5 text-xs font-bold w-full sm:w-auto justify-end">
                <span class="text-slate-400 mr-1">{{ __('Filtre:') }}</span>
                <a href="{{ route('seller.products.index') }}" class="px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ __('Tümü') }}
                </a>
                <a href="{{ route('seller.products.index', ['status' => 'aktif']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ __('Aktif') }}
                </a>
                <a href="{{ route('seller.products.index', ['status' => 'pasif']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'pasif' ? 'bg-rose-50 text-rose-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ __('Pasif') }}
                </a>
            </div>
        </div>

        <!-- Ürünler Tablosu -->
        <div class="bg-white overflow-hidden shadow-xs sm:rounded-2xl border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Ürün Bilgisi</th>
                            <th class="px-6 py-4">Kategori & Marka</th>
                            <th class="px-6 py-4">Fiyat</th>
                            <th class="px-6 py-4">Hızlı Stok Güncelle</th>
                            <th class="px-6 py-4">Durum (Aktif/Pasif)</th>
                            <th class="px-6 py-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($products as $product)
                            @php $variant = $product->variants->first(); @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shrink-0">
                                        <div>
                                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition flex items-center gap-1.5">
                                                <span>{{ Str::limit($product->name, 45) }}</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                                            </a>
                                            <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                                SKU: {{ $variant->sku ?? 'YOK' }}
                                                @if($variant && $variant->color) &bull; {{ $variant->color }} @endif
                                                @if($variant && $variant->size) &bull; {{ $variant->size }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                    <span class="block text-[11px] text-slate-400 mt-1 font-semibold">
                                        {{ $product->brand->name ?? 'Markasız' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($variant && $variant->discount_price)
                                        <div class="font-bold text-indigo-600 text-sm">
                                            {{ number_format($variant->discount_price, 2, ',', '.') }} TL
                                        </div>
                                        <div class="text-[10px] text-slate-400 line-through">
                                            {{ number_format($variant->price, 2, ',', '.') }} TL
                                        </div>
                                    @elseif($variant)
                                        <div class="font-bold text-slate-900 text-sm">
                                            {{ number_format($variant->price, 2, ',', '.') }} TL
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('seller.products.stock', $product) }}" method="POST" class="flex items-center gap-1.5">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="stock" value="{{ $variant->stock ?? 0 }}" min="0" 
                                               class="w-20 text-xs font-mono font-bold rounded-lg border-slate-200 py-1 px-2 focus:ring-1 focus:ring-indigo-600">
                                        <button type="submit" title="Stoğu Kaydet" class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-bold transition">
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </form>
                                    @if(($variant->stock ?? 0) <= 0)
                                        <span class="text-[10px] font-bold text-rose-600 mt-0.5 block">{{ __('Tükendi') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('seller.products.status', $product) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase transition cursor-pointer {{ $product->status === 'aktif' ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $product->status === 'aktif' ? __('Aktif') : __('Pasif') }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('seller.products.edit', $product) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Düzenle">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Bu ürünü mağazanızdan silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Sil">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                                    {{ __('Henüz listelenmiş bir ürününüz bulunmuyor.') }}
                                    <div class="mt-3">
                                        <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700">
                                            + {{ __('İlk Ürününüzü Ekleyin') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
