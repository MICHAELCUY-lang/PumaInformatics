<nav 
    aria-label="Main Navigation"
    role="navigation"
    x-data="{ 
        scrolled: false, 
        menuOpen: false 
    }" 
    x-init="
        $watch('menuOpen', value => {
            if (value) document.body.classList.add('overflow-hidden');
            else document.body.classList.remove('overflow-hidden');
        })
    "
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    class="fixed w-full z-[100] transition-all duration-1000 ease-cinematic top-0"
    :class="{
        'bg-sapientia-cream/80 backdrop-blur-xl': scrolled && !menuOpen,
        'bg-transparent': !scrolled || menuOpen
    }"
    :style="scrolled && !menuOpen ? 'border-bottom: 1px solid rgba(68,138,255,0.05);' : ''"
>
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center transition-all duration-700" :class="(scrolled && !menuOpen) ? 'h-20' : 'h-28'">
            <!-- Brand -->
            <div class="flex-shrink-0 flex items-center relative z-[110]">
                <a href="{{ url('/') }}" aria-label="Home" class="flex items-center gap-4 group focus:outline-none rounded-sm py-2">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-auto transform group-hover:scale-105 transition-transform duration-700 ease-cinematic" :class="menuOpen ? 'drop-shadow-[0_6px_16px_rgba(0,0,0,0.35)]' : ''">
                </a>
            </div>

            <!-- Right Side: Hamburger -->
            <div class="flex items-center gap-8 relative z-[110]">
                <!-- Animated Hamburger Icon -->
                <button 
                    @click="menuOpen = !menuOpen" 
                    type="button" 
                    class="relative w-12 h-12 flex flex-col items-center justify-center group focus:outline-none"
                    aria-label="Toggle Menu"
                >
                    <div class="relative w-6 h-5">
                        <span 
                            class="absolute block w-full h-[2px] transition-all duration-500 ease-cinematic transform origin-center"
                            :class="menuOpen ? 'rotate-45 top-2 bg-white' : 'top-0 bg-sapientia-deep'"
                        ></span>
                        <span 
                            class="absolute block w-full h-[2px] top-2 transition-all duration-500 ease-cinematic"
                            :class="menuOpen ? 'opacity-0 translate-x-4 bg-white' : 'opacity-100 bg-sapientia-deep'"
                        ></span>
                        <span 
                            class="absolute block w-full h-[2px] transition-all duration-500 ease-cinematic transform origin-center"
                            :class="menuOpen ? '-rotate-45 top-2 bg-white' : 'top-4 bg-sapientia-deep'"
                        ></span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Full Screen Menu Overlay -->
    <div 
        x-show="menuOpen"
        x-transition:enter="transition ease-out duration-800"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-600"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-sapientia-deep z-[105] overflow-y-auto"
        style="display: none;"
    >
        <!-- Background Artistic Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 bg-topo opacity-[0.08] mix-blend-screen"></div>
            <div class="absolute inset-0 bg-sapientia-ink opacity-[0.55] mix-blend-screen"></div>
            <!-- Large rotating star -->
            <div class="absolute -bottom-1/4 -right-1/4 w-[800px] h-[800px] opacity-[0.03] animate-lotus-spin">
                <svg viewBox="0 0 200 200" fill="none" class="w-full h-full text-white">
                    <g transform="translate(100,100)">
                        @for($i = 0; $i < 8; $i++)
                        <path d="M0,0 Q15,-30 0,-60 Q-15,-30 0,0" fill="currentColor" transform="rotate({{ $i * 45 }})" />
                        @endfor
                    </g>
                </svg>
            </div>
            <!-- Seigaiha pattern -->
            <div class="absolute top-0 left-0 w-full h-full jp-seigaiha opacity-[0.02]"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col md:flex-row">
            <!-- Left Side: Large Branding/Decoration -->
            <div class="hidden md:flex md:w-1/3 min-h-screen border-r border-white/5 items-center justify-center p-20">
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 mb-10 opacity-20">
                        <svg viewBox="0 0 200 200" fill="none" class="w-full h-full text-white">
                            <g transform="translate(100,100)">
                                @for($i = 0; $i < 8; $i++)
                                <path d="M0,0 Q15,-30 0,-60 Q-15,-30 0,0" fill="currentColor" transform="rotate({{ $i * 45 }})" />
                                @endfor
                            </g>
                        </svg>
                    </div>
                    <h2 class="font-serif text-3xl text-white/10 uppercase tracking-[0.5em] vertical-text">SAPIENTIA</h2>
                </div>
            </div>

            <!-- Right Side: Navigation Links -->
            <div class="flex-grow min-h-screen flex flex-col justify-start md:justify-center px-6 sm:px-10 md:px-24 py-24 sm:py-28 md:py-40">
                <div class="flex flex-col items-start space-y-5 sm:space-y-6 md:space-y-10">
                    @php
                        $navItems = [
                            ['label' => 'Home', 'route' => url('/')],
                            ['label' => 'The Newsroom', 'route' => route('public.news.index')],
                            ['label' => 'Exhibitions', 'route' => route('public.events.index')],
                            ['label' => 'Project Archive', 'route' => route('public.projects.index')],
                            ['label' => 'The Cabinet', 'route' => route('public.cabinet.index')],
                            ['label' => 'Aspirations', 'route' => route('public.aspirations.create')],
                        ];
                    @endphp

                    @foreach($navItems as $index => $item)
                        <a 
                            href="{{ $item['route'] }}" 
                            @click="menuOpen = false"
                            class="group relative flex items-center gap-10 overflow-hidden"
                            x-show="menuOpen"
                            x-transition:enter="transition ease-out duration-700 delay-{{ $index * 100 }}"
                            x-transition:enter-start="opacity-0 translate-x-20"
                            x-transition:enter-end="opacity-100 translate-x-0"
                        >
                            <span class="font-sans text-[10px] sm:text-xs md:text-sm tracking-[0.5em] text-sapientia-primary font-bold opacity-0 group-hover:opacity-100 transition-all duration-700 -translate-x-10 group-hover:translate-x-0">0{{ $index + 1 }}</span>
                            <span class="font-serif text-3xl sm:text-4xl md:text-7xl lg:text-8xl leading-[1.05] text-white/30 group-hover:text-white transition-all duration-700 ease-cinematic transform group-hover:translate-x-4">{{ $item['label'] }}</span>
                            <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-sapientia-primary group-hover:w-full transition-all duration-1000 ease-cinematic"></span>
                        </a>
                    @endforeach
                </div>

                <!-- Footer in Menu -->
                <div class="mt-20 md:mt-32 flex flex-col md:flex-row gap-12 items-start md:items-center">
                    <div class="flex items-center gap-10">
                        <a href="#" class="group flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-sapientia-primary group-hover:bg-sapientia-primary transition-all duration-500">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </span>
                            <span class="text-[10px] tracking-[0.4em] uppercase font-bold text-white/60 group-hover:text-white transition-colors">Instagram</span>
                        </a>
                        <a href="#" class="group flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center group-hover:border-sapientia-primary group-hover:bg-sapientia-primary transition-all duration-500">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </span>
                            <span class="text-[10px] tracking-[0.4em] uppercase font-bold text-white/60 group-hover:text-white transition-colors">LinkedIn</span>
                        </a>
                    </div>
                    <div class="w-px h-10 bg-white/5 hidden md:block"></div>
                    <p class="text-[10px] tracking-[0.4em] uppercase font-bold text-white/20">&copy; {{ date('Y') }} Sapientia Cabinet</p>
                </div>
            </div>
        </div>
    </div>
</nav>
