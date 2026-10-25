@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 rounded-xl py-2.5 px-3.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-200 shadow-sm']) }}>
