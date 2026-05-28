@props([
    'name',
    'title' => null,
    'subtitle' => null,
    'maxWidth' => 'sm:max-w-lg',
])

<div
    x-data="{
        open: false,
        title: @js($title),
        subtitle: @js($subtitle),
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
            document.body.style.touchAction = 'none';
        },
        hide() {
            this.open = false;
            document.body.style.overflow = '';
            document.body.style.touchAction = '';
        },
    }"
    x-on:open-modal-{{ $name }}.window="
        title = $event.detail?.title ?? @js($title);
        subtitle = $event.detail?.subtitle ?? @js($subtitle);
        show();
    "
    x-on:close-modal-{{ $name }}.window="hide()"
    x-on:keydown.escape.window="hide()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[70] flex items-end sm:items-center justify-center"
    style="display: none;"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="hide()"
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
        class="relative w-full {{ $maxWidth }} sm:mx-4 bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-3xl shadow-pop sm:border sm:border-slate-100 dark:border-white/5 max-h-[92dvh] sm:max-h-[88vh] flex flex-col"
    >
        {{-- Drag handle (mobile) --}}
        <div class="sm:hidden flex items-center justify-center pt-2 pb-1 shrink-0">
            <span class="w-10 h-1 rounded-full bg-slate-200" aria-hidden="true"></span>
        </div>

        {{-- Header --}}
        <header class="flex items-start justify-between gap-3 px-5 sm:px-6 pt-3 sm:pt-6 pb-4 border-b border-slate-100 dark:border-white/5 shrink-0">
            <div class="min-w-0 flex-1">
                <h3 x-text="title" class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-slate-100 tracking-tight"></h3>
                <p x-show="subtitle" x-text="subtitle" class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" x-on:click="hide()"
                class="w-9 h-9 -mt-1 -mr-1 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 active:bg-slate-200 cursor-pointer focus-ring shrink-0"
                aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </header>

        {{-- Body (scrollable) --}}
        <div class="flex-1 min-h-0 overflow-y-auto thin-scroll px-5 sm:px-6 py-5">
            {{ $slot }}
        </div>

        {{-- Footer (sticky, optional) --}}
        @isset($footer)
            <footer
                class="shrink-0 border-t border-slate-100 dark:border-white/5 px-5 sm:px-6 py-3 sm:py-4 bg-white dark:bg-slate-900 rounded-b-3xl"
                style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));"
            >
                {{ $footer }}
            </footer>
        @endisset
    </div>
</div>
