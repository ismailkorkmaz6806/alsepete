<a href="{{ route('cart.index') }}" class="flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-colors">
    <div class="relative">
        <i class="fa-solid fa-bag-shopping text-sm text-indigo-600"></i>
        <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 px-1 flex items-center justify-center shadow-xs">
            {{ $count }}
        </span>
    </div>
    <span class="hidden sm:inline">{{ __('Sepetim') }}</span>
</a>
