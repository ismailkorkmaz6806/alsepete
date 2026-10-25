<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98] transition-all shadow-md shadow-indigo-500/15 cursor-pointer']) }}>
    {{ $slot }}
</button>
