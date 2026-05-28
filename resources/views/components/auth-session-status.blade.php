@props(['status'])

@if($status)
    <div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 rounded-xl w-full']) }}>
        <x-icon name="check-circle" class="w-4 h-4" />
        <span>{{ $status }}</span>
    </div>
@endif
