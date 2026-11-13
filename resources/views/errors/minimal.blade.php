<!DOCTYPE html>
<html lang="tr" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - AlSepete</title>

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col justify-between text-slate-800 antialiased selection:bg-indigo-600 selection:text-white">

    <!-- Basit Üst Bar -->
    <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/30 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-slate-900 to-indigo-900 bg-clip-text text-transparent">
                    AlSepete
                </span>
                <span class="text-[9px] font-bold text-indigo-600 -mt-1 tracking-widest uppercase">
                    Pazaryeri
                </span>
            </div>
        </a>

        <div class="flex items-center gap-3 text-xs font-semibold text-slate-600">
            <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-house mr-1"></i> {{ __('Ana Sayfa') }}
            </a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('login') }}" class="hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> {{ __('Giriş Yap') }}
            </a>
        </div>
    </header>

    <!-- Ana Hata Kartı -->
    <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-lg w-full text-center">
            
            <!-- İkon & Kod Rozeti -->
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl @yield('icon_color', 'bg-indigo-50 text-indigo-600 border-indigo-200') border-2 shadow-sm mb-6 transform hover:scale-105 transition-transform">
                <i class="fa-solid @yield('icon', 'fa-triangle-exclamation') text-4xl"></i>
            </div>

            <div class="mb-3">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wider uppercase bg-slate-200/80 text-slate-700">
                    Hata Kodu: @yield('code')
                </span>
            </div>

            <!-- Başlık -->
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-3">
                @yield('headline')
            </h1>

            <!-- Açıklama -->
            <p class="text-sm text-slate-600 leading-relaxed max-w-md mx-auto mb-6">
                @yield('description')
            </p>

            <!-- Özel Sistem / Policy Mesajı (Varsa) -->
            @hasSection('custom_message')
                @if(trim($__env->yieldContent('custom_message')))
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs font-semibold flex items-start gap-3 text-left">
                        <i class="fa-solid fa-circle-info text-amber-600 text-sm mt-0.5 flex-shrink-0"></i>
                        <div class="flex-1">
                            <span class="font-bold block text-amber-950 mb-0.5">{{ __('Detaylı Açıklama:') }}</span>
                            <span>@yield('custom_message')</span>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Aksiyon Butonları -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button type="button" onclick="history.back()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>{{ __('Geri Dön') }}</span>
                </button>

                <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/25 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i>
                    <span>{{ __('Ana Sayfaya Git') }}</span>
                </a>

                @if(trim($__env->yieldContent('code')) === '403')
                    <a href="{{ route('seller.dashboard') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-store"></i>
                        <span>{{ __('Satıcı Panelim') }}</span>
                    </a>
                @endif
            </div>

            <!-- Hızlı Yardım / İpucu -->
            <div class="mt-8 pt-6 border-t border-slate-200/80 text-[11px] text-slate-400">
                Yardıma mı ihtiyacınız var? Destek için <a href="mailto:destek@alsepete.com" class="text-indigo-600 font-bold hover:underline">destek@alsepete.com</a> adresine ulaşabilirsiniz.
            </div>

        </div>
    </main>

    <!-- Basit Alt Bilgi -->
    <footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} AlSepete Pazaryeri Platformu. Tüm hakları saklıdır.
    </footer>

</body>
</html>
