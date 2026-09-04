@extends('layouts.public')

@php
    /** @var \Illuminate\Support\Collection $partners */
@endphp

@section('title', 'Industry Partners')
@section('meta_description', 'Our institutional partnerships and industry collaborations.')

@section('content')
<div class="bg-jp-cream min-h-screen">

    <!-- Header -->
    <section class="pt-32 pb-20 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1/3 h-full">
            <svg viewBox="0 0 300 500" fill="none" class="w-full h-full opacity-[0.04]" preserveAspectRatio="xMinYMid slice">
                <path d="M300 0C250 100 200 150 150 250C100 350 150 400 250 450L300 450L300 0Z" fill="#1B3A5C"/>
            </svg>
        </div>
        <div class="absolute bottom-0 right-0 w-80 h-80 jp-seigaiha opacity-40"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" x-data="{ shown: false }" x-intersect.once="shown = true">
            <div class="flex justify-center mb-6 reveal" :class="shown ? 'active' : ''">
                <svg width="40" height="12" viewBox="0 0 40 12" fill="none"><path d="M0 6C7 2 13 2 20 6C27 10 33 10 40 6" stroke="#C5A47E" stroke-width="1.5"/></svg>
            </div>
            <h1 class="font-serif text-5xl md:text-7xl text-jp-indigo mb-6 reveal" :class="shown ? 'active' : ''">Industry Partners</h1>
            <p class="text-jp-indigo/50 max-w-2xl mx-auto text-lg reveal reveal-delay-100" :class="shown ? 'active' : ''">
                Organizations and institutions that support our mission for technological excellence.
            </p>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-jp-indigo/10 to-transparent"></div>
    </section>

    <!-- Partners -->
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($partners->isEmpty())
                <div class="text-center py-24">
                    <svg width="60" height="20" viewBox="0 0 60 20" fill="none" class="mx-auto mb-6"><path d="M0 10C10 4 20 4 30 10C40 16 50 16 60 10" stroke="#C5A47E" stroke-width="1"/></svg>
                    <p class="text-jp-indigo/40 font-serif text-xl italic">Partners directory is currently being updated.</p>
                </div>
            @else
                @foreach($partners as $categoryName => $group)
                    <div class="mb-24 last:mb-0" x-data="{ shown: false }" x-intersect.once="shown = true">
                        <div class="flex items-center gap-6 mb-12 reveal" :class="shown ? 'active' : ''">
                            <h2 class="font-serif text-3xl text-jp-indigo">{{ $categoryName }}</h2>
                            <div class="flex-grow h-px bg-gradient-to-r from-jp-indigo/15 to-transparent"></div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            @foreach($group as $index => $partner)
                                <div class="group flex flex-col items-center justify-center p-8 bg-white border border-jp-indigo/5 hover:border-jp-gold hover:shadow-wave transition-all duration-500 reveal" style="transition-delay: {{ ($index % 4) * 100 }}ms;" :class="shown ? 'active' : ''">
                                    <div class="relative w-32 h-32 mb-6 flex items-center justify-center">
                                        @if($partner->getFirstMediaUrl('logo'))
                                            <img src="{{ $partner->getFirstMediaUrl('logo', 'standard') }}" alt="{{ $partner->name }}" class="max-w-full max-h-full object-contain opacity-90 group-hover:opacity-100 transition-all duration-700">
                                        @else
                                            <div class="w-24 h-24 bg-jp-mist flex items-center justify-center">
                                                <span class="font-serif text-2xl text-jp-indigo/30">{{ substr($partner->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="font-serif text-lg text-jp-indigo text-center mb-2">{{ $partner->name }}</h3>
                                    @if($partner->website_url)
                                        <a href="{{ $partner->website_url }}" target="_blank" class="text-[10px] tracking-[0.25em] uppercase text-jp-indigo/30 hover:text-jp-gold transition-colors duration-500 block text-center truncate w-full">Visit Site</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </section>

</div>
@endsection
