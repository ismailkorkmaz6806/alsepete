<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Giriş Yap</h2>
        <p class="text-xs text-slate-500 mt-1">Hesabınıza giriş yaparak alışverişe veya yönetime devam edin.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-Posta Adresi" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="ornek@alsepete.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Şifre" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline" href="{{ route('password.request') }}">
                        Şifremi Unuttum
                    </a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500/20" name="remember">
            <label for="remember_me" class="ms-2 text-xs text-slate-600 select-none">
                Beni hatırla
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Giriş Yap
            </x-primary-button>
        </div>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Hesabınız yok mu? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline ms-1">
                Kayıt Olun
            </a>
        </div>
    </form>

    <!-- Test Giriş Bilgileri Kutucuğu -->
    <div class="mt-6 p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl text-[11px] text-slate-600 space-y-1">
        <div class="font-bold text-slate-700 flex items-center gap-1.5 mb-1.5">
            <i class="fa-solid fa-key text-indigo-500"></i> Hızlı Test Girişleri (Şifre: <span class="font-mono text-indigo-600">password</span>)
        </div>
        <div class="grid grid-cols-1 gap-1 text-[11px]">
            <div>👑 <span class="font-semibold text-slate-700">Yönetici:</span> <span class="font-mono text-slate-500">admin@alsepete.com</span></div>
            <div>🏪 <span class="font-semibold text-slate-700">Onaylı Satıcı:</span> <span class="font-mono text-slate-500">satici1@alsepete.com</span></div>
            <div>🛒 <span class="font-semibold text-slate-700">Müşteri:</span> <span class="font-mono text-slate-500">musteri1@alsepete.com</span></div>
        </div>
    </div>
</x-guest-layout>
