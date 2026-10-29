<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-indigo-600"></i>
                    {{ __('Müşteri Kontrol Paneli') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ __('Hesap bilgilerinizi, adreslerinizi ve satıcılara sorduğunuz soruları buradan yönetebilirsiniz.') }}
                </p>
            </div>
            <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-colors flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>{{ __('Alışverişe Devam Et') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Üst İstatistik Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-bold block">{{ __('Sorduğum Sorular') }}</span>
                    <span class="text-2xl font-black text-slate-900">{{ count($myQuestions ?? []) }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-bold block">{{ __('Satıcıdan Yanıtlanan') }}</span>
                    <span class="text-2xl font-black text-emerald-600">
                        {{ collect($myQuestions ?? [])->filter(fn($q) => !empty($q->answer))->count() }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="truncate">
                    <span class="text-xs text-slate-400 font-bold block">{{ __('Varsayılan Adres') }}</span>
                    <span class="text-xs font-bold text-slate-800 truncate block">
                        {{ auth()->user()->addresses->first()?->city ?? 'İstanbul' }} / {{ auth()->user()->addresses->first()?->district ?? 'Kadıköy' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Sorduğum Sorular ve Satıcı Yanıtları Sekmesi (Kullanıcının Aradığı Bölüm) -->
        <div id="sorular" class="scroll-mt-24 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 lg:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                            <i class="fa-solid fa-award text-[10px] me-1"></i> {{ __('Hafta 3 Bonus Özellik') }}
                        </span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-messages-question text-indigo-600"></i>
                        {{ __('Sorduğum Sorular ve Satıcı Yanıtları') }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ __('Ürün detay sayfalarından satıcı mağazalarına ilettiğiniz tüm sorular ve verilen yanıtlar burada listelenir.') }}
                    </p>
                </div>

                <div class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl">
                    <span class="text-indigo-600 font-black text-sm">{{ count($myQuestions ?? []) }}</span> {{ __('Toplam Soru') }}
                </div>
            </div>

            <!-- Sorular Listesi -->
            <div class="space-y-4">
                @forelse($myQuestions ?? [] as $q)
                    <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition-colors space-y-4">
                        
                        <!-- İlgili Ürün Başlığı & Linki -->
                        @if($q->product)
                            <div class="flex items-center gap-3 pb-3 border-b border-slate-200/60">
                                <img src="{{ $q->product->featured_image }}" 
                                     alt="{{ $q->product->name }}" 
                                     class="w-12 h-12 rounded-xl object-contain bg-white border border-slate-200 p-1 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <span class="text-[10px] font-bold text-indigo-600 uppercase">{{ $q->product->category->name ?? 'Kategori' }}</span>
                                    <h4 class="text-xs font-black text-slate-900 truncate hover:text-indigo-600 transition-colors">
                                        <a href="{{ route('products.show', $q->product->slug) }}#soru-cevap">
                                            {{ $q->product->name }}
                                        </a>
                                    </h4>
                                </div>
                                <a href="{{ route('products.show', $q->product->slug) }}#soru-cevap" 
                                   class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:text-indigo-600 text-xs font-bold transition-colors shrink-0">
                                    {{ __('Ürüne Git') }} &rarr;
                                </a>
                            </div>
                        @endif

                        <!-- Müşterinin Sorusu -->
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-bold text-slate-500">{{ __('Sorunuz:') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $q->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-slate-900 mt-1 leading-relaxed">{{ $q->question }}</p>
                            </div>
                        </div>

                        <!-- Satıcının Yanıtı -->
                        @if($q->answer)
                            <div class="ms-10 p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/70 space-y-1.5">
                                <div class="flex items-center gap-2 text-[11px] font-bold text-emerald-900">
                                    <i class="fa-solid fa-store text-emerald-600"></i>
                                    <span>{{ $q->seller->store_name ?? __('Satıcı Mağazası') }}</span>
                                    <span class="text-[9px] font-black bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                                        {{ __('Satıcı Yanıtladı') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal ms-auto">
                                        {{ $q->answered_at ? $q->answered_at->diffForHumans() : '' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                    {{ $q->answer }}
                                </p>
                            </div>
                        @else
                            <div class="ms-10 p-3 rounded-xl bg-amber-50/60 border border-amber-200/70 text-xs text-amber-800 font-semibold flex items-center gap-2">
                                <i class="fa-solid fa-clock text-amber-600"></i>
                                <span>{{ __('Satıcı henüz yanıt vermedi. Yanıtlandığında burada görüntülenecektir.') }}</span>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs space-y-3">
                        <i class="fa-regular fa-comment-dots text-4xl text-slate-300 block"></i>
                        <p class="font-bold text-slate-600">{{ __('Henüz satıcılara sorduğunuz bir soru bulunmuyor.') }}</p>
                        <p class="text-slate-400 max-w-md mx-auto">
                            {{ __('Herhangi bir ürün sayfasını inceleyip altındaki "Satıcıya Soru Sor" bölümünden merak ettiğiniz detayları doğrudan satıcıya sorabilirsiniz.') }}
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/20">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>{{ __('Ürünleri İncele') }}</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
