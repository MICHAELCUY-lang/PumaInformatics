@props(['disabled' => false])

{{-- Palette-aligned input. Breeze shipped grey borders with an indigo focus
     ring, which belonged to no part of this site. --}}
<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full rounded-xl border-jp-indigo/15 bg-white/70 px-4 py-3 text-jp-indigo placeholder:text-jp-indigo/30
                shadow-sm transition duration-300
                focus:border-jp-gold focus:ring-2 focus:ring-jp-gold/30
                disabled:bg-jp-indigo/5 disabled:text-jp-indigo/40',
]) }}>
