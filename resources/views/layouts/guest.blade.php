<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AlSepete') }}</title>

        <!-- Tailwind CSS CDN & Fonts & FontAwesome -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .subtle-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
                    radial-gradient(at 90% 15%, rgba(14, 165, 233, 0.07) 0px, transparent 50%),
                    radial-gradient(at 50% 90%, rgba(168, 85, 247, 0.06) 0px, transparent 50%);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="subtle-mesh text-slate-800 min-h-screen antialiased flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-indigo-600 selection:text-white">
        
        <!-- Logo & Başlık -->
        <div class="mb-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-basket-shopping text-lg"></i>
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-2xl font-black tracking-tight text-slate-900 leading-none">
                        Al<span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Sepete</span>
                    </span>
                    <span class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mt-1">{{ __('Çok Satıcılı Platform') }}</span>
                </div>
            </a>
        </div>

        <!-- Kart Alanı -->
        <div class="w-full sm:max-w-md bg-white border border-slate-200/80 shadow-xl shadow-slate-200/50 rounded-3xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <!-- Alt Bilgi / Ana Sayfaya Dönüş -->
        <div class="mt-6 text-center text-xs text-slate-500">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 font-semibold transition-colors">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Ana Sayfaya Geri Dön
            </a>
        </div>
    </body>
</html>
