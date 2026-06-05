@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator $sessions */
@endphp

@section('title', 'Student Elections')
@section('meta_description', 'Participate in institutional student elections. Secure, transparent, and democratic voting for PUMA IT leadership.')

@section('content')
<div class="bg-jp-cream min-h-screen">

    {{-- Page Header --}}
    <section class="pt-32 pb-20 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-2/5 h-full">
            <svg viewBox="0 0 400 600" fill="none" class="w-full h-full opacity-[0.04]" preserveAspectRatio="xMinYMid slice">
                <path d="M400 0C350 120 280 180 230 300C180 420 230 520 350 600L400 600L400 0Z" fill="#1B3A5C"/>
            </svg>
        </div>
        <div class="absolute bottom-0 right-0 w-80 h-80 jp-seigaiha opacity-40"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex items-center justify-center gap-3 mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
                <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">Governance</span>
                <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
            </div>
            <h1 class="font-serif text-5xl md:text-7xl text-jp-indigo mb-6 reveal reveal-delay-100" :class="shown ? 'active' : ''">Student Elections</h1>
            <p class="text-jp-indigo/50 max-w-2xl mx-auto text-lg font-light reveal reveal-delay-200" :class="shown ? 'active' : ''">
                A secure, transparent, and immutable democratic platform for PUMA IT leadership elections.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
    </section>

    {{-- Trust Indicators --}}
    <section class="py-16 bg-white relative">
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex items-start gap-5 reveal" :class="shown ? 'active' : ''">
                    <div class="flex-shrink-0 w-12 h-12 bg-jp-cream border border-jp-indigo/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-jp-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-serif text-lg text-jp-indigo mb-1">Immutable Ledger</h3>
                        <p class="text-sm text-jp-indigo/50 leading-relaxed">Votes are recorded as permanent, tamper-proof entries. No deletion, no modification.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    <div class="flex-shrink-0 w-12 h-12 bg-jp-cream border border-jp-indigo/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-jp-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-serif text-lg text-jp-indigo mb-1">One Vote Per Member</h3>
                        <p class="text-sm text-jp-indigo/50 leading-relaxed">Database-level unique constraints prevent any possibility of double-voting.</p>
                    </div>
                </div>
                <div class="flex items-start gap-5 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <div class="flex-shrink-0 w-12 h-12 bg-jp-cream border border-jp-indigo/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-jp-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-serif text-lg text-jp-indigo mb-1">Verified Identity</h3>
                        <p class="text-sm text-jp-indigo/50 leading-relaxed">Authentication and email verification required. Your identity is confirmed, not stored with your vote.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Active & Upcoming Elections --}}
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($sessions->isEmpty())
                <div class="text-center py-24" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <div class="w-16 h-16 border border-jp-indigo/10 flex items-center justify-center mx-auto mb-8 reveal" :class="shown ? 'active' : ''">
                        <svg class="w-7 h-7 text-jp-indigo/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="font-serif text-2xl text-jp-indigo mb-2 reveal reveal-delay-100" :class="shown ? 'active' : ''">No Active Elections</p>
                    <p class="text-jp-indigo/40 reveal reveal-delay-200" :class="shown ? 'active' : ''">There are no elections scheduled at this time. Check back soon.</p>
                </div>
            @else
                <div class="mb-16" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <div class="flex items-center gap-3 mb-3 reveal" :class="shown ? 'active' : ''">
                        <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
                        <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">Currently Open</span>
                    </div>
                    <h2 class="font-serif text-4xl md:text-5xl text-jp-indigo reveal reveal-delay-100" :class="shown ? 'active' : ''">Active Elections</h2>
                </div>

                <div class="space-y-8">
                    @foreach($sessions as $index => $session)
                        <a href="{{ route('public.voting.show', $session->slug) }}"
                           class="group block bg-white border border-jp-indigo/5 hover:border-jp-gold transition-all duration-500 hover:shadow-wave"
                           x-data="{ shown: false }" x-intersect.once="shown = true">
                            <div class="grid grid-cols-1 lg:grid-cols-12 reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                                {{-- Status Bar --}}
                                <div class="lg:col-span-1 flex lg:flex-col items-center justify-center p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-jp-indigo/5 bg-jp-cream group-hover:bg-jp-indigo transition-colors duration-500">
                                    @if($session->status === 'active')
                                        <span class="flex items-center gap-2 lg:flex-col lg:gap-1 text-jp-gold group-hover:text-jp-cream transition-colors">
                                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                            <span class="text-[10px] tracking-[0.25em] uppercase font-bold lg:mt-1">LIVE</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] tracking-[0.25em] uppercase font-semibold text-jp-indigo/40 group-hover:text-jp-cream/70 transition-colors">SOON</span>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="lg:col-span-9 p-8 lg:p-12">
                                    <h3 class="font-serif text-3xl text-jp-indigo mb-3 group-hover:text-jp-gold transition-colors duration-500">{{ $session->title }}</h3>
                                    @if($session->description)
                                        <p class="text-jp-indigo/50 leading-relaxed mb-6 max-w-2xl">{{ Str::limit($session->description, 200) }}</p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-8 text-sm">
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Start Date</span>
                                            <span class="text-jp-indigo font-medium">{{ $session->start_date->format('F j, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">End Date</span>
                                            <span class="text-jp-indigo font-medium">{{ $session->end_date->format('F j, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Candidates</span>
                                            <span class="text-jp-indigo font-medium">{{ $session->candidates_count }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-[0.2em] text-jp-indigo/30 font-semibold mb-1">Votes Cast</span>
                                            <span class="text-jp-indigo font-medium">{{ $session->votes_count }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                <div class="lg:col-span-2 flex items-center justify-center p-8 border-t lg:border-t-0 lg:border-l border-jp-indigo/5 group-hover:bg-jp-gold transition-colors duration-500">
                                    <div class="text-center">
                                        <span class="block font-serif text-jp-indigo group-hover:text-white transition-colors text-lg mb-2">
                                            {{ $session->status === 'active' ? 'Vote Now' : 'View Details' }}
                                        </span>
                                        <svg class="w-6 h-6 text-jp-indigo group-hover:text-white transition-colors transform group-hover:translate-x-1 duration-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- Past Elections (Public Results) --}}
    @if($pastSessions->isNotEmpty())
    <section class="py-24 bg-white relative">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-16" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex items-center gap-3 mb-3 reveal" :class="shown ? 'active' : ''">
                    <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
                    <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">Historical Record</span>
                </div>
                <h2 class="font-serif text-4xl text-jp-indigo reveal reveal-delay-100" :class="shown ? 'active' : ''">Past Elections</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($pastSessions as $index => $session)
                    <a href="{{ route('public.voting.show', $session->slug) }}"
                       class="group block bg-jp-cream border border-jp-indigo/5 hover:border-jp-gold hover:shadow-wave p-8 transition-all duration-500"
                       x-data="{ shown: false }" x-intersect.once="shown = true">
                        <div class="reveal" :class="shown ? 'active' : ''" style="transition-delay: {{ $index * 100 }}ms;">
                            <span class="text-[10px] uppercase tracking-[0.25em] text-jp-indigo/30 font-semibold mb-4 block">Closed — {{ $session->end_date->format('M Y') }}</span>
                            <h3 class="font-serif text-2xl text-jp-indigo mb-4 group-hover:text-jp-gold transition-colors duration-500">{{ $session->title }}</h3>
                            <div class="flex items-center gap-6 text-sm mt-6 pt-6 border-t border-jp-indigo/10">
                                <div>
                                    <span class="block text-[10px] text-jp-indigo/30 mb-1">Candidates</span>
                                    <span class="font-semibold text-jp-indigo">{{ $session->candidates_count }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-jp-indigo/30 mb-1">Total Votes</span>
                                    <span class="font-semibold text-jp-indigo">{{ $session->votes_count }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
