@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \App\Models\Event $event */
@endphp

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->description), 150))
@section('meta_type', 'article')
@section('meta_image', $event->getFirstMediaUrl('hero', 'card') ?: null)

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Event",
  "name": "{{ $event->title }}",
  "startDate": "{{ $event->start_date->toIso8601String() }}",
  "endDate": "{{ $event->end_date->toIso8601String() }}",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
  "location": {
    "@@type": "Place",
    "name": "{{ $event->location_name }}"
  },
  "image": [
    "{{ $event->getFirstMediaUrl('hero', 'card') ?: asset('images/default-og.jpg') }}"
  ],
  "description": "{{ Str::limit(strip_tags($event->description), 150) }}"
}
</script>
@endpush

@section('content')
<div class="min-h-screen">

    <!-- Split Hero -->
    <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[90vh]">
        <!-- Left: Context -->
        <div class="flex flex-col justify-center px-6 sm:px-8 py-20 sm:py-24 lg:p-24 bg-white relative overflow-hidden" x-data="{ shown: false }" x-intersect.once="shown = true">
            <!-- Seigaiha corner -->
            <div class="absolute bottom-0 left-0 w-48 h-48 jp-seigaiha opacity-30"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6 reveal" :class="shown ? 'active' : ''">
                    <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1"/></svg>
                    <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-semibold">
                        {{ $event->category->name ?? 'Engagement' }}
                    </span>
                </div>
                <h1 class="font-serif text-5xl lg:text-7xl text-jp-indigo leading-tight mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
                    {{ $event->title }}
                </h1>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                    <div>
                        <span class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40 font-semibold mb-2">Date & Time</span>
                        <span class="block text-jp-indigo font-medium">{{ $event->start_date->format('l, F j, Y') }}</span>
                        <span class="block text-jp-indigo/60 text-sm">{{ $event->start_date->format('g:i A') }}@if($event->end_date) - {{ $event->end_date->format('g:i A') }}@endif</span>
                    </div>
                    <div>
                        <span class="block text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40 font-semibold mb-2">Location</span>
                        <span class="block text-jp-indigo font-medium">{{ $event->location_name }}</span>
                    </div>
                </div>

                @if($event->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 reveal reveal-delay-300" :class="shown ? 'active' : ''">
                        @foreach($event->tags as $tag)
                            <span class="px-3 py-1 bg-jp-cream border border-jp-indigo/10 text-jp-indigo text-[10px] tracking-[0.15em] uppercase">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Media -->
        <div class="relative h-[60vh] lg:h-auto bg-sapientia-ink overflow-hidden">
            @if($event->getFirstMediaUrl('hero'))
                <img src="{{ $event->getFirstMediaUrl('hero', 'hero') }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-topo opacity-[0.16] mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-jp-indigo-deep/30 via-transparent to-transparent"></div>
        </div>
    </div>

    <!-- Editorial Brief -->
    <section class="py-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg prose-headings:font-serif prose-headings:text-jp-indigo prose-p:text-jp-indigo/75 mx-auto">
            {!! $event->description !!}
        </div>
    </section>

    <!-- Event Gallery -->
    @if($event->hasMedia('gallery'))
    <section class="py-12 relative pb-24">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
            <div class="text-center mb-12">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none" class="mx-auto mb-3"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1"/></svg>
                <h3 class="font-serif text-[11px] uppercase tracking-[0.3em] text-jp-gold font-semibold">Event Gallery</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ shown: false }" x-intersect.once="shown = true">
                @foreach($event->getMedia('gallery') as $media)
                    <div class="relative overflow-hidden aspect-square group reveal bg-jp-mist" :class="shown ? 'active' : ''" style="transition-delay: {{ $loop->index * 75 }}ms">
                        <img src="{{ $media->getUrl('card') }}" alt="{{ $event->title }} Gallery Image" class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-700 ease-in-out cursor-pointer" @click="window.open('{{ $media->getUrl() }}', '_blank')">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Upcoming Events Grid -->
    @if($upcomingEvents->isNotEmpty())
    <section class="py-24 relative">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none" class="mx-auto mb-3"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1"/></svg>
                <h3 class="font-serif text-[11px] uppercase tracking-[0.3em] text-jp-gold font-semibold">Upcoming Engagements</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $upcoming)
                    <a href="{{ route('public.events.show', $upcoming->slug) }}" class="group block" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <div class="relative overflow-hidden aspect-[4/3] mb-4 reveal bg-jp-mist shadow-wave" :class="shown ? 'active' : ''">
                            @if($upcoming->getFirstMediaUrl('hero'))
                                <img src="{{ $upcoming->getFirstMediaUrl('hero', 'card') }}" alt="{{ $upcoming->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                            @endif
                        </div>
                        <div class="reveal reveal-delay-100" :class="shown ? 'active' : ''">
                            <span class="text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40 font-semibold mb-2 block">{{ $upcoming->start_date->format('F j') }}</span>
                            <h4 class="font-serif text-2xl text-jp-indigo group-hover:text-jp-gold transition-colors duration-500 leading-snug">{{ $upcoming->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
