@props(['title' => 'Sign in'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth antialiased">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ $title }} — {{ config("app.name") }}</title>

        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        {{-- Same faces as the public site: Playfair Display for headings,
             Inter for everything else. The Breeze default pulled Figtree from a
             third host, which meant the auth pages did not even share a
             typeface with the site they belong to. --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            .font-serif { font-family: 'Playfair Display', serif; }
        </style>
    </head>

    <body class="min-h-screen bg-jp-cream text-jp-indigo">
        {{-- Two panels on wide screens: the brand side carries the site's own
             canvas so signing in feels like part of the site rather than a
             detour into an admin tool. It collapses away below lg, where the
             form is all that matters. --}}
        <div class="min-h-screen lg:grid lg:grid-cols-2">

            <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-jp-indigo-deep text-jp-cream p-12 xl:p-16">
                <div class="absolute inset-0 opacity-[0.10] jp-seigaiha" aria-hidden="true"></div>
                <x-public.bloom class="absolute -left-24 -bottom-24 w-[28rem] h-[28rem] text-jp-gold opacity-[0.14] animate-lotus-spin" aria-hidden="true" />
                <x-public.bloom class="absolute -right-16 -top-20 w-72 h-72 text-sapientia-secondary opacity-[0.10]" rotate="24" :petals="8" aria-hidden="true" />

                <a href="{{ url('/') }}" class="relative inline-flex items-center gap-3 group w-fit">
                    <span class="grid place-items-center w-11 h-11 rounded-xl bg-white overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="" class="w-full h-full object-contain scale-110">
                    </span>
                    <span class="font-serif text-lg tracking-wide">
                        PUMA <span class="text-jp-gold">Informatics</span>
                    </span>
                </a>

                <div class="relative max-w-md">
                    <p class="text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-5">
                        Members Area
                    </p>
                    <h2 class="font-serif text-4xl xl:text-5xl leading-tight mb-6">
                        The people behind the programme.
                    </h2>
                    <p class="text-jp-cream/60 font-light leading-relaxed">
                        Sign in to manage the newsroom, events, projects and the
                        cabinet archive — or to cast your vote when an election
                        is open.
                    </p>
                </div>

                <p class="relative text-[10px] uppercase tracking-[0.25em] text-jp-cream/30">
                    President University
                </p>
            </aside>

            <main class="relative flex flex-col justify-center px-6 py-14 sm:px-10 lg:px-16 xl:px-24">
                {{-- The floral field carries through to this side too, faintly --}}
                <div class="absolute inset-0 opacity-40 jp-seigaiha pointer-events-none" aria-hidden="true"></div>

                <div class="relative w-full max-w-md mx-auto">
                    {{-- Brand mark, shown only where the panel is hidden --}}
                    <a href="{{ url('/') }}" class="lg:hidden inline-flex items-center gap-3 mb-10">
                        <span class="grid place-items-center w-10 h-10 rounded-xl bg-white ring-1 ring-jp-indigo/10 overflow-hidden">
                            <img src="{{ asset('logo.png') }}" alt="" class="w-full h-full object-contain scale-110">
                        </span>
                        <span class="font-serif text-base tracking-wide">
                            PUMA <span class="text-jp-gold">Informatics</span>
                        </span>
                    </a>

                    {{ $slot }}

                    <p class="mt-12 text-center text-[10px] uppercase tracking-[0.25em] text-jp-indigo/30">
                        <a href="{{ url('/') }}" class="hover:text-jp-gold transition-colors duration-500">
                            &larr; Back to the site
                        </a>
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
