<div x-data="{
        isOpen: false,
        scrollToBottom() {
            $nextTick(() => {
                const container = this.$refs.chatBody;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    }" 
    x-init="
        window.addEventListener('scroll-chat-to-bottom', () => scrollToBottom());
    "
    class="relative">

    <!-- FLOATING LAUNCHER BUTTON -->
    <div class="fixed bottom-6 right-6 z-50">
        <button 
            type="button"
            @click="isOpen = !isOpen; if(isOpen) scrollToBottom();"
            class="group relative flex items-center gap-3 px-5 py-3.5 bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 border border-white/20 cursor-pointer">
            
            <!-- Glowing Ping Ring Animation -->
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-white"></span>
            </span>

            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-amber-300">
                <i class="fa-solid fa-wand-magic-sparkles text-sm group-hover:rotate-12 transition-transform"></i>
            </div>
            
            <div class="text-left hidden sm:block">
                <div class="text-[11px] font-medium text-indigo-100 uppercase tracking-wider leading-none">AlSepete</div>
                <div class="text-sm font-bold text-white leading-tight">{{ __('AI Asistanı') }}</div>
            </div>

            <i class="fa-solid fa-chevron-up text-xs text-indigo-200 transition-transform duration-300" :class="isOpen ? 'rotate-180' : ''"></i>
        </button>
    </div>

    <!-- FLOATING CHAT WINDOW -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        x-cloak
        class="fixed bottom-24 right-4 sm:right-6 w-[92vw] sm:w-[420px] h-[580px] max-h-[82vh] bg-white rounded-3xl shadow-2xl border border-slate-200/90 flex flex-col z-50 overflow-hidden backdrop-blur-md">

        <!-- HEADER -->
        <div class="px-5 py-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-indigo-900 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="relative w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-inner border border-white/20 text-white">
                    <i class="fa-solid fa-robot text-lg"></i>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base leading-snug flex items-center gap-1.5">
                        {{ __('AlSepete AI Danışman') }}
                        <span class="text-[10px] bg-indigo-500/30 text-indigo-300 font-semibold px-2 py-0.5 rounded-full border border-indigo-400/20">PRO</span>
                    </h3>
                    <p class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ __('Çevrimiçi • Katalog Uzmanı') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1 text-slate-300">
                <button 
                    type="button" 
                    wire:click="resetChat" 
                    title="{{ __('Sohbeti Sıfırla') }}"
                    class="p-2 hover:bg-white/10 rounded-xl transition text-slate-300 hover:text-white cursor-pointer">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                </button>
                <button 
                    type="button" 
                    @click="isOpen = false"
                    title="{{ __('Kapat') }}"
                    class="p-2 hover:bg-white/10 rounded-xl transition text-slate-300 hover:text-white cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- CHAT BODY / MESSAGES -->
        <div 
            x-ref="chatBody"
            class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50/60 scroll-smooth">
            
            @foreach($messages as $msg)
                @if($msg['role'] === 'user')
                    <!-- USER MESSAGE -->
                    <div class="flex justify-end items-end gap-2">
                        <div class="max-w-[85%] bg-indigo-600 text-white rounded-2xl rounded-br-none px-4 py-3 shadow-md shadow-indigo-600/10">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $msg['content'] }}</p>
                            <span class="block text-[10px] text-indigo-200 mt-1 text-right">{{ $msg['time'] }}</span>
                        </div>
                    </div>
                @else
                    <!-- ASSISTANT MESSAGE -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex-shrink-0 flex items-center justify-center text-xs shadow-sm mt-1">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="max-w-[88%] space-y-3">
                            <div class="bg-white text-slate-800 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm border border-slate-200/80">
                                <div class="text-sm leading-relaxed space-y-2 prose-sm">
                                    {!! nl2br(e($msg['content'])) !!}
                                </div>
                                <span class="block text-[10px] text-slate-400 mt-1.5">{{ $msg['time'] }}</span>
                            </div>

                            <!-- RECOMMENDED PRODUCT CARDS (IF ANY) -->
                            @if(!empty($msg['products']))
                                <div class="space-y-2 pt-1">
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1 px-1">
                                        <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                                        {{ __('Önerilen Katalog Ürünleri') }} ({{ count($msg['products']) }})
                                    </div>

                                    @foreach($msg['products'] as $item)
                                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all group flex items-center gap-3">
                                            <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-100 relative">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5 mb-1">
                                                    <span class="text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">{{ $item['brand'] }}</span>
                                                    <span class="text-[10px] text-slate-400 truncate">{{ __($item['category']) }}</span>
                                                </div>
                                                <h4 class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">
                                                    {{ $item['name'] }}
                                                </h4>
                                                <div class="flex items-center justify-between mt-1.5">
                                                    <span class="text-xs font-black text-indigo-700">{{ $item['price'] }}</span>
                                                    <a href="{{ $item['url'] }}" 
                                                       class="text-[11px] font-semibold text-white bg-slate-900 hover:bg-indigo-600 px-3 py-1 rounded-lg transition-colors shadow-sm flex items-center gap-1">
                                                        {{ __('İncele') }}
                                                        <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- TYPING / LOADING INDICATOR -->
            <div wire:loading wire:target="sendMessage, quickAsk" class="flex items-center gap-2 text-slate-400 pl-10">
                <div class="bg-white border border-slate-200 px-4 py-2.5 rounded-2xl rounded-tl-none shadow-sm flex items-center gap-1.5">
                    <span class="text-xs text-slate-500 font-medium">{{ __('AlSepete AI kataloğu inceliyor') }}</span>
                    <span class="flex gap-1 items-center ml-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.2s]"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-bounce [animation-delay:0.4s]"></span>
                    </span>
                </div>
            </div>
        </div>

        <!-- QUICK PROMPTS CHIPS -->
        <div class="px-4 py-2 bg-slate-100/80 border-t border-slate-200/60 overflow-x-auto whitespace-nowrap scrollbar-none flex items-center gap-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex-shrink-0">{{ __('Hızlı Sor:') }}</span>
            @foreach($quickPrompts as $prompt)
                <button 
                    type="button" 
                    wire:click="quickAsk('{{ addslashes($prompt) }}')"
                    wire:loading.attr="disabled"
                    class="text-xs bg-white hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-300 text-slate-700 px-3 py-1 rounded-full border border-slate-200/80 transition-colors shadow-2xs flex-shrink-0 flex items-center gap-1 active:scale-95 cursor-pointer">
                    {{ $prompt }}
                </button>
            @endforeach
        </div>

        <!-- INPUT FORM -->
        <form wire:submit="sendMessage" class="p-3 bg-white border-t border-slate-200">
            <div class="flex items-center gap-2">
                <input 
                    type="text" 
                    wire:model="userInput" 
                    placeholder="{{ __('Ürün, marka veya bütçe belirtin...') }}" 
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition"
                    wire:loading.attr="disabled"
                />
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="w-10 h-10 rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white flex items-center justify-center transition shadow-md shadow-indigo-600/20 disabled:opacity-50 cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </div>
            <div class="text-[10px] text-center text-slate-400 mt-2 font-medium">
                ⚡ {{ __('AlSepete Akıllı Semantik Arama & Katalog Motoru') }}
            </div>
        </form>
    </div>
</div>
