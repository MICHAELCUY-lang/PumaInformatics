@extends('layouts.public')

@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Cabinet> $cabinets */
    /** @var \App\Models\Cabinet|null $activeCabinet */
    /** @var \Illuminate\Support\Collection<int, \App\Models\CabinetMember> $executives */
    /** @var \Illuminate\Support\Collection<int, \App\Models\CabinetDepartment> $departments */
@endphp

@section('title', 'The Cabinet')
@section('meta_description', 'Organizational structure and leadership of PUMA IT.')

@section('content')
<div class="min-h-screen">

    <!-- Hero -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 relative overflow-hidden border-b border-sapientia-primary/10 bg-sapientia-ink">
        <div class="absolute inset-0 bg-topo opacity-[0.22] mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="60" height="16" viewBox="0 0 60 16" fill="none">
                    <path d="M0 8C10 3 20 3 30 8C40 13 50 13 60 8" stroke="#448AFF" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl text-sapientia-deep mb-6 reveal" :class="shown ? 'active' : ''">The Cabinet</h1>
            <p class="text-sapientia-deep/70 max-w-2xl mx-auto text-base sm:text-lg reveal reveal-delay-100 font-medium" :class="shown ? 'active' : ''">
                The institutional governance structure and student leadership driving our vision forward.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sapientia-primary/25 to-transparent"></div>
    </section>

    {{-- Generation selector. Same emblem strip as the homepage so switching
         cabinets looks and behaves identically wherever you meet it. --}}
    <x-public.cabinet-lineage
        :cabinet-lineage="$cabinets"
        :active-slug="$activeCabinet?->slug"
        compact />

    @if($activeCabinet?->tagline)
        <div class="bg-white/20 py-6 text-center">
            <p class="font-serif text-lg text-jp-indigo/60 italic">{{ $activeCabinet->tagline }}</p>
        </div>
    @endif

    <!-- Departments & Members -->
    <section class="py-24 bg-white/30 bg-striped">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($executives->isNotEmpty())
                <div class="mb-32" x-data="{ shown: false }" x-intersect.once="shown = true">
                    <div class="text-center mb-16 reveal" :class="shown ? 'active' : ''">
                        <div class="flex justify-center mb-4">
                            <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="2"/></svg>
                        </div>
                        <h2 class="font-serif text-4xl text-jp-indigo mb-4">The Executive</h2>
                        <p class="text-jp-indigo/70 max-w-3xl mx-auto font-medium">The core leadership overseeing organizational strategy and institutional integrity.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
                        @foreach($executives as $index => $member)
                            <a href="{{ route('public.cabinet.show', $member->slug) }}" class="group flex flex-col items-center reveal" style="transition-delay: {{ ($index % 3) * 150 }}ms;" :class="shown ? 'active' : ''">
                                <div class="relative w-48 h-64 md:w-56 md:h-72 mb-6 overflow-hidden bg-jp-mist shadow-wave border border-jp-indigo/10 group-hover:border-jp-indigo/40 transition-colors duration-700">
                                    @if($member->getFirstMediaUrl('portrait'))
                                        <img src="{{ $member->getFirstMediaUrl('portrait', 'portrait') }}" alt="{{ $member->name }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic filter grayscale group-hover:grayscale-0">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-jp-indigo">
                                            <span class="font-serif text-6xl text-jp-cream/30">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    
                                    <!-- View Profile Overlay -->
                                    <div class="absolute inset-0 bg-sapientia-deep/40 opacity-0 group-hover:opacity-100 transition-opacity duration-700 flex items-center justify-center backdrop-blur-[2px]">
                                        <span class="text-[10px] uppercase tracking-[0.5em] text-white font-black border border-white/40 px-6 py-3">View Profile</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="font-serif text-2xl text-jp-indigo mb-1 group-hover:text-sapientia-primary transition-colors">{{ $member->name }}</h3>
                                    <p class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-black mb-3">{{ $member->role_title }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($departments->isEmpty() && $executives->isEmpty())
                <div class="text-center py-24">
                    <svg width="60" height="20" viewBox="0 0 60 20" fill="none" class="mx-auto mb-6"><path d="M0 10C10 4 20 4 30 10C40 16 50 16 60 10" stroke="#C5A47E" stroke-width="2"/></svg>
                    <p class="text-jp-indigo/60 font-serif text-xl italic font-bold">The organizational structure is currently being updated.</p>
                </div>
            @else
                <div class="space-y-40">
                    @foreach($departments as $department)
                        @if($department->members->isNotEmpty())
                            <div x-data="{ shown: false }" x-intersect.once="shown = true">
                                <!-- Department Header -->
                                <div class="text-center mb-20 reveal" :class="shown ? 'active' : ''">
                                    <div class="flex justify-center mb-6">
                                        <svg width="60" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="2"/></svg>
                                    </div>
                                    <h2 class="font-serif text-5xl text-sapientia-deep mb-6 uppercase tracking-wider">{{ $department->name }}</h2>
                                    @if($department->description)
                                        <p class="text-sapientia-deep/75 max-w-3xl mx-auto font-medium leading-relaxed">{{ $department->description }}</p>
                                    @endif
                                </div>

                                <!-- Leaders (HOD & VOD) -->
                                @php
                                    $leaders = $department->members->whereIn('role_hierarchy_level', [1, 2]);
                                    $regularMembers = $department->members->where('role_hierarchy_level', '>', 2);
                                @endphp

                                @if($leaders->isNotEmpty())
                                    <div class="flex flex-wrap justify-center gap-16 lg:gap-24 mb-24">
                                        @foreach($leaders as $index => $member)
                                            <a href="{{ route('public.cabinet.show', $member->slug) }}" class="group flex flex-col items-center reveal" style="transition-delay: {{ $index * 150 }}ms;" :class="shown ? 'active' : ''">
                                                <div class="relative w-56 h-72 md:w-64 md:h-80 mb-8 overflow-hidden bg-sapientia-mist shadow-elegant transform group-hover:-translate-y-2 transition-transform duration-700 ease-cinematic border border-sapientia-deep/10 group-hover:border-sapientia-deep/30">
                                                    @if($member->getFirstMediaUrl('portrait'))
                                                        <img src="{{ $member->getFirstMediaUrl('portrait', 'portrait') }}" alt="{{ $member->name }}" class="object-cover w-full h-full filter grayscale group-hover:grayscale-0 transition-all duration-1000 ease-cinematic">
                                                    @else
                                                        <div class="absolute inset-0 flex items-center justify-center bg-sapientia-deep">
                                                            <span class="font-serif text-8xl text-white/20">{{ substr($member->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Role Badge Overlay -->
                                                    <div class="absolute top-0 left-0 px-4 py-2 bg-sapientia-primary text-white text-[9px] tracking-[0.3em] uppercase font-black">
                                                        {{ $member->role_hierarchy_level == 1 ? 'Head' : 'Vice' }}
                                                    </div>

                                                    <div class="absolute inset-0 bg-sapientia-deep/40 opacity-0 group-hover:opacity-100 transition-opacity duration-700 flex items-center justify-center backdrop-blur-[2px]">
                                                        <span class="text-[10px] uppercase tracking-[0.5em] text-white font-black border border-white/40 px-6 py-3">View Profile</span>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <h3 class="font-serif text-3xl text-sapientia-deep mb-2 group-hover:text-sapientia-primary transition-colors">{{ $member->name }}</h3>
                                                    <p class="text-[11px] tracking-[0.3em] uppercase text-sapientia-primary font-black">{{ $member->role_title }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Regular Members -->
                                @if($regularMembers->isNotEmpty())
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-10 lg:gap-12 pt-16 border-t border-sapientia-deep/10">
                                        @foreach($regularMembers as $index => $member)
                                            <a href="{{ route('public.cabinet.show', $member->slug) }}" class="group flex flex-col items-center reveal" style="transition-delay: {{ ($index + 2) * 100 }}ms;" :class="shown ? 'active' : ''">
                                                <div class="relative w-full aspect-[4/5] mb-6 overflow-hidden bg-sapientia-mist shadow-art transform group-hover:-translate-y-1 transition-transform duration-700 ease-cinematic border border-sapientia-deep/5 group-hover:border-sapientia-deep/20">
                                                    @if($member->getFirstMediaUrl('portrait'))
                                                        <img src="{{ $member->getFirstMediaUrl('portrait', 'portrait') }}" alt="{{ $member->name }}" class="object-cover w-full h-full filter grayscale group-hover:grayscale-0 transition-all duration-1000 ease-cinematic">
                                                    @else
                                                        <div class="absolute inset-0 flex items-center justify-center bg-sapientia-deep">
                                                            <span class="font-serif text-5xl text-white/20">{{ substr($member->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-center">
                                                    <h3 class="font-serif text-xl text-sapientia-deep mb-1 group-hover:text-sapientia-primary transition-colors">{{ $member->name }}</h3>
                                                    <p class="text-[9px] tracking-[0.2em] uppercase text-sapientia-deep/60 font-black">{{ $member->role_title }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- This generation's programme. Events belong to a cabinet, so each term
         shows what it actually ran rather than one undifferentiated list. --}}
    @if($events->isNotEmpty())
    <section class="py-24 relative bg-jp-cream-warm overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <x-public.bloom class="absolute -left-20 top-10 w-72 h-72 text-jp-gold opacity-[0.07]" />
            <x-public.bloom class="absolute -right-24 bottom-0 w-80 h-80 text-jp-indigo opacity-[0.05]" rotate="120" />
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="block text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
                    Programme
                </span>
                <h2 class="font-serif text-3xl md:text-4xl text-jp-indigo">
                    What {{ $activeCabinet?->name ?? 'This Cabinet' }} Ran
                </h2>
            </div>

            <ol class="relative border-l border-jp-indigo/10 ml-3 md:ml-6 space-y-10">
                @foreach($events as $event)
                    <li class="relative pl-8 md:pl-12">
                        <span class="absolute -left-[5px] top-2 w-2.5 h-2.5 rounded-full bg-jp-gold ring-4 ring-jp-cream-warm"></span>

                        <time datetime="{{ $event->start_date?->toDateString() }}"
                              class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40 mb-2">
                            {{ $event->start_date?->translatedFormat('F Y') ?? 'Undated' }}
                        </time>

                        <h3 class="font-serif text-xl md:text-2xl text-jp-indigo">
                            @if($event->status === 'published')
                                <a href="{{ route('public.events.show', $event->slug) }}"
                                   class="hover:text-jp-gold transition-colors duration-500">{{ $event->title }}</a>
                            @else
                                {{ $event->title }}
                            @endif
                        </h3>

                        @if($event->excerpt || $event->description)
                            <p class="mt-2 text-jp-indigo/50 font-light leading-relaxed max-w-2xl">
                                {{ Str::limit(strip_tags($event->excerpt ?: $event->description), 180) }}
                            </p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

</div>
@endsection
