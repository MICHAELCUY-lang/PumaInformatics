@extends('layouts.public')

@php
    /** @var \App\Models\CabinetMember $member */
@endphp

@section('title', $member->name . ' — Cabinet Member')
@section('meta_description', $member->role_title . ' at PUMA Informatics ' . ($member->cabinet ? $member->cabinet->name : ''))

@section('content')
<div class="min-h-screen">

    <!-- Member Identity Section -->
    <section class="pt-28 sm:pt-40 pb-20 sm:pb-32 relative overflow-hidden bg-sapientia-ink">
        <div class="absolute inset-0 bg-topo opacity-[0.18] mix-blend-multiply"></div>
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <!-- Large rotating star in corner -->
            <div class="absolute -top-1/4 -left-1/4 w-[600px] h-[600px] opacity-[0.03] animate-lotus-spin">
                <svg viewBox="0 0 200 200" fill="none" class="w-full h-full text-sapientia-primary">
                    <g transform="translate(100,100)">
                        @for($i = 0; $i < 8; $i++)
                        <path d="M0,0 Q15,-30 0,-60 Q-15,-30 0,0" fill="currentColor" transform="rotate({{ $i * 45 }})" />
                        @endfor
                    </g>
                </svg>
            </div>
            <!-- Seigaiha pattern -->
            <div class="absolute top-0 right-0 w-full h-full jp-seigaiha opacity-[0.02]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
            <div class="flex flex-col lg:flex-row gap-20 items-start">
                
                <!-- Left: Portrait Image -->
                <div class="w-full lg:w-1/3 reveal" :class="shown ? 'active' : ''">
                    <div class="relative aspect-[3/4] bg-sapientia-mist shadow-art overflow-hidden group">
                        @if($member->getFirstMediaUrl('portrait'))
                            <img src="{{ $member->getFirstMediaUrl('portrait', 'portrait') }}" alt="{{ $member->name }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-sapientia-deep">
                                <span class="font-serif text-9xl text-white/10">{{ substr($member->name, 0, 1) }}</span>
                            </div>
                        @endif
                        
                        <!-- Floating Label (Vertical) -->
                        <div class="absolute top-10 -right-4 hidden md:block">
                            <span class="vertical-text text-[10px] uppercase tracking-[0.8em] text-white font-bold px-4 py-8 bg-sapientia-primary/80 backdrop-blur-sm">
                                EST. {{ $member->term_year }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Information -->
                <div class="w-full lg:w-2/3">
                    <div class="mb-12 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                        <span class="inline-flex items-center gap-6 text-[11px] uppercase tracking-[0.5em] text-sapientia-primary font-bold mb-8">
                            <span class="w-16 h-px bg-sapientia-primary/40"></span>
                            {{ $member->department ? $member->department->name : 'The Executive' }}
                        </span>
                        
                        <h1 class="font-serif text-4xl sm:text-6xl md:text-8xl text-sapientia-deep mb-4 leading-tight">
                            {{ $member->name }}
                        </h1>
                        <p class="font-sans text-lg sm:text-xl md:text-2xl text-sapientia-primary tracking-[0.2em] uppercase font-light italic">
                            {{ $member->role_title }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-20 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                        <!-- Biography Column -->
                        <div class="md:col-span-2">
                            <h4 class="font-serif text-[12px] uppercase tracking-[0.5em] text-sapientia-deep/30 mb-8 font-bold flex items-center gap-4">
                                Personal Identity
                                <span class="flex-grow h-px bg-sapientia-deep/5"></span>
                            </h4>
                            <div class="prose prose-stone prose-lg max-w-none text-sapientia-deep/70 font-light leading-relaxed whitespace-pre-line">
                                {!! $member->biography ?: 'Biografi belum tersedia untuk anggota ini.' !!}
                            </div>
                        </div>

                        <!-- Details & Socials -->
                        <div class="space-y-12">
                            <div>
                                <h4 class="text-[10px] uppercase tracking-[0.5em] text-sapientia-deep/30 font-bold mb-4">Masa Jabatan</h4>
                                <p class="font-mono text-sm text-sapientia-deep/60">{{ $member->term_year }}</p>
                            </div>
                            
                            @php $socials = $member->social_links ?? []; @endphp
                            @if(!empty($socials))
                            <div>
                                <h4 class="text-[10px] uppercase tracking-[0.5em] text-sapientia-deep/30 font-bold mb-6">Digital Presence</h4>
                                <div class="flex gap-6">
                                    @foreach($socials as $platform => $url)
                                        @if(!empty($url))
                                        <a href="{{ $url }}" target="_blank" class="group flex items-center gap-3">
                                            <span class="w-10 h-10 rounded-full border border-sapientia-deep/10 flex items-center justify-center group-hover:bg-sapientia-primary group-hover:border-sapientia-primary transition-all duration-500">
                                                <svg class="w-4 h-4 text-sapientia-deep/40 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    @if($platform === 'linkedin') <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                                    @elseif($platform === 'instagram') <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                    @endif
                                                </svg>
                                            </span>
                                        </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation Back -->
                    <div class="reveal reveal-delay-300" :class="shown ? 'active' : ''">
                        <a href="{{ route('public.cabinet.index') }}" class="group flex items-center gap-6 text-[11px] uppercase tracking-[0.4em] text-sapientia-deep font-bold">
                            <span class="w-12 h-px bg-sapientia-deep transition-all duration-700 group-hover:w-24"></span>
                            Return to Roster
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
