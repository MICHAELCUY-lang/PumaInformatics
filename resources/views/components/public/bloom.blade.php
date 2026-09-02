{{--
    Decorative bloom.

    The site's ornament was seigaiha — Japanese overlapping waves. This replaces
    it with a floral motif in the same spirit: line-art, single colour, low
    opacity, never competing with the content.

    Drawn with currentColor so a caller sets the hue through a text-* class, and
    marked aria-hidden because it carries no meaning.

    Props:
      rotate — degrees, to vary repeated instances so they don't look stamped
      petals — 6 (default) or 8
--}}
@props(['rotate' => 0, 'petals' => 6])

@php
    $count = (int) $petals === 8 ? 8 : 6;
    $step = 360 / $count;
@endphp

<svg {{ $attributes->merge(['class' => 'block']) }}
     viewBox="0 0 200 200"
     fill="none"
     xmlns="http://www.w3.org/2000/svg"
     aria-hidden="true"
     focusable="false">
    <g transform="rotate({{ $rotate }} 100 100)" stroke="currentColor" stroke-width="1.1" stroke-linecap="round">
        @for($i = 0; $i < $count; $i++)
            <g transform="rotate({{ $i * $step }} 100 100)">
                {{-- One petal: a lens shape formed by two mirrored arcs --}}
                <path d="M100 100 C 78 74, 78 40, 100 18 C 122 40, 122 74, 100 100 Z"
                      fill="currentColor"
                      fill-opacity="0.10" />
                {{-- Inner vein, shorter, to give the petal some structure --}}
                <path d="M100 92 C 94 72, 94 50, 100 32" stroke-opacity="0.55" />
            </g>
        @endfor

        {{-- Calyx: a small ring at the centre --}}
        <circle cx="100" cy="100" r="9" fill="currentColor" fill-opacity="0.18" stroke-opacity="0.7" />
        <circle cx="100" cy="100" r="3.2" fill="currentColor" fill-opacity="0.45" stroke="none" />
    </g>
</svg>
