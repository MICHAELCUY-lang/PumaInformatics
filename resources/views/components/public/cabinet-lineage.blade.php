{{--
    Cabinet lineage strip.

    Each generation is a clickable emblem that opens the cabinet page scoped to
    that term. Cabinets own their departments and members, so this is the entry
    point into each generation's own archive rather than a filter over one
    merged roster.

    Expects: $cabinetLineage (Cabinet collection, newest first), $activeSlug
--}}
@props(['cabinetLineage' => collect(), 'activeSlug' => null, 'compact' => false])

@if($cabinetLineage->count() > 1)
<section @class([
             'relative overflow-hidden border-b border-jp-indigo/5',
             'py-20 md:py-24 bg-jp-cream-warm' => ! $compact,
             'py-12 bg-white/30' => $compact,
         ])
         aria-labelledby="lineage-heading">

    {{-- Floral wash: two soft blooms, low opacity, purely decorative --}}
    @unless($compact)
        <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
            <x-public.bloom class="absolute -left-24 -top-16 w-80 h-80 text-jp-gold opacity-[0.07]" />
            <x-public.bloom class="absolute -right-28 -bottom-24 w-96 h-96 text-jp-indigo opacity-[0.05]" rotate="150" />
        </div>
    @endunless

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        @if(! $compact)
            <div class="text-center mb-14">
                <span class="block text-[10px] uppercase tracking-[0.45em] text-jp-gold font-semibold mb-4">
                    Our Lineage
                </span>
                <h2 id="lineage-heading" class="font-serif text-3xl md:text-4xl text-jp-indigo">
                    Every Cabinet, Every Chapter
                </h2>
                <p class="mt-4 text-jp-indigo/50 font-light max-w-xl mx-auto leading-relaxed">
                    Each generation kept its own structure and ran its own programme.
                    Choose a cabinet to see the people and the events of that term.
                </p>
            </div>
        @else
            <h2 id="lineage-heading" class="sr-only">Choose a cabinet generation</h2>
        @endif

        <ul @class([
                'flex flex-wrap justify-center items-start',
                'gap-8 md:gap-12 lg:gap-16' => ! $compact,
                'gap-6 md:gap-10' => $compact,
            ])>
            @foreach($cabinetLineage as $cabinet)
                @php
                    $isCurrent = $activeSlug ? $cabinet->slug === $activeSlug : $cabinet->is_active;
                @endphp
                <li @class(['w-36 sm:w-40' => ! $compact, 'w-28 sm:w-32' => $compact])>
                    <a href="{{ route('public.cabinet.index', ['cabinet' => $cabinet->slug]) }}"
                       @class([
                           'group block text-center focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold focus-visible:ring-offset-4 focus-visible:ring-offset-jp-cream-warm rounded-sm',
                       ])
                       @if($isCurrent) aria-current="page" @endif>

                        <span @class([
                            'relative mx-auto flex items-center justify-center rounded-full overflow-hidden transition-all duration-700 ease-cinematic',
                            'w-24 h-24 sm:w-28 sm:h-28' => ! $compact,
                            'w-16 h-16 sm:w-20 sm:h-20' => $compact,
                            'ring-1 ring-jp-indigo/10 group-hover:ring-jp-gold',
                            'bg-white shadow-wave group-hover:shadow-wave-lg group-hover:-translate-y-1',
                            'ring-2 ring-jp-gold' => $isCurrent,
                        ])>
                            <img src="{{ $cabinet->logoUrl() }}"
                                 alt=""
                                 loading="lazy"
                                 class="w-full h-full object-contain p-4 transition-transform duration-700 ease-cinematic group-hover:scale-105">
                        </span>

                        <span class="mt-5 block font-serif text-lg text-jp-indigo group-hover:text-jp-gold transition-colors duration-500">
                            {{ $cabinet->name }}
                        </span>

                        <span class="mt-1 block text-[10px] uppercase tracking-[0.25em] text-jp-indigo/40">
                            {{ $cabinet->term_year }}
                        </span>

                        @if($cabinet->is_active)
                            <span class="mt-2 inline-flex items-center gap-1.5 text-[9px] uppercase tracking-[0.2em] text-jp-gold font-semibold">
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-jp-gold"></span>
                                Current
                            </span>
                        @elseif($cabinet->generation)
                            <span class="mt-2 block text-[9px] uppercase tracking-[0.2em] text-jp-indigo/30">
                                Gen {{ $cabinet->generation }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif
