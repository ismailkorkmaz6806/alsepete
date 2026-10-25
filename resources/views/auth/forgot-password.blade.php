<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Şifremi Unuttum</h2>
        <p class="text-xs text-slate-500 mt-1">E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-Posta Adresi" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="ornek@pazaryeri.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Sıfırlama Bağlantısı Gönder
            </x-primary-button>
        </div>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Hatırladınız mı?
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline ms-1">
                Giriş Yapın
            </a>
        </div>
    </form>
</x-guest-layout>
