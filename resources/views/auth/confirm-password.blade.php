<x-guest-layout title="Confirm password">
    <header class="mb-10">
        <p class="text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
            Security Check
        </p>
        <h1 class="font-serif text-4xl text-jp-indigo mb-3">Confirm your password</h1>
        <p class="text-jp-indigo/50 font-light leading-relaxed">
            This area is sensitive. Please confirm it is you.
        </p>
    </header>

    <div class="mb-4 text-sm text-jp-indigo/60">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
