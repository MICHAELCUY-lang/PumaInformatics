<x-guest-layout title="Verify email">
    <header class="mb-10">
        <p class="text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
            One More Step
        </p>
        <h1 class="font-serif text-4xl text-jp-indigo mb-3">Verify your email</h1>
        <p class="text-jp-indigo/50 font-light leading-relaxed">
            Voting is limited to verified members, so this step is required before an election.
        </p>
    </header>

    <div class="mb-4 text-sm text-jp-indigo/60">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-jp-gold">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-jp-indigo/60 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jp-gold/40">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
