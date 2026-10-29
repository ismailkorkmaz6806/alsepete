<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 lg:p-10 space-y-8">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    <i class="fa-solid fa-award text-[10px] me-1"></i> {{ __('Bonus Özellik (+2 Puan)') }}
                </span>
            </div>
            <h3 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-comments text-indigo-600"></i>
                {{ __('Satıcıya Soru Sor & Cevaplar') }}
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                {{ __('Bu ürün hakkında aklınıza takılan soruları doğrudan satıcı mağazasına sorabilirsiniz.') }}
            </p>
        </div>

        <div class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl">
            <span class="text-indigo-600 font-black text-sm">{{ count($this->questions) }}</span> {{ __('Cevaplanmış Soru') }}
        </div>
    </div>

    <!-- Soru Sorma Formu -->
    <div class="bg-slate-50/70 border border-slate-200/70 rounded-2xl p-5 space-y-4">
        @auth
            <form wire:submit.prevent="askQuestion" class="space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-800">
                    <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name }} olarak satıcıya soru sor:</span>
                </div>

                @if($success)
                    <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ __('Sorunuz satıcıya iletildi. Satıcı yanıtladığında burada görüntülenecektir.') }}</span>
                    </div>
                @endif

                <div>
                    <textarea wire:model="question" 
                              rows="3" 
                              placeholder="{{ __('Örn: Bu ürünün kumaş yapısı nedir? Hangi kargo firmasıyla gönderiyorsunuz?') }}"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 placeholder:text-slate-400 p-3"></textarea>
                    @error('question')
                        <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2">
                        <span wire:loading.remove><i class="fa-solid fa-paper-plane"></i> {{ __('Soruyu Gönder') }}</span>
                        <span wire:loading><i class="fa-solid fa-spinner animate-spin"></i> {{ __('Gönderiliyor...') }}</span>
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-4 space-y-2">
                <i class="fa-solid fa-lock text-slate-400 text-2xl"></i>
                <p class="text-xs text-slate-600 font-medium">
                    {{ __('Satıcıya soru sormak için lütfen üye girişi yapın.') }}
                </p>
                <div>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> {{ __('Giriş Yap') }}
                    </a>
                </div>
            </div>
        @endauth
    </div>

    <!-- Cevaplanmış Sorular Listesi -->
    <div class="space-y-4">
        @forelse($this->questions as $q)
            <div class="p-5 rounded-2xl border border-slate-200/80 bg-white space-y-3">
                <!-- Soru Başlığı -->
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                            <i class="fa-solid fa-question"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 leading-relaxed">{{ $q->question }}</p>
                            <span class="text-[10px] text-slate-400">{{ $q->user->name ?? __('Müşteri') }} &bull; {{ $q->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Satıcı Cevabı -->
                @if($q->answer)
                    <div class="ms-8 p-3.5 rounded-xl bg-indigo-50/50 border border-indigo-100/70 space-y-1.5">
                        <div class="flex items-center gap-2 text-[11px] font-bold text-indigo-900">
                            <i class="fa-solid fa-store text-indigo-600"></i>
                            <span>{{ $q->seller->store_name ?? __('Satıcı') }}</span>
                            <span class="text-[9px] font-extrabold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">{{ __('Mağaza Cevabı') }}</span>
                            <span class="text-[10px] text-slate-400 font-normal ms-auto">{{ $q->answered_at ? $q->answered_at->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            {{ $q->answer }}
                        </p>
                    </div>
                @else
                    <div class="ms-8 text-[11px] text-amber-600 italic flex items-center gap-1.5">
                        <i class="fa-solid fa-clock"></i>
                        <span>{{ __('Satıcının cevabı bekleniyor...') }}</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-slate-400 text-xs">
                <i class="fa-regular fa-comment-dots text-3xl mb-2 text-slate-300 block"></i>
                {{ __('Bu ürün hakkında henüz soru sorulmamış. İlk soruyu siz sorun!') }}
            </div>
        @endforelse
    </div>

</div>
