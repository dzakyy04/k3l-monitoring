@props(['value'])

<label {{ $attributes->merge(['class' => 'label']) }}>
    <span class="label-text font-bold">{{ $value ?? $slot }}</span>
</label>
