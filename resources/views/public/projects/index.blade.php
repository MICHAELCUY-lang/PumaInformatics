@extends('layouts.public')

@php
    use Illuminate\Support\Str;
    /** @var \Illuminate\Pagination\LengthAwarePaginator $projects */
@endphp

@section('title', 'Project Archive')
@section('meta_description', 'An archive of technological innovation and student-led digital projects.')

@section('content')
<div class="min-h-screen">

    <!-- Header -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 relative overflow-hidden border-b border-sapientia-primary/10 bg-sapientia-ink">
        <div class="absolute inset-0 bg-topo opacity-[0.22] mix-blend-multiply"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#448AFF" stroke-width="2"/></svg>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl text-sapientia-deep mb-6 reveal" :class="shown ? 'active' : ''">Project Archive</h1>
            <p class="text-sapientia-deep/70 max-w-2xl mx-auto text-base sm:text-lg reveal reveal-delay-100 font-medium" :class="shown ? 'active' : ''">
                An exhibition of technological innovation and digital craftsmanship.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sapientia-primary/25 to-transparent"></div>
    </section>

    <!-- Projects -->
    <section class="py-16 bg-white/30 bg-striped">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($projects->isEmpty())
                <div class="text-center py-24">
                    <svg width="60" height="20" viewBox="0 0 60 20" fill="none" class="mx-auto mb-6"><path d="M0 10C10 4 20 4 30 10C40 16 50 16 60 10" stroke="#C5A47E" stroke-width="2"/></svg>
                    <p class="text-jp-indigo/60 font-serif text-xl italic font-bold">The archive is currently being updated.</p>
                </div>
            @else
                <div class="space-y-32">
                    @foreach($projects as $index => $project)
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center" x-data="{ shown: false }" x-intersect.once="shown = true">
                            <!-- Alternating Asymmetrical Layout -->
                            <div class="lg:col-span-7 {{ $index % 2 !== 0 ? 'lg:order-2' : '' }} reveal" :class="shown ? 'active' : ''">
                                <a href="{{ route('public.projects.show', $project->slug) }}" class="block relative overflow-hidden aspect-[16/10] bg-jp-mist shadow-wave group border border-jp-indigo/10 hover:border-jp-indigo/30 transition-colors duration-700">
                                    @if($project->getFirstMediaUrl('gallery'))
                                        <img src="{{ $project->getFirstMediaUrl('gallery', 'showcase') }}" alt="{{ $project->title }}" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-cinematic ease-cinematic">
                                    @endif
                                    <div class="absolute inset-0 bg-jp-indigo/5 group-hover:bg-transparent transition-colors duration-500"></div>
                                </a>
                            </div>
                            <div class="lg:col-span-5 {{ $index % 2 !== 0 ? 'lg:order-1' : '' }} flex flex-col justify-center reveal reveal-delay-200" :class="shown ? 'active' : ''">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-[10px] uppercase tracking-[0.25em] text-jp-gold font-black">{{ $project->category->name ?? 'Technology' }}</span>
                                    <svg width="24" height="8" viewBox="0 0 24 8" fill="none"><path d="M0 4C4 1 8 1 12 4C16 7 20 7 24 4" stroke="#C5A47E" stroke-width="1.5" opacity="0.8"/></svg>
                                    <span class="text-[10px] uppercase tracking-[0.25em] text-jp-indigo/60 font-bold">{{ $project->status }}</span>
                                </div>
                                <h2 class="font-serif text-4xl text-jp-indigo mb-6">
                                    <a href="{{ route('public.projects.show', $project->slug) }}" class="hover:text-jp-gold transition-colors duration-500">{{ $project->title }}</a>
                                </h2>
                                <p class="text-jp-indigo/80 font-medium leading-relaxed mb-8 border-l-2 border-jp-gold/20 pl-6">
                                    {{ Str::limit(strip_tags($project->description), 180) }}
                                </p>
                                
                                @if($project->technologies->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 mb-8">
                                        @foreach($project->technologies->take(4) as $tech)
                                            <span class="px-3 py-1 bg-jp-cream-warm text-jp-indigo text-[10px] tracking-[0.15em] uppercase border border-jp-indigo/20 font-bold hover:bg-jp-gold/10 transition-colors">{{ $tech->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <a href="{{ route('public.projects.show', $project->slug) }}" class="inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.25em] text-jp-indigo font-black hover:text-jp-gold transition-colors duration-500 group">
                                    View Case Study
                                    <span class="w-8 h-[2px] bg-jp-indigo group-hover:bg-jp-gold group-hover:w-12 transition-all duration-500"></span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-20">
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </section>

</div>
@endsection
