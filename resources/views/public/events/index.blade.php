@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \Illuminate\Pagination\LengthAwarePaginator $events */
@endphp

@section('title', 'Exhibitions & Events')
@section('meta_description', 'Upcoming institutional engagements, campaign launches, and technology exhibitions from PUMA IT.')

@section('content')
<div class="min-h-screen">

    <!-- Events Header -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 relative overflow-hidden bg-sapientia-ink border-b border-sapientia-primary/10">
        <div class="absolute inset-0 bg-topo opacity-[0.22] mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="60" height="12" viewBox="0 0 60 12" fill="none"><path d="M0 6C10 2 20 2 30 6C40 10 50 10 60 6" stroke="#448AFF" stroke-width="1.5"/></svg>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl text-sapientia-deep mb-6 reveal" :class="shown ? 'active' : ''">Exhibitions & Events</h1>
            <p class="text-sapientia-deep/70 max-w-2xl mx-auto text-base sm:text-lg reveal reveal-delay-100" :class="shown ? 'active' : ''">
                Institutional engagements, campaign launches, and technology showcases.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sapientia-primary/25 to-transparent"></div>
    </section>

    <!-- Events Layout -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($events->isEmpty())
                <div class="text-center py-24">
                    <svg width="60" height="20" viewBox="0 0 60 20" fill="none" class="mx-auto mb-6"><path d="M0 10C10 4 20 4 30 10C40 16 50 16 60 10" stroke="#C5A47E" stroke-width="1"/></svg>
                    <p class="text-jp-indigo/40 font-serif text-xl italic">No upcoming engagements scheduled at this time.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    @foreach($events as $index => $event)
                        <div class="group cursor-pointer flex flex-col" x-data="{ shown: false }" x-intersect.once="shown = true" onclick="window.location='{{ route('public.events.show', $event->slug) }}'">
                            <div class="relative overflow-hidden aspect-[4/5] mb-6 reveal bg-jp-mist shadow-wave" :class="shown ? 'active' : ''">
                                @if($event->getFirstMediaUrl('hero'))
                                    <img src="{{ $event->getFirstMediaUrl('hero', 'card') }}" alt="{{ $event->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-jp-indigo-deep/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="absolute bottom-6 left-6 right-6 text-white transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                                    <span class="text-[10px] tracking-[0.25em] uppercase font-semibold block mb-1">Location</span>
                                    <span class="font-serif text-lg">{{ $event->location_name }}</span>
                                </div>
                            </div>
                            
                            <div class="flex-grow reveal reveal-delay-100" :class="shown ? 'active' : ''">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">{{ $event->start_date->format('F j, Y') }}</span>
                                    <svg width="16" height="6" viewBox="0 0 16 6" fill="none"><path d="M0 3C3 1 5 1 8 3C11 5 13 5 16 3" stroke="#C5A47E" stroke-width="0.75"/></svg>
                                    <span class="text-[10px] tracking-[0.25em] uppercase text-jp-indigo/40 font-semibold">{{ $event->category->name ?? 'Event' }}</span>
                                </div>
                                
                                <h3 class="font-serif text-3xl text-jp-indigo mb-3 group-hover:text-jp-gold transition-colors duration-500">{{ $event->title }}</h3>
                                
                                <p class="text-jp-indigo/50 text-sm leading-relaxed mb-6">
                                    {{ Str::limit(strip_tags($event->description), 120) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-20">
                    {{ $events->links() }}
                </div>
            @endif

        </div>
    </section>

</div>
@endsection
