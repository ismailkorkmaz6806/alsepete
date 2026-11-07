<x-app-layout>
    <div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 mb-3 border border-indigo-100">
                <i class="fa-solid fa-store"></i> {{ __('AlSepete Pazaryeri Ortaklığı') }}
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                {{ __('Mağazanızı Açın, Milyonlarca Müşteriye Ulaşın') }}
            </h1>
            <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                {{ __('AlSepete çok satıcılı e-ticaret platformunda yerinizi alın. Kolay başvuru, şeffaf komisyon ve anında satış imkanı.') }}
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-sm">{{ __('Tebrikler!') }}</h4>
                    <p class="text-xs mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($seller && $seller->status === 'beklemede')
            <!-- Başvuru Beklemede Kartı -->
            <div class="bg-white rounded-3xl border border-amber-200 shadow-sm p-8 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 mx-auto flex items-center justify-center text-2xl border border-amber-100">
                    <i class="fa-solid fa-clock-rotate-left animate-spin" style="animation-duration: 6s;"></i>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 mb-2">
                        {{ __('Durum: Beklemede') }}
                    </span>
                    <h2 class="text-xl font-bold text-slate-900">{{ __('Satıcı Başvurunuz İnceleniyor') }}</h2>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-2 leading-relaxed">
                        <strong>{{ $seller->store_name }}</strong> {{ __('adlı mağaza başvurunuz sistem yöneticimizin onay sırasındadır. Onaylandığında e-posta alacak ve satıcı paneline anında erişebileceksiniz.') }}
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-wrap justify-center gap-4 text-xs text-slate-600">
                    <div><span class="text-slate-400">{{ __('Vergi No:') }}</span> <span class="font-mono font-bold">{{ $seller->tax_number }}</span></div>
                    <div><span class="text-slate-400">{{ __('IBAN:') }}</span> <span class="font-mono font-bold">{{ $seller->iban }}</span></div>
                    <div><span class="text-slate-400">{{ __('Tarih:') }}</span> <span class="font-bold">{{ $seller->created_at->format('d.m.Y H:i') }}</span></div>
                </div>
            </div>
        @elseif($seller && $seller->status === 'reddedildi')
            <!-- Başvuru Reddedildi Uyarısı -->
            <div class="bg-white rounded-3xl border border-rose-200 shadow-sm p-8 text-center space-y-4 mb-8">
                <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 mx-auto flex items-center justify-center text-2xl border border-rose-100">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 mb-2">
                        {{ __('Durum: Reddedildi') }}
                    </span>
                    <h2 class="text-xl font-bold text-slate-900">{{ __('Önceki Başvurunuz Onaylanmadı') }}</h2>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-2">
                        {{ __('Bilgilerinizi güncelleyerek tekrar başvurabilirsiniz.') }}
                    </p>
                </div>
            </div>
        @endif

        @if(! $seller || $seller->status !== 'beklemede')
            <!-- Başvuru Formu -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
                <form action="{{ route('seller.apply.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="store_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            {{ __('Mağaza Adı') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="store_name" id="store_name" value="{{ old('store_name', $seller->store_name ?? '') }}" required placeholder="Örn: TeknoMarket veya Butik Moda"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                        <p class="text-[11px] text-slate-400 mt-1">{{ __('Müşterilerin ürün sayfalarında göreceği resmi mağaza adınız.') }}</p>
                        @error('store_name')
                            <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="tax_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Vergi Kimlik Numarası (veya TCKN)') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number', $seller->tax_number ?? '') }}" required placeholder="10 veya 11 haneli" maxlength="11"
                                   class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            @error('tax_number')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="iban" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                {{ __('Banka IBAN Numarası') }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="iban" id="iban" value="{{ old('iban', $seller->iban ?? '') }}" required placeholder="TR00 0000 0000 0000 0000 0000 00"
                                   class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 py-2.5 px-3.5">
                            <p class="text-[11px] text-slate-400 mt-1">{{ __('Hakediş ödemelerinizin yatırılacağı banka hesabı.') }}</p>
                            @error('iban')
                                <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 text-xs text-slate-600 space-y-1">
                        <div class="font-bold text-slate-800 flex items-center gap-1.5">
                            <i class="fa-solid fa-info-circle text-indigo-600"></i> {{ __('Satıcı Şartları & Bilgilendirme') }}
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            {{ __('Başvurunuz incelendikten sonra durumunuz admin tarafından onaylanacaktır. Başvuru anında mağaza durumunuz "beklemede" olarak işaretlenir.') }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white rounded-xl font-bold text-sm shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i>
                            {{ __('Satıcı Başvurusunu Gönder') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
