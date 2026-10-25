<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kayıt Ol</h2>
        <p class="text-xs text-slate-500 mt-1">AlSepete ailesine katılarak avantajlı alışverişe başlayın.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Ad Soyad" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Adınız ve Soyadınız" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-Posta Adresi" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="ornek@alsepete.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Şifre" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="En az 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Şifre Tekrarı" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Şifrenizi tekrar girin" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Kayıt Ol ve Başla
            </x-primary-button>
        </div>

        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
            Zaten bir hesabınız var mı?
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline ms-1">
                Giriş Yapın
            </a>
        </div>
    </form>
</x-guest-layout>
