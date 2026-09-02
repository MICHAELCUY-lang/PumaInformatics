@php
    $links = [
        ['label' => 'Newsroom',   'route' => route('public.news.index'),         'pattern' => 'news*'],
        ['label' => 'Events',     'route' => route('public.events.index'),       'pattern' => 'events*'],
        ['label' => 'Projects',   'route' => route('public.projects.index'),     'pattern' => 'projects*'],
        ['label' => 'Cabinet',    'route' => route('public.cabinet.index'),      'pattern' => 'cabinet*'],
        ['label' => 'Aspirations','route' => route('public.aspirations.create'), 'pattern' => 'aspirations*'],
    ];
@endphp

{{--
    Dynamic Island navigation — light variant.

    A translucent cream pill that lets the page tint through rather than sitting
    on top of it as a dark slab. It firms up slightly once scrolled, which is
    the only cue needed to keep the links legible over darker sections.

    Two behaviours, one element:
      lg and up — the pill hugs its contents (w-fit) and the links sit inline
      below lg  — the pill spans the container and expands downward into a card,
                  the way the iPhone island grows to hold more content

    w-fit matters: an earlier version pinned a max-width that shrank on scroll,
    and with overflow-hidden the right-hand links were silently clipped.
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
    class="fixed inset-x-0 top-0 z-50 flex justify-center pointer-events-none"
    aria-label="Primary">

    <div class="w-full max-w-[min(72rem,calc(100vw-1.5rem))] pointer-events-auto"
         :class="scrolled ? 'pt-3' : 'pt-5 sm:pt-7'"
         style="transition: padding-top .5s cubic-bezier(.32,.72,0,1)">

        <div
            @click.outside="close()"
            class="relative mx-auto overflow-hidden w-full lg:w-fit backdrop-blur-2xl text-jp-indigo
                   bg-jp-cream/55 ring-1 ring-jp-indigo/10"
            :class="{
                'rounded-[36px] shadow-[0_10px_40px_-18px_rgba(27,58,92,0.35)]': !open,
                'rounded-[32px] shadow-[0_24px_60px_-18px_rgba(27,58,92,0.45)]': open,
                'bg-jp-cream/85 ring-jp-indigo/15': scrolled || open,
            }"
            style="transition: border-radius .5s cubic-bezier(.32,.72,0,1), box-shadow .5s cubic-bezier(.32,.72,0,1), background-color .5s ease">

            {{-- Collapsed row: always visible --}}
            <div class="flex items-center justify-between gap-3 lg:gap-6 px-3 sm:px-4"
                 :class="scrolled && !open ? 'h-14' : 'h-16'"
                 style="transition: height .5s cubic-bezier(.32,.72,0,1)">

                <a href="{{ url('/') }}"
                   class="flex items-center gap-2.5 shrink-0 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold"
                   aria-label="PUMA Informatics — home">
                    {{-- The mark ships as a white rounded card, so it gets a white
                         tile rather than a circular crop that would clip it. --}}
                    <span class="grid place-items-center w-9 h-9 rounded-xl bg-white ring-1 ring-jp-indigo/10 overflow-hidden">
                        <img src="{{ asset('logo.png') }}" alt="" class="w-full h-full object-contain scale-110">
                    </span>
                    <span class="hidden sm:block font-serif text-[15px] leading-none tracking-wide whitespace-nowrap">
                        PUMA <span class="text-jp-gold">Informatics</span>
                    </span>
                </a>

                {{-- Desktop links --}}
                <ul class="hidden lg:flex items-center gap-0.5">
                    @foreach($links as $link)
                        @php $active = request()->is($link['pattern']); @endphp
                        <li>
                            <a href="{{ $link['route'] }}"
                               @class([
                                   'block px-3.5 py-2 rounded-full text-[11px] uppercase tracking-[0.16em] whitespace-nowrap transition-colors duration-500',
                                   'text-jp-indigo/55 hover:text-jp-indigo hover:bg-jp-indigo/5' => ! $active,
                                   'text-jp-indigo bg-jp-gold/30 ring-1 ring-jp-gold/40' => $active,
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
                           class="hidden sm:inline-flex items-center h-9 px-4 rounded-full bg-jp-indigo text-jp-cream hover:bg-jp-indigo-deep text-[10px] uppercase tracking-[0.18em] whitespace-nowrap transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex items-center h-9 px-4 rounded-full bg-jp-indigo text-jp-cream hover:bg-jp-indigo-deep text-[10px] uppercase tracking-[0.18em] whitespace-nowrap transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold">
                            Sign in
                        </a>
                    @endauth

                    {{-- Expand toggle: the island's own control --}}
                    <button type="button"
                            @click="open = !open"
                            class="lg:hidden grid place-items-center w-9 h-9 rounded-full bg-jp-indigo/5 hover:bg-jp-indigo/10 transition-colors duration-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold"
                            :aria-expanded="open ? 'true' : 'false'"
                            aria-controls="island-menu">
                        <span class="sr-only" x-text="open ? 'Close menu' : 'Open menu'">Open menu</span>
                        <span class="relative block w-4 h-3" aria-hidden="true">
                            <span class="absolute inset-x-0 top-0 h-[1.5px] bg-jp-indigo rounded-full"
                                  style="transition: transform .45s cubic-bezier(.32,.72,0,1)"
                                  :style="open ? 'transform: translateY(5.5px) rotate(45deg)' : ''"></span>
                            <span class="absolute inset-x-0 top-[5.5px] h-[1.5px] bg-jp-indigo rounded-full"
                                  style="transition: opacity .3s"
                                  :style="open ? 'opacity: 0' : ''"></span>
                            <span class="absolute inset-x-0 bottom-0 h-[1.5px] bg-jp-indigo rounded-full"
                                  style="transition: transform .45s cubic-bezier(.32,.72,0,1)"
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

                <div class="px-2.5 pb-3 pt-1">
                    <div class="h-px bg-jp-indigo/10 mx-2 mb-2"></div>

                    <ul class="space-y-0.5">
                        @foreach($links as $link)
                            @php $active = request()->is($link['pattern']); @endphp
                            <li>
                                <a href="{{ $link['route'] }}"
                                   @click="close()"
                                   @class([
                                       'flex items-center justify-between px-4 py-3 rounded-2xl transition-colors duration-500',
                                       'hover:bg-jp-indigo/5' => ! $active,
                                       'bg-jp-gold/20 ring-1 ring-jp-gold/30' => $active,
                                   ])
                                   @if($active) aria-current="page" @endif>
                                    <span class="font-serif text-lg text-jp-indigo">{{ $link['label'] }}</span>
                                    <svg class="w-4 h-4 text-jp-indigo/25" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-2 pt-3 px-4 border-t border-jp-indigo/10 sm:hidden">
                        @auth
                            <a href="{{ route('dashboard') }}" @click="close()"
                               class="block py-1 text-[11px] uppercase tracking-[0.22em] text-jp-gold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" @click="close()"
                               class="block py-1 text-[11px] uppercase tracking-[0.22em] text-jp-gold">Sign in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- No spacer here: the bar was already fixed, so every page carries its own
     top padding (pt-20 on the hero, pt-24/32 on inner pages). --}}
