@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block text-[10px] font-semibold uppercase tracking-[0.25em] text-jp-indigo/50 mb-2',
]) }}>
    {{ $value ?? $slot }}
</label>
