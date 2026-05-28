@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-sm font-bold text-primary border-b-2 border-primary px-3 py-2'
            : 'text-sm font-bold text-base-content/60 hover:text-base-content px-3 py-2 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
