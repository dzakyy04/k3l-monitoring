@props(['title' => 'Dashboard', 'subtitle' => null])

<header data-topbar class="sticky top-0 z-40 pt-0 pb-2 bg-slate-100/90 dark:bg-slate-950/90 backdrop-blur supports-[backdrop-filter]:bg-slate-100/70 supports-[backdrop-filter]:dark:bg-slate-950/70 transition-[padding-top] duration-200 ease-out">
    <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-3xl border border-slate-100 dark:border-white/5 shadow-soft px-3 sm:px-4 h-14 lg:h-16 flex items-center gap-3">

        {{-- Mobile: brand mark + page title --}}
        <a href="{{ route('dashboard') }}" class="lg:hidden flex items-center gap-2 min-w-0 shrink-0">
            <span class="w-9 h-9 rounded-xl brand-gradient flex items-center justify-center text-white shadow-soft shrink-0">
                <img src="{{ asset('images/iconpln.png') }}" alt="" class="h-5 w-5 object-contain">
            </span>
            <span class="flex flex-col leading-tight min-w-0">
                <span class="text-[13px] font-bold text-slate-900 dark:text-slate-100 truncate">{{ $title }}</span>
                @if($subtitle)
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $subtitle }}</span>
                @endif
            </span>
        </a>

        {{-- Desktop: clock on left --}}
        <div data-clock class="hidden lg:flex items-center gap-3 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-white/5">
            <span class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 text-brand-700 dark:text-brand-300 flex items-center justify-center shrink-0">
                <x-icon name="clock" class="w-4 h-4" />
            </span>
            <div class="flex flex-col leading-tight">
                <span class="text-[13px] font-semibold text-slate-900 dark:text-slate-100 font-mono-data" data-clock-time>--:--:--</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400" data-clock-date>--</span>
            </div>
        </div>

        {{-- Right side actions --}}
        <div class="ml-auto flex items-center gap-2 lg:gap-3">

            {{-- Install PWA button (only shows when native prompt is ready) --}}
            <button type="button"
                x-data="{
                    show: !!window.pwa?._deferredPrompt,
                    init() {
                        window.addEventListener('pwa-state-changed', () => {
                            this.show = !!window.pwa._deferredPrompt;
                        });
                    },
                    install() {
                        if (window.pwa?._deferredPrompt) {
                            window.pwa.install();
                        }
                    }
                }"
                x-show="show"
                x-cloak
                x-on:click="install()"
                class="inline-flex items-center gap-1.5 px-2.5 lg:px-3 py-2 text-xs font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 hover:bg-brand-100 dark:hover:bg-brand-900/50 active:bg-brand-200 dark:active:bg-brand-900/70 border border-brand-100 dark:border-brand-800/40 rounded-full cursor-pointer focus-ring transition-colors"
                aria-label="Install aplikasi">
                <x-icon name="download" class="w-3.5 h-3.5" />
                <span class="hidden sm:inline">Install</span>
            </button>

            {{-- Theme toggle --}}
            <button type="button"
                x-data="{
                    isDark: document.documentElement.classList.contains('dark'),
                    init() {
                        window.addEventListener('theme-changed', () => {
                            this.isDark = document.documentElement.classList.contains('dark');
                        });
                    },
                    toggle() { window.setTheme(this.isDark ? 'light' : 'dark'); }
                }"
                x-on:click="toggle()"
                class="hidden sm:flex w-10 h-10 rounded-xl items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 cursor-pointer focus-ring"
                aria-label="Toggle dark mode">
                <span x-show="!isDark"><x-icon name="moon" class="w-5 h-5" /></span>
                <span x-show="isDark" x-cloak><x-icon name="sun" class="w-5 h-5" /></span>
            </button>

            {{-- Notifications --}}
            <button type="button"
                class="relative w-10 h-10 rounded-xl flex items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 cursor-pointer focus-ring"
                aria-label="Notifikasi">
                <x-icon name="bell" class="w-5 h-5" />
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
            </button>

            <div class="hidden lg:block w-px h-6 bg-slate-200 dark:bg-white/10" aria-hidden="true"></div>

            {{-- Profile menu --}}
            <div
                x-data="{
                    open: false,
                    theme: window.getTheme(),
                    setTheme(t) { window.setTheme(t); this.theme = t; },
                    pwaInstallable: !!window.pwa?._deferredPrompt,
                    init() {
                        window.addEventListener('pwa-state-changed', () => {
                            this.pwaInstallable = !!window.pwa._deferredPrompt;
                        });
                    },
                    installApp() {
                        this.open = false;
                        if (window.pwa?._deferredPrompt) {
                            window.pwa.install();
                        }
                    }
                }"
                x-on:keydown.escape.window="open = false"
                x-on:click.outside="open = false"
                x-on:theme-changed.window="theme = window.getTheme()"
                class="relative"
            >
                {{-- Trigger: desktop --}}
                <button type="button"
                    x-on:click="open = !open"
                    :aria-expanded="open"
                    aria-haspopup="menu"
                    class="hidden lg:flex items-center gap-2 pr-2 pl-1 py-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer focus-ring transition-colors"
                    aria-label="Menu profil">
                    <span class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    <span class="flex flex-col items-start leading-tight">
                        <span class="text-[13px] font-bold text-slate-900 dark:text-slate-100">{{ auth()->user()->name }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 capitalize">{{ auth()->user()->role }}</span>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                {{-- Trigger: mobile --}}
                <button type="button"
                    x-on:click="open = !open"
                    :aria-expanded="open"
                    aria-haspopup="menu"
                    class="lg:hidden w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold cursor-pointer focus-ring shrink-0"
                    aria-label="Menu profil">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                    class="absolute right-0 top-full mt-2 w-72 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/10 shadow-pop overflow-hidden z-50 origin-top-right"
                    role="menu"
                >
                    {{-- User header --}}
                    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-semibold text-slate-900 dark:text-slate-100 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                           role="menuitem"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 active:bg-slate-100 dark:active:bg-slate-700 cursor-pointer">
                            <x-icon name="user-cog" class="w-[18px] h-[18px] text-slate-500 dark:text-slate-400" />
                            <span>Profil & Password</span>
                        </a>

                        {{-- Install App (only when installable) --}}
                        <button type="button" x-show="pwaInstallable" x-cloak
                            x-on:click="installApp()"
                            role="menuitem"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 active:bg-slate-100 dark:active:bg-slate-700 cursor-pointer">
                            <x-icon name="download" class="w-[18px] h-[18px] text-brand-600 dark:text-brand-400" />
                            <span class="flex-1 text-left">Install Aplikasi</span>
                            <span class="pill pill-info text-[10px]">PWA</span>
                        </button>
                    </div>

                    {{-- Theme picker --}}
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-white/5">
                        <p class="text-[10px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-2">Tampilan</p>
                        <div class="inline-flex w-full p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                            <button type="button" x-on:click="setTheme('light')"
                                :class="theme === 'light' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-soft' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 text-xs font-semibold rounded-lg cursor-pointer focus-ring transition-colors"
                                role="menuitemradio">
                                <x-icon name="sun" class="w-3.5 h-3.5" />
                                Terang
                            </button>
                            <button type="button" x-on:click="setTheme('dark')"
                                :class="theme === 'dark' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-soft' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 text-xs font-semibold rounded-lg cursor-pointer focus-ring transition-colors"
                                role="menuitemradio">
                                <x-icon name="moon" class="w-3.5 h-3.5" />
                                Gelap
                            </button>
                            <button type="button" x-on:click="setTheme('system')"
                                :class="theme === 'system' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-soft' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-2 py-1.5 text-xs font-semibold rounded-lg cursor-pointer focus-ring transition-colors"
                                role="menuitemradio">
                                <x-icon name="monitor" class="w-3.5 h-3.5" />
                                Sistem
                            </button>
                        </div>
                    </div>

                    {{-- Logout --}}
                    <div class="py-1 border-t border-slate-100 dark:border-white/5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin logout?')"
                                role="menuitem"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 active:bg-red-100 dark:active:bg-red-500/20 cursor-pointer">
                                <x-icon name="log-out" class="w-[18px] h-[18px]" />
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
