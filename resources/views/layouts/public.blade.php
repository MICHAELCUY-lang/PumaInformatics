<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth antialiased jp-scrollbar">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <x-public.seo 
            title="{{ View::hasSection('title') ? View::getSection('title') : '' }}"
            description="{{ View::hasSection('meta_description') ? View::getSection('meta_description') : null }}"
            image="{{ View::hasSection('meta_image') ? View::getSection('meta_image') : null }}"
            type="{{ View::hasSection('meta_type') ? View::getSection('meta_type') : 'website' }}"
        >
            @stack('seo')
        </x-public.seo>

        <!-- Typography with Preload -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap"></noscript>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Base Japanese Theme */
            body {
                background-color: #FBF9F4; /* jp.cream */
                color: #1B3A5C; /* jp.indigo */
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6, .font-serif {
                font-family: 'Playfair Display', serif;
            }
            
            /* Cinematic Scroll Reveal Utilities */
            .reveal {
                opacity: 0;
                transform: translateY(2rem);
                transition: opacity 1s cubic-bezier(0.19, 1, 0.22, 1), 
                            transform 1s cubic-bezier(0.19, 1, 0.22, 1);
            }
            .reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
            .reveal-delay-100 { transition-delay: 100ms; }
            .reveal-delay-200 { transition-delay: 200ms; }
            .reveal-delay-300 { transition-delay: 300ms; }
            
            /* Smooth Hide Scrollbar for carousels */
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

            /* Selection color */
            ::selection {
                background-color: #1B3A5C;
                color: #FBF9F4;
            }
        </style>
    </head>
    <body class="min-h-screen overflow-x-hidden">
        <div class="fixed inset-0 pointer-events-none bg-topo opacity-[0.55] mix-blend-multiply"></div>
        <div class="fixed inset-0 pointer-events-none bg-noise z-[5]"></div>
        <div class="fixed inset-0 pointer-events-none jp-seigaiha opacity-[0.012]"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <x-public.navbar />

            <main class="flex-grow">
                @yield('content')
            </main>

            <x-public.footer />
        </div>
    </body>
</html>
