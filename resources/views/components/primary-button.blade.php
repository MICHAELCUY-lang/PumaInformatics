<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex w-full items-center justify-center rounded-xl bg-jp-indigo px-6 py-3.5
                text-[11px] font-semibold uppercase tracking-[0.25em] text-jp-cream
                transition duration-500 ease-cinematic
                hover:bg-jp-indigo-deep active:scale-[0.99]
                focus:outline-none focus-visible:ring-2 focus-visible:ring-jp-gold focus-visible:ring-offset-2 focus-visible:ring-offset-jp-cream',
]) }}>
    {{ $slot }}
</button>
