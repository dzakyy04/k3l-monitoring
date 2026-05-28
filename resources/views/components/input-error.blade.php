@props(['messages'])

@if($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1 text-xs font-semibold text-red-600 dark:text-red-400 space-y-0.5']) }}>
        @foreach((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
