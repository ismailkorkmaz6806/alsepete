<a href="{{ route('favorites.index') }}" class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-600 hover:text-rose-500 transition-colors flex items-center justify-center" title="{{ __('Favorilerim') }}">
    <i class="fa-regular fa-heart text-lg"></i>
    @if($count > 0)
        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 px-1 flex items-center justify-center shadow-xs">
            {{ $count }}
        </span>
    @endif
</a>
