<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 mb-1">
                    <i class="fa-solid fa-crown text-[10px]"></i> {{ __('Sistem Yöneticisi') }}
                </span>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-indigo-600"></i>
                    {{ __('Admin Yönetim Paneli') }}
                </h2>
            </div>

            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-500/20 hover:from-indigo-700 hover:to-violet-700 transition-all">
                <i class="fa-solid fa-layer-group"></i>
                <span>{{ __('Kategori Yönetimi') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Kategoriler (Hafta 2 Çıktısı) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Toplam Kategori') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ \App\Models\Category::count() }}
                </div>
                <a href="{{ route('admin.categories.index') }}" class="text-[11px] text-indigo-600 font-bold hover:underline mt-1 block">
                    {{ __('Yönet & Düzenle') }} &rarr;
                </a>
            </div>

            <!-- Onaylı Satıcılar -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Onaylı Satıcılar') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-store text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ \App\Models\Seller::where('status', 'onayli')->count() }}
                </div>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 block">{{ __('Aktif Mağaza') }}</span>
            </div>

            <!-- Müşteriler -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Kayıtlı Kullanıcılar') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <i class="fa-solid fa-users text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ \App\Models\User::count() }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ __('Tüm Roller Dahil') }}</span>
            </div>

            <!-- Ürünler -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Vitrindeki Ürünler') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ \App\Models\Product::count() }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ __('Hafta 1 Seed Verileri') }}</span>
            </div>
        </div>

        <!-- Hafta 2 Görev Kutusu -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <h3 class="font-extrabold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-indigo-600"></i> {{ __('Hafta 2 Teslimatı Tamamlanan Modüller') }}
                </span>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">{{ __('Tamamlandı') }}</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-2">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ __('Kategori CRUD & Ağacı') }}</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">
                        {{ __('Admin için alt kategori (parent_id), komisyon oranı ve sıra destekli yönetim paneli.') }}
                    </p>
                    <a href="{{ route('admin.categories.index') }}" class="text-indigo-600 font-bold hover:underline text-[11px] inline-block">
                        {{ __('Kategorileri Yönet') }} &rarr;
                    </a>
                </div>

                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-2">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ __('Rol Bazlı Middleware') }}</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">
                        {{ __('Customer kullanıcılar için 403 Forbidden engeli; Onaylı satıcılar için seller.approved koruması.') }}
                    </p>
                </div>

                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-2">
                    <div class="font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span>{{ __('Dinamik Mega Menü & Dil') }}</span>
                    </div>
                    <p class="text-slate-500 text-[11px]">
                        {{ __('Veritabanından beslenen dinamik hiyerarşik mega menü ve TR/EN çoklu dil desteği.') }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
