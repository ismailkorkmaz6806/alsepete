<div>
    @if($size === 'card')
        <button type="button" 
                wire:click.stop="toggle" 
                title="{{ $isFavorite ? __('Favorilerden Çıkar') : __('Favorilere Ekle') }}"
                class="w-8 h-8 rounded-full bg-white/90 hover:bg-white backdrop-blur-xs flex items-center justify-center shadow-sm transition-all transform active:scale-90 {{ $isFavorite ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }}">
            <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm"></i>
        </button>
    @elseif($size === 'sm')
        <button type="button" 
                wire:click="toggle" 
                title="{{ $isFavorite ? __('Favorilerden Çıkar') : __('Favorilere Ekle') }}"
                class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center transition-all transform active:scale-90 {{ $isFavorite ? 'text-rose-500 border-rose-200 bg-rose-50/50' : 'text-slate-500 hover:text-rose-500' }}">
            <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm"></i>
        </button>
    @else
        <button type="button" 
                wire:click="toggle" 
                class="px-4 py-3.5 rounded-2xl border transition-all flex items-center justify-center gap-2 font-bold text-xs transform active:scale-95 {{ $isFavorite ? 'bg-rose-50 border-rose-200 text-rose-600 shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300 hover:text-rose-600' }}">
            <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart text-sm text-rose-500"></i>
            <span>{{ $isFavorite ? __('Favorilerimde') : __('Favorilere Ekle') }}</span>
        </button>
    @endif
</div>
