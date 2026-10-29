<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-award text-[10px] me-1"></i> {{ __('Bonus Modül (+2 Puan)') }}
                    </span>
                    <span class="text-xs text-slate-400">|</span>
                    <span class="text-xs text-slate-500 font-medium">{{ Auth::user()->seller->store_name }}</span>
                </div>
                <h2 class="font-black text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-comments text-indigo-600"></i>
                    {{ __('Müşteri Soru & Cevap Yönetimi') }}
                </h2>
            </div>

            <a href="{{ route('seller.dashboard') }}" class="text-xs font-bold text-slate-600 hover:text-indigo-600 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-xs transition-colors">
                &larr; {{ __('Satıcı Paneline Dön') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-sm text-slate-900">{{ __('Mağazanıza Gelen Sorular') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('Müşterilerin ürünleriniz hakkında sorduğu soruları buradan yanıtlayabilirsiniz.') }}</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                    {{ $questions->total() }} {{ __('Toplam Soru') }}
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($questions as $q)
                    <div class="p-6 space-y-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                            <!-- İlgili Ürün -->
                            <a href="{{ route('products.show', $q->product->slug) }}" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-2">
                                <i class="fa-solid fa-box text-slate-400"></i>
                                <span>{{ $q->product->name }}</span>
                            </a>
                            <span class="text-[11px] text-slate-400">
                                {{ $q->created_at->format('d.m.Y H:i') }} &bull; {{ $q->user->name ?? 'Müşteri' }}
                            </span>
                        </div>

                        <!-- Soru Metni -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 text-xs text-slate-800 font-medium">
                            <span class="font-bold text-slate-500 block mb-1">Müşteri Sorusu:</span>
                            {{ $q->question }}
                        </div>

                        <!-- Mevcut Cevap veya Cevaplama Formu -->
                        @if($q->answer)
                            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100 text-xs text-slate-700 space-y-1">
                                <div class="flex items-center justify-between font-bold text-emerald-800">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ __('Verilen Cevap') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-normal">{{ $q->answered_at ? $q->answered_at->format('d.m.Y H:i') : '' }}</span>
                                </div>
                                <p class="text-slate-800 leading-relaxed">{{ $q->answer }}</p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('seller.questions.update', $q->id) }}" class="space-y-3 pt-2">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        {{ __('Cevabınız:') }}
                                    </label>
                                    <textarea name="answer" 
                                              rows="2" 
                                              required
                                              placeholder="{{ __('Müşterinin sorusunu detaylı ve profesyonel bir dille yanıtlayın...') }}"
                                              class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 p-3"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-reply"></i>
                                        <span>{{ __('Cevapla & Yayınla') }}</span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-xs space-y-2">
                        <i class="fa-solid fa-inbox text-3xl text-slate-300 block"></i>
                        <p class="font-semibold text-slate-600">{{ __('Mağazanıza yöneltilen henüz bir soru bulunmuyor.') }}</p>
                    </div>
                @endforelse
            </div>

            @if($questions->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
