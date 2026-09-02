@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \App\Models\VotingSession $session */
    /** @var int $totalVotes */
    /** @var bool $hasVoted */
    /** @var bool $showResults */
    /** @var \App\Models\Vote|null $userVote */
@endphp

@section('title', $session->title . ' — Election')
@section('meta_description', Str::limit($session->description ?? 'Cast your vote in the ' . $session->title . ' election.', 150))

@section('content')
<div class="bg-jp-cream min-h-screen">

    {{-- Session Header --}}
    <section class="pt-32 pb-16 bg-white relative">
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ shown: false }" x-intersect.once="shown = true">
            <a href="{{ route('public.voting.index') }}" class="inline-flex items-center gap-2 text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40 hover:text-jp-gold transition-colors mb-10 reveal" :class="shown ? 'active' : ''">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All Elections
            </a>

            <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                <div class="reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    <div class="flex items-center gap-3 mb-4">
                        @if($session->isOpenForVoting())
                            <span class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.25em] text-green-600">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                Live Election
                            </span>
                        @elseif($session->isUpcoming())
                            <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-jp-indigo/40">Upcoming</span>
                        @else
                            <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-jp-indigo/40">Election Closed</span>
                        @endif
                    </div>
                    <h1 class="font-serif text-4xl md:text-6xl text-jp-indigo leading-tight">{{ $session->title }}</h1>
                    @if($session->description)
                        <p class="text-jp-indigo/50 mt-4 text-lg font-light leading-relaxed max-w-2xl">{{ $session->description }}</p>
                    @endif
                </div>

                {{-- Election Stats --}}
                <div class="flex-shrink-0 bg-jp-cream border border-jp-indigo/10 p-8 min-w-[220px] reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <div class="space-y-6">
                        <div>
                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Opens</span>
                            <span class="text-jp-indigo font-medium text-sm">{{ $session->start_date->format('M j, Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Closes</span>
                            <span class="text-jp-indigo font-medium text-sm">{{ $session->end_date->format('M j, Y') }}</span>
                        </div>
                        <div class="pt-4 border-t border-jp-indigo/10">
                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Total Votes Cast</span>
                            <span class="font-serif text-3xl text-jp-indigo">{{ $totalVotes }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Status Alerts --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        @if(session('success'))
            <div class="mb-8 p-6 bg-jp-indigo-deep text-jp-cream flex items-start gap-4 shadow-wave">
                <svg class="w-6 h-6 text-jp-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold mb-1">Vote Recorded</p>
                    <p class="text-jp-cream/70 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-6 bg-red-50 border border-red-200 text-red-800 flex items-start gap-4">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-semibold mb-1">Unable to Record Vote</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($hasVoted)
            <div class="mb-8 p-6 bg-jp-gold/10 border border-jp-gold/30 flex items-start gap-4">
                <svg class="w-6 h-6 text-jp-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <div>
                    <p class="font-semibold text-jp-indigo mb-1">Your Vote Is Recorded</p>
                    <p class="text-jp-indigo/50 text-sm">You have already participated in this election. Your vote is permanently recorded in the immutable ledger.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Candidates --}}
    <section class="py-16 pb-32">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-16" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex items-center gap-3 mb-3 reveal" :class="shown ? 'active' : ''">
                    <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
                    <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">
                        {{ $session->candidates->count() }} {{ Str::plural('Candidate', $session->candidates->count()) }}
                    </span>
                </div>
                <h2 class="font-serif text-3xl text-jp-indigo reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    {{ $session->isOpenForVoting() && !$hasVoted ? 'Select Your Candidate' : 'The Candidates' }}
                </h2>
            </div>

            @if($session->candidates->isEmpty())
                <div class="text-center py-16">
                    <p class="font-serif text-xl text-jp-indigo/40 italic">Candidates have not been announced yet.</p>
                </div>
            @else
                {{-- Voting Form (Active + Not voted + Logged In) --}}
                @if($session->isOpenForVoting() && !$hasVoted && auth()->check())
                    <form action="{{ route('voting.store', $session->slug) }}" method="POST" x-data="{ selectedCandidate: null }">
                        @csrf
                        <input type="hidden" name="fingerprint" id="fingerprint_input">

                        <div class="space-y-8 mb-16">
                            @foreach($session->candidates as $index => $candidate)
                                <label class="group block cursor-pointer" x-data="{ shown: false }" x-intersect.once="shown = true">
                                    <input type="radio" name="candidate_id" value="{{ $candidate->id }}"
                                           x-model="selectedCandidate"
                                           class="sr-only peer">
                                    <div class="reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                                        @include('public.voting._candidate_card', ['candidate' => $candidate, 'showResults' => false, 'totalVotes' => 0, 'selectable' => true, 'voted' => false])
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="text-center" x-data="{ shown: false }" x-intersect.once="shown = true">
                            <button type="submit"
                                    :disabled="!selectedCandidate"
                                    :class="selectedCandidate ? 'bg-jp-indigo hover:bg-jp-gold cursor-pointer' : 'bg-jp-mist cursor-not-allowed text-jp-indigo/30'"
                                    class="inline-flex items-center justify-center gap-3 px-12 py-4 text-jp-cream text-[11px] font-semibold uppercase tracking-[0.25em] transition-all duration-500 reveal"
                                    :class="shown ? 'active' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Cast My Vote
                            </button>
                            <p class="mt-4 text-[10px] text-jp-indigo/30 tracking-[0.15em]">
                                This action is permanent and cannot be undone. Your vote is protected by cryptographic hashing.
                            </p>
                        </div>
                    </form>

                {{-- Results View --}}
                @elseif($showResults)
                    <div class="space-y-8">
                        @php $winner = $session->candidates->sortByDesc('votes_count')->first(); @endphp
                        @foreach($session->candidates->sortByDesc('votes_count') as $index => $candidate)
                            <div x-data="{ shown: false }" x-intersect.once="shown = true">
                                <div class="reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                                    @include('public.voting._candidate_card', [
                                        'candidate' => $candidate,
                                        'showResults' => true,
                                        'totalVotes' => $totalVotes,
                                        'selectable' => false,
                                        'voted' => $hasVoted && $userVote && $userVote->candidate_id === $candidate->id,
                                        'isWinner' => $session->hasFinished() && $winner && $candidate->id === $winner->id
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>

                {{-- Must Login --}}
                @elseif(!auth()->check() && $session->isOpenForVoting())
                    <div class="space-y-8 mb-12">
                        @foreach($session->candidates as $index => $candidate)
                            <div x-data="{ shown: false }" x-intersect.once="shown = true">
                                <div class="reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                                    @include('public.voting._candidate_card', ['candidate' => $candidate, 'showResults' => false, 'totalVotes' => 0, 'selectable' => false, 'voted' => false])
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center bg-jp-indigo-deep text-jp-cream p-12 mt-4 relative overflow-hidden">
                        <div class="absolute inset-0 jp-seigaiha opacity-[0.04]"></div>
                        <div class="relative z-10">
                            <svg class="w-12 h-12 text-jp-gold mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            <h3 class="font-serif text-3xl mb-3">Login to Vote</h3>
                            <p class="text-jp-cream/60 mb-8 max-w-md mx-auto font-light">You must be a verified PUMA IT member to participate in this election.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-4 border border-jp-cream/30 text-[11px] font-semibold uppercase tracking-[0.25em] text-jp-cream hover:bg-jp-cream hover:text-jp-indigo-deep transition-colors duration-500">
                                Login
                            </a>
                        </div>
                    </div>

                {{-- Upcoming --}}
                @elseif($session->isUpcoming())
                    <div class="space-y-8 mb-12 opacity-60 pointer-events-none">
                        @foreach($session->candidates as $index => $candidate)
                            @include('public.voting._candidate_card', ['candidate' => $candidate, 'showResults' => false, 'totalVotes' => 0, 'selectable' => false, 'voted' => false])
                        @endforeach
                    </div>
                    <div class="text-center border border-jp-indigo/10 p-12 bg-white">
                        <p class="font-serif text-2xl text-jp-indigo mb-2">Voting Opens {{ $session->start_date->format('F j, Y') }}</p>
                        <p class="text-jp-indigo/40">This election has not yet opened. Please check back on the opening date.</p>
                    </div>

                {{-- Closed no public results --}}
                @else
                    <div class="space-y-8">
                        @foreach($session->candidates as $index => $candidate)
                            <div x-data="{ shown: false }" x-intersect.once="shown = true">
                                <div class="reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                                    @include('public.voting._candidate_card', ['candidate' => $candidate, 'showResults' => false, 'totalVotes' => 0, 'selectable' => false, 'voted' => false])
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center border border-jp-indigo/10 p-12 bg-white mt-8">
                        <p class="font-serif text-2xl text-jp-indigo mb-2">Results Pending Announcement</p>
                        <p class="text-jp-indigo/40">This election has concluded. Results will be published by the electoral committee.</p>
                    </div>
                @endif

            @endif
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fp = [
            navigator.userAgent,
            navigator.language,
            screen.width + 'x' + screen.height,
            new Date().getTimezoneOffset()
        ].join('|');

        const input = document.getElementById('fingerprint_input');
        if (input) {
            input.value = btoa(fp).substring(0, 64);
        }
    });
</script>
@endsection
