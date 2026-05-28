@props(['type' => 'info', 'message' => null])

@php
    $config = [
        'success' => ['classes' => 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200', 'icon' => 'check-circle'],
        'error'   => ['classes' => 'text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-500/10 border-red-200',           'icon' => 'x-circle'],
        'warning' => ['classes' => 'text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/10 border-amber-200',     'icon' => 'alert-triangle'],
        'info'    => ['classes' => 'text-sky-700 bg-sky-50 dark:bg-sky-500/10 border-sky-200',           'icon' => 'info'],
    ][$type] ?? ['classes' => 'text-sky-700 bg-sky-50 dark:bg-sky-500/10 border-sky-200', 'icon' => 'info'];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border rounded-xl w-full {$config['classes']}"]) }}>
    <x-icon :name="$config['icon']" class="w-4 h-4 shrink-0" />
    <span>{{ $message ?? $slot }}</span>
</div>
