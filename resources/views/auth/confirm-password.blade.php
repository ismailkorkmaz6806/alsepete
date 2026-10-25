<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('Şifrenizi Onaylayın') }}</h2>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
