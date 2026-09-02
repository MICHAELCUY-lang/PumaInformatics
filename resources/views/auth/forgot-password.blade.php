<x-guest-layout title="Reset password">
    <header class="mb-10">
        <p class="text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
            Members Area
        </p>
        <h1 class="font-serif text-4xl text-jp-indigo mb-3">Reset your password</h1>
        <p class="text-jp-indigo/50 font-light leading-relaxed">
            Enter your email and we will send you a reset link.
        </p>
    </header>

    <div class="mb-4 text-sm text-jp-indigo/60">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
