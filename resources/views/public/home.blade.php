@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator $featuredProjects */
    /** @var \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator $upcomingEvents */
    /** @var \Illuminate\Support\Collection|\Illuminate\Pagination\LengthAwarePaginator $latestArticles */
    /** @var \App\Models\Cabinet|null $activeCabinet */
    /** @var \Illuminate\Support\Collection<int, \App\Models\CabinetMember> $cabinetMembers */
@endphp

@section('title', 'PUMA Informatics — Sapientia Cabinet')
@section('meta_description', 'PUMA Informatics is the official student organization for the Informatics Department at President University, dedicated to technological excellence and positive social contribution.')

@section('content')
<div class="relative" x-data="{ scrollY: 0 }" @scroll.window="scrollY = window.pageYOffset">

    <!-- Hero — Sapientia Concept -->
    {{-- pt clears the floating island, which is ~84px tall including its offset --}}
    <section class="relative min-h-[88vh] flex items-center pt-28 sm:pt-32 pb-16 overflow-hidden border-b border-sapientia-primary/10">
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -right-40 w-[70rem] h-[70rem] bg-gradient-to-br from-sapientia-primary/18 via-sapientia-secondary/10 to-transparent blur-[120px] rounded-full"></div>
            <div class="absolute -bottom-40 -left-40 w-[55rem] h-[55rem] bg-gradient-to-tr from-jp-gold/10 via-sapientia-light/10 to-transparent blur-[140px] rounded-full"></div>
            <div class="absolute inset-0 opacity-[0.55] mix-blend-multiply bg-topo"></div>

            <div class="absolute inset-0 opacity-[0.95]" :style="`transform: translateY(${scrollY * 0.12}px)`">
                <svg class="w-full h-full" viewBox="0 0 1200 760" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
                    <defs>
                        <linearGradient id="sapientiaInk" x1="560" y1="40" x2="1120" y2="720" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#2D5A8A" stop-opacity="0.92" />
                            <stop offset="0.55" stop-color="#1B3A5C" stop-opacity="0.98" />
                            <stop offset="1" stop-color="#0F2744" stop-opacity="1" />
                        </linearGradient>
                        <path id="inkBlob" d="M794.5 70.5C932.5 28.5 1102 116.5 1134.5 264.5C1167 412.5 1144 561.5 1006 640C868 718.5 712 676 657.5 540C603 404 514 364 560 232.5C606 101 700.5 99.5 794.5 70.5Z"/>
                        <clipPath id="inkClip">
                            <use href="#inkBlob" />
                        </clipPath>
                    </defs>

                    <use href="#inkBlob" fill="url(#sapientiaInk)"/>

                    <g clip-path="url(#inkClip)" opacity="0.35">
                        <g stroke="#E3F2FD" stroke-opacity="0.7" stroke-width="1.2" fill="none">
                            <path d="M560 210C700 160 820 240 960 190C1090 145 1180 240 1260 200"/>
                            <path d="M520 260C680 200 820 310 980 250C1130 206 1220 320 1320 275"/>
                            <path d="M500 312C660 252 830 380 1010 318C1165 265 1255 410 1360 360"/>
                            <path d="M520 368C690 312 860 448 1040 390C1195 340 1265 520 1380 468"/>
                            <path d="M560 430C740 380 900 520 1080 470C1225 430 1305 610 1415 560"/>
                            <path d="M610 492C780 444 955 585 1130 540C1265 506 1335 660 1430 640"/>
                            <path d="M660 555C820 514 1010 650 1180 620C1305 598 1380 710 1470 705"/>
                            <path d="M710 620C870 590 1040 705 1210 690C1340 678 1430 740 1520 748"/>
                        </g>
                        <g stroke="#0F2744" stroke-opacity="0.28" stroke-width="1.3" fill="none">
                            <path d="M560 235C700 186 830 268 980 220C1115 176 1200 266 1280 235"/>
                            <path d="M520 338C680 282 845 412 1015 350C1165 300 1260 452 1365 410"/>
                            <path d="M585 458C760 410 930 560 1105 510C1250 470 1330 635 1425 610"/>
                            <path d="M670 585C835 550 1025 690 1200 675C1330 664 1410 740 1495 750"/>
                        </g>
                    </g>

                    <g opacity="0.9" stroke="#0F2744" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M260 422C232 392 214 356 218 324C238 344 252 372 260 422C268 372 282 344 302 324C306 356 288 392 260 422Z"/>
                        <path d="M260 422C216 402 190 368 194 324C222 344 246 376 260 422Z"/>
                        <path d="M260 422C304 402 330 368 326 324C298 344 274 376 260 422Z"/>
                        <path d="M260 422C238 396 224 372 224 348C242 362 254 384 260 422Z"/>
                        <path d="M260 422C282 396 296 372 296 348C278 362 266 384 260 422Z"/>
                        <path d="M212 432C238 456 282 456 308 432"/>
                        <path d="M338 342C368 330 396 334 418 352"/>
                        <path d="M160 352C132 340 110 344 92 360"/>
                        <path d="M420 360C450 374 476 400 488 430"/>
                        <path d="M90 368C66 386 50 410 44 440"/>
                    </g>
                </svg>
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 w-full" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
            <div class="lg:grid lg:grid-cols-12 gap-16 items-center">
                {{-- 7 of 12 columns, not 10: the ink blob occupies the right of
                     the canvas, and at 10 the display type ran underneath it and
                     lost contrast against the dark shape. --}}
                <div class="lg:col-span-7">
                    <!-- Subtle small text badge -->
                    <div class="mb-8 reveal" :class="shown ? 'active' : ''">
                        <span class="inline-flex items-center gap-5 text-[10px] uppercase tracking-[0.5em] text-sapientia-primary font-black">
                            <span class="w-10 h-[2px] bg-gradient-to-r from-sapientia-primary to-transparent"></span>
                            {{ $activeCabinet?->name ?? 'PUMA Informatics' }}
                        </span>
                    </div>

                    {{-- Capped at 7rem: 10rem overflowed the column and collided
                         with the artwork on every screen wider than a laptop. --}}
                    <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl lg:text-[5.5rem] xl:text-[6.5rem] text-sapientia-deep leading-[0.95] sm:leading-[0.9] mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                        <span class="block">PUMA</span>
                        <span class="text-gradient italic">Informatics.</span>
                    </h1>

                    <div class="max-w-xl reveal reveal-delay-200" :class="shown ? 'active' : ''">
                        <p class="text-base sm:text-lg md:text-xl text-sapientia-deep/80 font-light leading-relaxed mb-10 border-l-2 border-sapientia-primary/20 pl-6">
                            We are dedicated to developing students' capabilities in technology and fostering a community of forward-thinking tech enthusiasts.
                        </p>

                        <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                            <a href="#projects" class="group relative px-8 sm:px-10 py-4 sm:py-5 bg-sapientia-deep text-white text-[11px] tracking-[0.4em] uppercase font-bold overflow-hidden transition-transform duration-500 hover:scale-105 active:scale-95 shadow-elegant">
                                <span class="relative z-10">Explore Archive</span>
                                <div class="absolute inset-0 bg-sapientia-primary translate-y-full group-hover:translate-y-0 transition-transform duration-700 ease-cinematic"></div>
                            </a>
                            
                            <a href="{{ route('public.events.index') }}" class="flex items-center gap-6 group">
                                <span class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border border-sapientia-primary/40 flex items-center justify-center group-hover:bg-sapientia-primary/10 transition-all duration-700">
                                    <svg class="w-5 h-5 text-sapientia-primary transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                                <span class="text-[11px] uppercase tracking-[0.4em] text-sapientia-deep/60 font-black group-hover:text-sapientia-primary transition-colors">Engagements</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cabinet lineage: one emblem per generation, each opening that term's
         own roster and programme. Sits directly under the hero so the history
         is the first thing offered after the introduction. --}}
    <x-public.cabinet-lineage :cabinet-lineage="$cabinetLineage" />

    <!-- Upcoming Events (Spotlight) -->
    @if($upcomingEvents->isNotEmpty())
    <section class="py-24 relative bg-white bg-striped">
        <!-- Decorative side text -->
        <div class="absolute right-0 top-1/4 h-64 hidden lg:block">
            <span class="vertical-text text-[10px] uppercase tracking-[0.8em] text-sapientia-deep/20 font-bold">FUTURE PERSPECTIVES • ENGAGEMENTS</span>
        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-20" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex justify-center mb-8 reveal" :class="shown ? 'active' : ''">
                    <svg width="60" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="2"/></svg>
                </div>
                <h2 class="font-serif text-[12px] uppercase tracking-[0.6em] text-sapientia-primary font-black mb-6 reveal" :class="shown ? 'active' : ''">Exhibitions & Events</h2>
                <h3 class="font-serif text-5xl md:text-6xl text-sapientia-deep reveal reveal-delay-100" :class="shown ? 'active' : ''">Upcoming Engagements</h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-20 items-start">
                @foreach($upcomingEvents as $index => $event)
                    @if($index === 0)
                        <!-- Featured Event -->
                        <div class="lg:col-span-7 group cursor-pointer" x-data="{ shown: false, elementTop: 0 }" x-init="elementTop = $el.offsetTop" x-intersect.once="shown = true" onclick="window.location='{{ route('public.events.show', $event->slug) }}'">
                            <div class="relative overflow-hidden aspect-[16/10] mb-10 bg-sapientia-mist shadow-art reveal glass-card" :class="shown ? 'active' : ''">
                                @if($event->getFirstMediaUrl('hero'))
                                    <img src="{{ $event->getFirstMediaUrl('hero', 'hero') }}" 
                                         alt="{{ $event->title }}" 
                                         class="object-cover w-full h-full transform transition-transform duration-1000 ease-cinematic group-hover:scale-105"
                                         :style="`transform: translateY(${(scrollY - elementTop) * 0.04}px)`">
                                @endif
                                <div class="absolute inset-0 bg-sapientia-deep/5 group-hover:bg-transparent transition-colors duration-1000"></div>
                                <!-- Decorative Border -->
                                <div class="absolute inset-0 border border-sapientia-primary/20 group-hover:border-sapientia-primary/50 transition-colors duration-700 pointer-events-none"></div>
                            </div>
                            <div class="reveal reveal-delay-100" :class="shown ? 'active' : ''">
                                <div class="flex items-center gap-6 mb-6">
                                    <span class="text-sapientia-primary text-[11px] font-black uppercase tracking-[0.6em]">{{ $event->start_date->format('F j, Y') }}</span>
                                    <span class="w-12 h-px bg-sapientia-deep/20"></span>
                                    <span class="text-sapientia-deep/60 text-[11px] uppercase tracking-[0.4em] font-black">{{ $event->category->name ?? 'Event' }}</span>
                                </div>
                                <h4 class="font-serif text-5xl text-sapientia-deep mb-6 group-hover:text-gradient transition-all duration-700 leading-tight">{{ $event->title }}</h4>
                                <p class="text-sapientia-deep/80 font-light leading-relaxed text-lg max-w-xl border-l-2 border-sapientia-primary/20 pl-6">{{ Str::limit(strip_tags($event->description), 140) }}</p>
                            </div>
                        </div>
                    @else
                        @if($index === 1) <div class="lg:col-span-5 flex flex-col gap-16"> @endif
                        <!-- Secondary Events -->
                        <div class="group cursor-pointer grid grid-cols-1 md:grid-cols-5 gap-8 items-center" x-data="{ shown: false }" x-intersect.once="shown = true" onclick="window.location='{{ route('public.events.show', $event->slug) }}'">
                            <div class="md:col-span-2 relative overflow-hidden aspect-square bg-sapientia-mist reveal shadow-art glass-card" :class="shown ? 'active' : ''">
                                @if($event->getFirstMediaUrl('hero'))
                                    <img src="{{ $event->getFirstMediaUrl('hero', 'card') }}" alt="{{ $event->title }}" class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-1000">
                                @endif
                                <div class="absolute inset-0 border border-sapientia-primary/10 group-hover:border-sapientia-primary/30 transition-colors duration-700 pointer-events-none"></div>
                            </div>
                            <div class="md:col-span-3 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                                <span class="text-[10px] tracking-[0.5em] uppercase text-sapientia-primary font-black mb-3 block">{{ $event->start_date->format('M j') }}</span>
                                <h4 class="font-serif text-2xl text-sapientia-deep mb-3 group-hover:text-sapientia-primary transition-colors duration-700 leading-snug">{{ $event->title }}</h4>
                                <span class="text-[10px] uppercase tracking-[0.4em] text-sapientia-deep/50 font-black">{{ $event->category->name ?? 'Event' }}</span>
                            </div>
                        </div>
                        @if($loop->last) 
                            <div class="pt-8">
                                <a href="{{ route('public.events.index') }}" class="inline-flex items-center gap-6 group">
                                    <span class="text-[11px] font-black uppercase tracking-[0.5em] text-sapientia-deep">Archive</span>
                                    <span class="w-16 h-[2px] bg-sapientia-deep/30 group-hover:w-24 group-hover:bg-sapientia-primary transition-all duration-700"></span>
                                </a>
                            </div>
                        </div> 
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Project Archive -->
    @if($featuredProjects->isNotEmpty())
    <section id="projects" class="py-24 relative overflow-hidden bg-sapientia-cream bg-striped-vertical">
        {{-- Floral accent, replacing the single meandering wave line --}}
        <div class="absolute top-0 right-0 w-1/3 h-full pointer-events-none overflow-hidden" aria-hidden="true">
            <x-public.bloom class="absolute -right-16 top-24 w-72 h-72 text-jp-gold opacity-[0.10]" />
            <x-public.bloom class="absolute right-10 top-1/2 w-40 h-40 text-sapientia-primary opacity-[0.07]" rotate="24" :petals="8" />
            <x-public.bloom class="absolute -right-10 bottom-24 w-56 h-56 text-jp-gold opacity-[0.08]" rotate="48" />
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-24" x-data="{ shown: false }" x-intersect.once="shown = true">
                <h2 class="font-serif text-[12px] uppercase tracking-[0.6em] text-sapientia-primary font-black mb-6 reveal" :class="shown ? 'active' : ''">Portfolio of Excellence</h2>
                <h3 class="font-serif text-5xl md:text-6xl text-sapientia-deep reveal reveal-delay-100" :class="shown ? 'active' : ''">Selected Projects</h3>
            </div>

            <div class="space-y-40">
                @foreach($featuredProjects as $index => $project)
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-24 items-center" x-data="{ shown: false, elementTop: 0 }" x-init="elementTop = $el.offsetTop" x-intersect.once="shown = true">
                        <!-- Alternating Layout -->
                        <div class="lg:col-span-7 {{ $index % 2 !== 0 ? 'lg:order-2' : '' }} reveal" :class="shown ? 'active' : ''">
                            <div class="relative group cursor-pointer" onclick="window.location='{{ route('public.projects.show', $project->slug) }}'">
                                <div class="overflow-hidden aspect-[4/3] bg-sapientia-mist shadow-art border border-sapientia-primary/10 group-hover:border-sapientia-primary/40 transition-colors duration-700">
                                    @if($project->getFirstMediaUrl('gallery'))
                                        <img src="{{ $project->getFirstMediaUrl('gallery', 'showcase') }}" 
                                             alt="{{ $project->title }}" 
                                             class="object-cover w-full h-full transform transition-transform duration-1000 ease-cinematic group-hover:scale-110"
                                             :style="`transform: translateY(${(scrollY - elementTop) * 0.05}px) scale(${shown ? 1 : 1.1})`">
                                    @endif
                                </div>
                                <!-- Floating Index -->
                                <span class="absolute -top-16 {{ $index % 2 !== 0 ? '-right-16' : '-left-16' }} font-serif text-[12rem] text-sapientia-deep/[0.08] pointer-events-none select-none z-[-1]"
                                      :style="`transform: translateY(${(scrollY - elementTop) * -0.1}px)`">0{{ $index + 1 }}</span>
                            </div>
                        </div>
                        <div class="lg:col-span-5 {{ $index % 2 !== 0 ? 'lg:order-1' : '' }} reveal reveal-delay-200" :class="shown ? 'active' : ''">
                            <div class="flex items-center gap-6 mb-8">
                                <span class="text-[11px] uppercase tracking-[0.4em] text-sapientia-primary font-black">{{ $project->category->name ?? 'Technology' }}</span>
                                <span class="w-16 h-[2px] bg-sapientia-deep/20"></span>
                            </div>
                            <h4 class="font-serif text-4xl md:text-5xl text-sapientia-deep mb-8 leading-tight group-hover:text-sapientia-primary transition-colors duration-700">{{ $project->title }}</h4>
                            <p class="text-sapientia-deep/80 font-light leading-relaxed text-lg mb-10 border-l-2 border-sapientia-primary/10 pl-6">
                                {{ Str::limit(strip_tags($project->description), 200) }}
                            </p>
                            
                            @if($project->technologies->isNotEmpty())
                                <div class="flex flex-wrap gap-4 mb-12">
                                    @foreach($project->technologies->take(4) as $tech)
                                        <span class="text-[10px] tracking-[0.3em] uppercase text-sapientia-deep/70 px-5 py-2 border border-sapientia-deep/20 rounded-full font-black hover:bg-sapientia-primary/5 transition-colors">{{ $tech->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <a href="{{ route('public.projects.show', $project->slug) }}" class="inline-flex items-center gap-6 text-[12px] uppercase tracking-[0.4em] font-black text-sapientia-deep hover:text-sapientia-primary transition-all duration-700 group">
                                <span>Case Study</span>
                                <svg class="w-8 h-8 transform group-hover:translate-x-3 transition-transform duration-700 ease-cinematic" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-32 text-center">
                <a href="{{ route('public.projects.index') }}" class="group relative px-16 py-6 text-[12px] font-bold uppercase tracking-[0.5em] text-sapientia-deep inline-block">
                    <span class="relative z-10">View Full Archive</span>
                    <div class="absolute inset-0 border border-sapientia-deep/20 group-hover:border-sapientia-deep transition-colors duration-700"></div>
                    <div class="absolute bottom-0 left-0 w-full h-0 bg-sapientia-deep group-hover:h-full transition-all duration-700 -z-0"></div>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- The Cabinet — Leadership -->
    @if(isset($cabinetMembers) && $cabinetMembers->isNotEmpty())
    <section class="py-24 relative bg-sapientia-deep overflow-hidden">
        <!-- Seigaiha overlay -->
        <div class="absolute inset-0 jp-seigaiha opacity-[0.02]"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-24" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="flex justify-center mb-8 reveal" :class="shown ? 'active' : ''">
                    <svg width="60" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#82B1FF" stroke-width="1.5"/></svg>
                </div>
                <h2 class="font-serif text-[12px] uppercase tracking-[0.6em] text-sapientia-secondary font-bold mb-6 reveal" :class="shown ? 'active' : ''">Student Governance</h2>
                <h3 class="font-serif text-5xl md:text-6xl text-white reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    The Cabinet
                    @if($activeCabinet)
                        <span class="block text-xl text-white/30 mt-6 font-sans font-light tracking-[0.4em] uppercase">{{ $activeCabinet->name }}</span>
                    @endif
                </h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-x-12 gap-y-24">
                @php
                    $sortedCabinet = $cabinetMembers->sortBy('role_hierarchy_level');
                @endphp
                @foreach($sortedCabinet as $index => $member)
                    <a href="{{ route('public.cabinet.show', $member->slug) }}" class="flex flex-col items-center text-center group" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <div class="relative w-full aspect-[4/5] mb-8 overflow-hidden reveal shadow-elegant" style="transition-delay: {{ $index * 100 }}ms;" :class="shown ? 'active' : ''">
                            @if($member->getFirstMediaUrl('portrait'))
                                <img src="{{ $member->getFirstMediaUrl('portrait', 'portrait') }}" alt="{{ $member->name }}" class="object-cover w-full h-full filter grayscale contrast-125 group-hover:grayscale-0 group-hover:contrast-100 transition-all duration-1000 ease-cinematic scale-105 group-hover:scale-100">
                            @else
                                <div class="w-full h-full bg-sapientia-deep flex items-center justify-center border border-white/5">
                                    <span class="font-serif text-6xl text-white/10">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 border border-white/5 group-hover:border-sapientia-primary/20 transition-colors duration-700"></div>
                            
                            @if($member->role_hierarchy_level <= 2)
                                <div class="absolute top-0 left-0 px-3 py-1 bg-sapientia-primary text-white text-[8px] tracking-[0.2em] uppercase font-bold">
                                    {{ $member->role_hierarchy_level == 1 ? 'Head' : 'Vice' }}
                                </div>
                            @endif
                        </div>
                        <div class="reveal" style="transition-delay: {{ ($index * 100) + 50 }}ms;" :class="shown ? 'active' : ''">
                            <h4 class="font-serif text-xl text-white mb-3 leading-tight group-hover:text-sapientia-secondary transition-colors duration-700">{{ $member->name }}</h4>
                            <p class="text-[10px] tracking-[0.4em] uppercase text-white/30 group-hover:text-sapientia-secondary/50 transition-colors duration-700 font-bold">{{ $member->role_title }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-24 text-center" x-data="{ shown: false }" x-intersect.once="shown = true">
                <a href="{{ route('public.cabinet.index') }}" class="group relative px-12 py-5 text-[11px] font-bold uppercase tracking-[0.5em] text-white inline-block overflow-hidden">
                    <span class="relative z-10">Explore Governance</span>
                    <div class="absolute inset-0 border border-white/20 group-hover:border-sapientia-secondary transition-colors duration-700"></div>
                    <div class="absolute inset-0 bg-sapientia-primary transform translate-y-full group-hover:translate-y-0 transition-transform duration-700 ease-cinematic"></div>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- The Newsroom -->
    @if($latestArticles->isNotEmpty())
    <section class="py-24 relative bg-white">
        <!-- Subtle vertical text -->
        <div class="absolute left-0 top-1/3 h-64 hidden lg:block">
            <span class="vertical-text text-[10px] uppercase tracking-[0.8em] text-sapientia-deep/5 font-bold">EDITORIAL VOICES • NEWS</span>
        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20" x-data="{ shown: false }" x-intersect.once="shown = true">
                <div class="reveal" :class="shown ? 'active' : ''">
                    <div class="flex items-center gap-6 mb-6">
                        <span class="w-16 h-px bg-sapientia-primary"></span>
                        <span class="font-serif text-[12px] uppercase tracking-[0.6em] text-sapientia-primary font-bold">The Newsroom</span>
                    </div>
                    <h3 class="font-serif text-5xl md:text-6xl text-sapientia-deep">Latest Insights</h3>
                </div>
                <div class="reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    <a href="{{ route('public.news.index') }}" class="inline-flex items-center gap-8 group">
                        <span class="text-[11px] font-bold uppercase tracking-[0.4em] text-sapientia-deep">Read All Articles</span>
                        <span class="w-20 h-px bg-sapientia-deep group-hover:w-28 group-hover:bg-sapientia-primary transition-all duration-1000 ease-cinematic"></span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20">
                @foreach($latestArticles as $index => $article)
                    <a href="{{ route('public.news.show', $article->slug) }}" class="group flex flex-col" x-data="{ shown: false, elementTop: 0 }" x-init="elementTop = $el.offsetTop" x-intersect.once="shown = true">
                        <div class="relative overflow-hidden aspect-[4/5] mb-10 bg-sapientia-mist shadow-art reveal" style="transition-delay: {{ $index * 150 }}ms;" :class="shown ? 'active' : ''">
                            @if($article->getFirstMediaUrl('cover'))
                                <img src="{{ $article->getFirstMediaUrl('cover') }}" 
                                     alt="{{ $article->title }}" 
                                     class="object-cover w-full h-full transform transition-transform duration-1000 ease-cinematic group-hover:scale-110"
                                     :style="`transform: translateY(${(scrollY - elementTop) * 0.03}px)`">
                            @endif
                            <div class="absolute inset-0 bg-sapientia-deep/5 group-hover:bg-transparent transition-colors duration-1000"></div>
                        </div>
                        <div class="flex-grow reveal" style="transition-delay: {{ ($index * 150) + 100 }}ms;" :class="shown ? 'active' : ''">
                            <div class="flex items-center gap-4 mb-6">
                                <span class="text-[10px] uppercase tracking-[0.3em] text-sapientia-deep/40 font-bold">{{ $article->published_at->format('M d, Y') }}</span>
                                <span class="w-6 h-px bg-sapientia-deep/10"></span>
                                <span class="text-[10px] uppercase tracking-[0.3em] text-sapientia-primary font-bold">Insights</span>
                            </div>
                            <h4 class="font-serif text-3xl text-sapientia-deep mb-6 group-hover:text-sapientia-primary transition-colors duration-700 leading-snug">{{ $article->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
