@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \Illuminate\Pagination\LengthAwarePaginator $articles */
@endphp

@section('title', 'The Newsroom')
@section('meta_description', 'Latest editorial publications and announcements from PUMA IT.')

@section('content')
<div class="min-h-screen">

    <!-- Editorial Header with Wave -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 relative overflow-hidden border-b border-sapientia-primary/10 bg-sapientia-ink">
        <div class="absolute inset-0 bg-topo opacity-[0.22] mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="2"/></svg>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl text-sapientia-deep mb-6 reveal" :class="shown ? 'active' : ''">The Newsroom</h1>
            <p class="text-sapientia-deep/70 max-w-2xl mx-auto text-base sm:text-lg reveal reveal-delay-100 font-medium" :class="shown ? 'active' : ''">
                Official announcements, student governance updates, and technology insights.
            </p>
        </div>

        <!-- Bottom divider -->
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sapientia-primary/25 to-transparent"></div>
    </section>

    <!-- Article Grid -->
    <section class="py-16 bg-white/30 bg-striped">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($articles->isEmpty())
                <div class="text-center py-24">
                    <svg width="60" height="20" viewBox="0 0 60 20" fill="none" class="mx-auto mb-6"><path d="M0 10C10 4 20 4 30 10C40 16 50 16 60 10" stroke="#C5A47E" stroke-width="2"/></svg>
                    <p class="text-jp-indigo/60 font-serif text-xl italic font-bold">No publications available at this time.</p>
                </div>
            @else
                <!-- Featured Top Article -->
                @php $featured = $articles->first(); @endphp
                <div class="mb-24 group cursor-pointer" x-data="{ shown: false }" x-intersect.once="shown = true" onclick="window.location='{{ route('public.news.show', $featured->slug) }}'">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">
                        <div class="lg:col-span-8 reveal" :class="shown ? 'active' : ''">
                            <div class="relative overflow-hidden aspect-[16/9] bg-jp-mist shadow-wave border border-jp-indigo/10 group-hover:border-jp-indigo/30 transition-colors duration-700">
                                @if($featured->getFirstMediaUrl('cover'))
                                    <img src="{{ $featured->getFirstMediaUrl('cover') }}" alt="{{ $featured->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                                @endif
                            </div>
                        </div>
                        <div class="lg:col-span-4 reveal reveal-delay-200" :class="shown ? 'active' : ''">
                            <span class="text-[10px] tracking-[0.25em] uppercase text-jp-gold font-black mb-4 block">{{ $featured->published_at->format('F j, Y') }}</span>
                            <h2 class="font-serif text-4xl text-jp-indigo mb-6 leading-tight group-hover:text-jp-gold transition-colors duration-500">{{ $featured->title }}</h2>
                            <p class="text-jp-indigo/80 mb-6 leading-relaxed font-medium border-l-2 border-jp-gold/20 pl-6">
                                {{ Str::limit(strip_tags($featured->content), 150) }}
                            </p>
                            <span class="text-[11px] tracking-[0.2em] uppercase text-jp-indigo font-black relative inline-block group-hover:text-jp-gold transition-colors duration-500">
                                Read Article
                                <span class="absolute -bottom-1 left-0 w-full h-[2px] bg-jp-indigo group-hover:bg-jp-gold transition-all duration-500"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Standard Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    @foreach($articles->skip(1) as $index => $article)
                        <article class="group cursor-pointer flex flex-col" x-data="{ shown: false }" x-intersect.once="shown = true" onclick="window.location='{{ route('public.news.show', $article->slug) }}'">
                            <div class="relative overflow-hidden aspect-[3/4] mb-6 reveal bg-jp-mist border border-jp-indigo/10 group-hover:border-jp-indigo/30 transition-colors duration-700" :class="shown ? 'active' : ''">
                                @if($article->getFirstMediaUrl('cover'))
                                    <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                                @endif
                                <div class="absolute inset-0 bg-jp-indigo/0 group-hover:bg-jp-indigo/5 transition-colors duration-500"></div>
                            </div>
                            <div class="flex-grow reveal reveal-delay-100" :class="shown ? 'active' : ''">
                                <span class="text-[10px] uppercase tracking-[0.25em] text-jp-indigo/60 font-black mb-3 block">{{ $article->published_at->format('M j, Y') }} &mdash; {{ $article->author->name ?? 'Staff' }}</span>
                                <h3 class="font-serif text-2xl text-jp-indigo mb-4 group-hover:text-jp-gold transition-colors duration-500 leading-snug">{{ $article->title }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-20">
                    {{ $articles->links() }}
                </div>
            @endif

        </div>
    </section>

</div>
@endsection
