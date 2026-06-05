@extends('layouts.public')

@section('title', 'Too Many Requests')

@section('content')
<div class="bg-institutional-navy min-h-[80vh] flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] border border-white/20 rounded-full"></div>
    </div>

    <div class="relative z-10 text-center px-4 max-w-2xl mx-auto" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
        <span class="inline-block text-institutional-gold text-sm tracking-widest uppercase font-semibold mb-6 reveal" :class="shown ? 'active' : ''">
            Error 429
        </span>
        <h1 class="font-serif text-5xl md:text-7xl text-white mb-8 reveal reveal-delay-100" :class="shown ? 'active' : ''">
            Too Many Requests
        </h1>
        <p class="text-white/70 text-lg md:text-xl mb-12 font-light leading-relaxed reveal reveal-delay-200" :class="shown ? 'active' : ''">
            You have sent too many requests in a given amount of time. Please wait a moment before trying again.
        </p>
        <div class="reveal reveal-delay-300" :class="shown ? 'active' : ''">
            <a href="{{ route('public.home') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-institutional-navy text-sm tracking-widest uppercase font-semibold hover:bg-institutional-gold hover:text-white transition-colors duration-300">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
