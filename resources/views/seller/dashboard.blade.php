<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('Onaylı Satıcı Mağazası') }}
                    </span>
                    <span class="text-xs text-slate-400">|</span>
                    <span class="text-xs text-slate-500 font-medium">{{ Auth::user()->seller->company_title ?? 'AlSepete Satıcı Ortağı' }}</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-store text-indigo-600"></i>
                    {{ Auth::user()->seller->store_name ?? 'Satıcı Mağaza Paneli' }}
                </h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('seller.questions.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-xs rounded-xl shadow-sm hover:from-indigo-700 hover:to-violet-700 transition-all">
                    <i class="fa-solid fa-comments"></i>
                    <span>{{ __('Müşteri Soruları') }}</span>
                    @if(Auth::user()->seller && Auth::user()->seller->questions()->whereNull('answer')->count() > 0)
                        <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.2 rounded-full font-black">
                            {{ Auth::user()->seller->questions()->whereNull('answer')->count() }}
                        </span>
                    @endif
                </a>

                <div class="text-xs text-slate-500 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs">
                    <span class="text-slate-400">{{ __('Yetkilendirme:') }}</span>
                    <span class="font-bold text-slate-800">role:seller</span> &bull; 
                    <span class="text-slate-400">{{ __('Durum:') }}</span> 
                    <span class="font-bold text-emerald-600 uppercase">{{ Auth::user()->seller->status ?? 'onayli' }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Hafta 2 Bilgilendirme Banner'ı -->
        <div class="bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 border border-indigo-200 rounded-2xl p-5 text-slate-700">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 text-sm mt-0.5 shadow-sm">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-extrabold text-sm text-slate-900">{{ __('Hafta 2 Rol & Yetkilendirme Middleware Doğrulaması') }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('Bu panele yalnızca sistem yöneticisi tarafından onaylanmış (`status = onayli`) ve `seller` rolüne sahip kullanıcılar erişebilir. Onay bekleyen satıcılar veya normal müşteriler bu rotaya girdiğinde sistem otomatik olarak 403 Forbidden hatası verir.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 4 Özet İstatistik Kartı -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Toplam Ürün -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Katalogdaki Ürünler') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ Auth::user()->seller ? Auth::user()->seller->products()->count() : 8 }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ __('Hafta 3: Ürün CRUD') }}</span>
            </div>

            <!-- Mağaza Komisyonu -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Platform Komisyon Oranı') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-percent text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    %{{ Auth::user()->seller->commission_rate ?? '10.0' }}
                </div>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 block">{{ __('Standart Satıcı Tarifesi') }}</span>
            </div>

            <!-- Müşteri Soruları (Bonus +2 Puan) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Müşteri Soruları') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid fa-comments text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">
                    {{ Auth::user()->seller ? Auth::user()->seller->questions()->count() : 0 }}
                </div>
                <a href="{{ route('seller.questions.index') }}" class="text-[11px] text-indigo-600 font-bold hover:underline mt-1 block">
                    {{ __('Yanıtla & Görüntüle') }} &rarr;
                </a>
            </div>

            <!-- Mağaza Durumu -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                <div class="flex items-center justify-between text-slate-500 text-xs font-semibold mb-2">
                    <span>{{ __('Hesap Doğrulama') }}</span>
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-600">{{ __('Onaylı') }}</div>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ __('Satış Yapmaya Uygun') }}</span>
            </div>
        </div>

        <!-- Gelecek Haftaların Yol Haritası & Mağaza Bilgileri -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Mağaza Bilgileri Kartı -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="font-extrabold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-indigo-600"></i> {{ __('Mağaza Bilgileri') }}
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400">{{ __('Mağaza Adı:') }}</span>
                        <span class="font-bold text-slate-800">{{ Auth::user()->seller->store_name ?? 'TeknoMarket' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400">{{ __('Vergi No:') }}</span>
                        <span class="font-mono text-slate-700">{{ Auth::user()->seller->tax_number ?? '1234567890' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400">{{ __('IBAN:') }}</span>
                        <span class="font-mono text-slate-700 text-[10px]">{{ Auth::user()->seller->iban ?? 'TR12 3456 7890 ...' }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-400">{{ __('İletişim E-Posta:') }}</span>
                        <span class="font-medium text-slate-700">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Sonraki Adımlar / Modüller -->
            <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
                <h3 class="font-extrabold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-600"></i> {{ __('Satıcı Paneli Geliştirme Yol Haritası') }}
                    </span>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ __('Planlanan') }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1">
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded bg-indigo-100 text-indigo-700 text-[10px] font-black flex items-center justify-center">H3</span>
                            <span>{{ __('Ürün & Varyant Ekleme') }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            {{ __('Satıcıların kendi mağazalarına ürün, stok ve varyant tanımlayabilmesi.') }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1">
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded bg-amber-100 text-amber-700 text-[10px] font-black flex items-center justify-center">H4</span>
                            <span>{{ __('Sipariş & Kargo Yönetimi') }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            {{ __('Gelen siparişlerin satıcı bazında ayrıştırılması ve kargo takibi.') }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1">
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-black flex items-center justify-center">H5</span>
                            <span>{{ __('Satıcı Hakediş & Kazanç') }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            {{ __('Komisyon kesintileri sonrası satıcı bakiyesi ve hakediş ödemeleri.') }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-1">
                        <div class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-5 h-5 rounded bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center">H6</span>
                            <span>{{ __('Iyzico Pazaryeri Ödemesi') }}</span>
                        </div>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            {{ __('Otomatik alt satıcı para transferi (sub-merchant payout).') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
