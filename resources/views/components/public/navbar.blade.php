@php
    $links = [
        ['label' => 'Newsroom',  'route' => route('public.news.index'),        'pattern' => 'news*'],
        ['label' => 'Events',    'route' => route('public.events.index'),      'pattern' => 'events*'],
        ['label' => 'Projects',  'route' => route('public.projects.index'),    'pattern' => 'projects*'],
        ['label' => 'Cabinet',   'route' => route('public.cabinet.index'),     'pattern' => 'cabinet*'],
        ['label' => 'Aspirations','route' => route('public.aspirations.create'),'pattern' => 'aspirations*'],
    ];
@endphp

{{--
    Dynamic Island navigation.

    A floating dark pill rather than a full-width bar: it keeps the cream page
    visually uninterrupted and reads as an object sitting above the content.

    Two behaviours, one element:
      desktop — links sit inline inside the pill
      mobile  — the pill expands downward into a rounded card, the way the
                iPhone island grows to hold more content

    The expansion animates max-height and border-radius together on an iOS-style
    easing curve, which is what makes it feel like one object changing shape
    instead of a panel appearing.
--}}
<nav
    x-data="{
        open: false,
        scrolled: false,
        close() { this.open = false },
    }"
    x-init="$watch('open', v => document.body.style.overflow = v ? 'hidden' : '')"
    @scroll.window="scrolled = window.pageYOffset > 24"
    @keydown.escape.window="close()"
    @click.outside="close()"
    class="fixed inset-x-0 top-0 z-50 flex justify-center pointer-events-none"
    aria-label="Primary">

    <div class="w-full max-w-6xl px-4 sm:px-6 pointer-events-auto"
         :class="scrolled ? 'pt-3' : 'pt-5 sm:pt-7'"
         style="transition: padding-top .5s cubic-bezier(.32,.72,0,1)">

        <div
            class="relative mx-auto overflow-hidden bg-jp-ink/90 backdrop-blur-2xl ring-1 ring-white/10 text-jp-cream"
            :class="{
                'rounded-[36px] shadow-[0_18px_50px_-12px_rgba(10,17,40,0.55)]': !open,
                'rounded-[32px] shadow-[0_28px_70px_-14px_rgba(10,17,40,0.7)]': open,
            }"
            style="transition: border-radius .5s cubic-bezier(.32,.72,0,1), box-shadow .5s cubic-bezier(.32,.72,0,1), max-width .5s cubic-bezier(.32,.72,0,1)"
            :style="scrolled && !open ? 'max-width: 52rem' : 'max-width: 72rem'">

            {{-- Collapsed row: always visible --}}
            <div class="flex items-center justify-between gap-4 px-4 sm:px-5"
                 :class="scrolled && !open ? 'h-14' : 'h-16'"
                 style="transition: height .5s cubic-bezier(.32,.72,0,1)">

                <a href="{{ url('/') }}"
                   class="flex items-center gap-3 shrink-0 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold"
                   aria-label="PUMA Informatics — home">
                    {{-- Rounded square rather than a circle: the mark is a white
                         rounded card, so a circular crop would clip its corners
                         and leave a visible white square inside a ring. --}}
                    <span class="grid place-items-center w-9 h-9 rounded-xl bg-white overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="" class="w-full h-full object-contain scale-110">
                    </span>
                    <span class="hidden sm:block font-serif text-[15px] leading-none tracking-wide">
                        PUMA <span class="text-jp-gold">Informatics</span>
                    </span>
                </a>

                {{-- Desktop links --}}
                <ul class="hidden lg:flex items-center gap-1">
                    @foreach($links as $link)
                        @php $active = request()->is($link['pattern']); @endphp
                        <li>
                            <a href="{{ $link['route'] }}"
                               @class([
                                   'relative block px-4 py-2 rounded-full text-[12px] uppercase tracking-[0.18em] transition-colors duration-500',
                                   'text-jp-cream/60 hover:text-jp-cream hover:bg-white/5' => ! $active,
                                   'text-jp-ink bg-jp-gold' => $active,
                               ])
                               @if($active) aria-current="page" @endif>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="flex items-center gap-2 shrink-0">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="hidden sm:inline-flex items-center h-9 px-4 rounded-full bg-white/10 hover:bg-white/20 text-[11px] uppercase tracking-[0.18em] transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex items-center h-9 px-4 rounded-full bg-white/10 hover:bg-white/20 text-[11px] uppercase tracking-[0.18em] transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold">
                            Sign in
                        </a>
                    @endauth

                    {{-- Expand toggle: the island's own control --}}
                    <button type="button"
                            @click="open = !open"
                            class="lg:hidden grid place-items-center w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold"
                            :aria-expanded="open ? 'true' : 'false'"
                            aria-controls="island-menu">
                        <span class="sr-only" x-text="open ? 'Close menu' : 'Open menu'">Open menu</span>
                        <span class="relative block w-4 h-3" aria-hidden="true">
                            <span class="absolute inset-x-0 top-0 h-[1.5px] bg-jp-cream rounded-full"
                                  style="transition: transform .45s cubic-bezier(.32,.72,0,1), opacity .3s"
                                  :style="open ? 'transform: translateY(5.5px) rotate(45deg)' : ''"></span>
                            <span class="absolute inset-x-0 top-[5.5px] h-[1.5px] bg-jp-cream rounded-full"
                                  style="transition: opacity .3s"
                                  :style="open ? 'opacity: 0' : ''"></span>
                            <span class="absolute inset-x-0 bottom-0 h-[1.5px] bg-jp-cream rounded-full"
                                  style="transition: transform .45s cubic-bezier(.32,.72,0,1), opacity .3s"
                                  :style="open ? 'transform: translateY(-5.5px) rotate(-45deg)' : ''"></span>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Expanded content. max-height animates so the island grows as one
                 shape; overflow-hidden on the parent keeps the corners clean. --}}
            <div id="island-menu"
                 class="lg:hidden overflow-hidden"
                 style="transition: max-height .5s cubic-bezier(.32,.72,0,1), opacity .35s"
                 :style="open ? 'max-height: 32rem; opacity: 1' : 'max-height: 0; opacity: 0'"
                 :aria-hidden="open ? 'false' : 'true'">

                <div class="px-3 pb-3 pt-1">
                    <div class="h-px bg-white/10 mx-2 mb-2"></div>

                    <ul class="space-y-0.5">
                        @foreach($links as $i => $link)
                            @php $active = request()->is($link['pattern']); @endphp
                            <li>
                                <a href="{{ $link['route'] }}"
                                   @click="close()"
                                   @class([
                                       'flex items-center justify-between px-4 py-3 rounded-2xl transition-colors duration-400',
                                       'hover:bg-white/5' => ! $active,
                                       'bg-white/10' => $active,
                                   ])
                                   style="transition-delay: {{ $i * 35 }}ms"
                                   :style="open ? '' : 'transition-delay: 0ms'"
                                   @if($active) aria-current="page" @endif>
                                    <span @class([
                                        'font-serif text-lg',
                                        'text-jp-cream' => ! $active,
                                        'text-jp-gold' => $active,
                                    ])>{{ $link['label'] }}</span>
                                    <svg class="w-4 h-4 text-jp-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-2 px-4 pt-3 border-t border-white/10 sm:hidden">
                        @auth
                            <a href="{{ route('dashboard') }}" @click="close()"
                               class="block py-2 text-[11px] uppercase tracking-[0.22em] text-jp-gold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" @click="close()"
                               class="block py-2 text-[11px] uppercase tracking-[0.22em] text-jp-gold">Sign in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- No spacer here: the bar was already fixed, so every page carries its own
     top padding (pt-20 on the hero, pt-24/32 on inner pages). Adding one would
     double the gap. --}}
