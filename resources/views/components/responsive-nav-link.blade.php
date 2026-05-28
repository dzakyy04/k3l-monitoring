@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block rounded-xl px-4 py-2 text-sm font-bold text-primary bg-primary/10'
            : 'block rounded-xl px-4 py-2 text-sm font-bold text-base-content/70 hover:bg-base-200 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
