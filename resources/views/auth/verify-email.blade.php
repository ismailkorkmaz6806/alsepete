<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shadow-sm">
            <i class="fa-regular fa-envelope-open"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('E-Posta Doğrulama') }}</h2>
        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
            {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.") }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl font-medium text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-3 pt-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 font-semibold transition-colors">
                <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
