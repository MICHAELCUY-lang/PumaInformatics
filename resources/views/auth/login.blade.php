<x-guest-layout title="Sign in">
    <header class="mb-10">
        <p class="text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
            Members Area
        </p>
        <h1 class="font-serif text-4xl text-jp-indigo mb-3">Sign in</h1>
        <p class="text-jp-indigo/50 font-light leading-relaxed">
            Use the account you were invited with.
        </p>
    </header>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          placeholder="you@president.ac.id"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-baseline justify-between">
                <x-input-label for="password" :value="__('Password')" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-[10px] uppercase tracking-[0.2em] text-jp-indigo/40 hover:text-jp-gold transition-colors duration-500">
                        Forgot?
                    </a>
                @endif
            </div>

            <x-text-input id="password" type="password" name="password"
                          placeholder="••••••••"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 cursor-pointer select-none">
            <input id="remember_me" type="checkbox" name="remember"
                   class="rounded border-jp-indigo/25 text-jp-indigo shadow-sm focus:ring-jp-gold/40">
            <span class="text-sm text-jp-indigo/60">{{ __('Keep me signed in') }}</span>
        </label>

        <x-primary-button>{{ __('Sign in') }}</x-primary-button>
    </form>
</x-guest-layout>
