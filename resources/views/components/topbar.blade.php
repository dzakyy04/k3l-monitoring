@props(['title' => 'Dashboard', 'subtitle' => null])

<header data-topbar class="sticky top-0 z-40 pt-0 pb-2 bg-slate-100/90 dark:bg-slate-950/90 backdrop-blur supports-[backdrop-filter]:bg-slate-100/70 supports-[backdrop-filter]:dark:bg-slate-950/70 transition-[padding-top] duration-200 ease-out">
    <div class="bg-white dark:bg-slate-900 rounded-2xl lg:rounded-3xl border border-slate-100 dark:border-white/5 shadow-soft px-3 sm:px-4 h-14 lg:h-16 flex items-center gap-3">

        {{-- Mobile: brand mark + page title --}}
        <a href="{{ route('dashboard') }}" class="lg:hidden flex items-center gap-2 min-w-0 shrink-0">
            <img src="{{ asset('images/logo-k3l-monitoring.jpeg') }}" alt="" class="w-9 h-9 rounded-xl object-contain shrink-0">
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

            {{-- Download App button --}}
            <div x-data="{ isStandalone: window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true, dlOpen: false }"
                 x-show="!isStandalone"
                 x-on:click.outside="dlOpen = false"
                 class="relative">
                <button type="button" x-on:click="dlOpen = !dlOpen"
                    class="inline-flex items-center gap-1.5 px-2.5 lg:px-3 py-2 text-xs font-semibold text-brand-700 dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 hover:bg-brand-100 dark:hover:bg-brand-900/50 active:bg-brand-200 dark:active:bg-brand-900/70 border border-brand-100 dark:border-brand-800/40 rounded-full cursor-pointer focus-ring transition-colors"
                    aria-label="Download Aplikasi">
                    <x-icon name="download" class="w-3.5 h-3.5" />
                    <span class="hidden sm:inline">Download APK</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform" :class="dlOpen ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="dlOpen" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-2 w-52 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-white/10 shadow-pop overflow-hidden z-50 origin-top-right">
                    <a href="{{ asset('k3l-monitoring.apk') }}" download x-on:click="dlOpen = false"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">
                        <svg class="w-[18px] h-[18px] text-green-600 dark:text-green-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.523 2.232a.5.5 0 0 0-.87.49l1.12 1.992A7.97 7.97 0 0 0 12 2a7.97 7.97 0 0 0-5.773 2.714l1.12-1.992a.5.5 0 1 0-.87-.49L4.96 4.87A8.01 8.01 0 0 0 4 9h16a8.01 8.01 0 0 0-.96-4.13l-1.517-2.638zM9.5 7a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zm5 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zM4 10h16v9a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V10z"/></svg>
                        <span class="flex-1">Android</span>
                        <span class="pill pill-success text-[10px]">APK</span>
                    </a>
                    <button type="button" x-on:click="dlOpen = false; window.dispatchEvent(new CustomEvent('show-ios-guide'))"
                       class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer border-t border-slate-100 dark:border-white/5">
                        <svg class="w-[18px] h-[18px] text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                        <span class="flex-1">iOS</span>
                        <span class="pill pill-info text-[10px]">Panduan</span>
                    </button>
                </div>
            </div>

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
            @if(auth()->user()->role === 'supervisor')
            <div
                x-data="notifPanel()"
                x-on:keydown.escape.window="open = false"
                x-on:click.outside="open = false"
                x-init="fetchNotifs(); startPolling()"
                class="relative"
            >
                <button type="button"
                    x-on:click="toggle()"
                    class="relative w-10 h-10 rounded-xl flex items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 cursor-pointer focus-ring"
                    aria-label="Notifikasi">
                    <x-icon name="bell" class="w-5 h-5" />
                    <span
                        x-show="unreadCount > 0"
                        x-text="unreadCount > 99 ? '99+' : unreadCount"
                        x-transition
                        class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"
                    ></span>
                </button>

                {{-- Dropdown panel --}}
                <div
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                    class="absolute right-0 top-full mt-2 w-72 sm:w-80 md:w-96 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/10 shadow-pop overflow-hidden z-50 origin-top-right"
                >
                    {{-- Header --}}
                    <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="bell" class="w-4 h-4 text-brand-600 dark:text-brand-400" />
                            <span class="text-sm font-bold text-slate-900 dark:text-slate-100">Notifikasi</span>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="pill pill-info text-[10px]"></span>
                        </div>
                        <button
                            x-show="unreadCount > 0"
                            x-on:click="markAllRead()"
                            type="button"
                            class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 cursor-pointer"
                        >Tandai semua dibaca</button>
                    </div>

                    {{-- List --}}
                    <div class="max-h-80 overflow-y-auto thin-scroll divide-y divide-slate-100 dark:divide-white/5">
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-10 text-center">
                                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                                    <x-icon name="bell" class="w-5 h-5" />
                                </div>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada notifikasi</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Notifikasi akan muncul saat petugas menambahkan absensi.</p>
                            </div>
                        </template>

                        <template x-for="n in notifications" :key="n.id">
                            <a
                                :href="'/absensi/' + n.absensi_id"
                                x-on:click="markRead(n)"
                                class="flex gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                :class="!n.read ? 'bg-brand-50/50 dark:bg-brand-900/10' : ''"
                            >
                                <div class="shrink-0 mt-0.5">
                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        :class="!n.read ? 'bg-brand-100 dark:bg-brand-900/40 text-brand-600 dark:text-brand-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500'">
                                        <x-icon name="clipboard-check" class="w-4 h-4" />
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-semibold text-slate-900 dark:text-slate-100 truncate" x-text="n.judul"></p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 line-clamp-2" x-text="n.pesan"></p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1" x-text="n.waktu"></p>
                                </div>
                                <span x-show="!n.read" class="shrink-0 mt-2 w-2 h-2 rounded-full bg-brand-500"></span>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            @endif

            <div class="hidden lg:block w-px h-6 bg-slate-200 dark:bg-white/10" aria-hidden="true"></div>

            {{-- Profile menu --}}
            <div
                x-data="{
                    open: false,
                    theme: window.getTheme(),
                    setTheme(t) { window.setTheme(t); this.theme = t; },
                    pwaInstallable: !!window.pwa?._deferredPrompt,
                    isStandalone: window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true,
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

                        {{-- Download Apps --}}
                        <template x-if="!isStandalone">
                            <div>
                                <a href="{{ asset('k3l-monitoring.apk') }}" download
                                    role="menuitem"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 active:bg-slate-100 dark:active:bg-slate-700 cursor-pointer">
                                    <svg class="w-[18px] h-[18px] text-green-600 dark:text-green-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.523 2.232a.5.5 0 0 0-.87.49l1.12 1.992A7.97 7.97 0 0 0 12 2a7.97 7.97 0 0 0-5.773 2.714l1.12-1.992a.5.5 0 1 0-.87-.49L4.96 4.87A8.01 8.01 0 0 0 4 9h16a8.01 8.01 0 0 0-.96-4.13l-1.517-2.638zM9.5 7a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zm5 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zM4 10h16v9a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V10z"/></svg>
                                    <span class="flex-1">Download Android</span>
                                    <span class="pill pill-success text-[10px]">APK</span>
                                </a>
                                <button type="button" x-on:click="open = false; window.dispatchEvent(new CustomEvent('show-ios-guide'))"
                                    role="menuitem"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 active:bg-slate-100 dark:active:bg-slate-700 cursor-pointer">
                                    <svg class="w-[18px] h-[18px] text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                    <span class="flex-1">Install di iOS</span>
                                    <span class="pill pill-info text-[10px]">Panduan</span>
                                </button>
                            </div>
                        </template>
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

<script>
    function notifPanel() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            _interval: null,

            toggle() {
                this.open = !this.open;
                if (this.open) this.fetchNotifs();
            },

            async fetchNotifs() {
                try {
                    const res = await fetch('/notifikasi', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (e) {
                    // silently fail
                }
            },

            startPolling() {
                this._interval = setInterval(() => this.fetchNotifs(), 30000);
            },

            async markRead(n) {
                if (n.read) return;
                n.read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    await fetch(`/notifikasi/${n.id}/read`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                } catch (e) {}
            },

            async markAllRead() {
                this.notifications.forEach(n => n.read = true);
                this.unreadCount = 0;
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    await fetch('/notifikasi/read-all', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                } catch (e) {}
            },

            destroy() {
                if (this._interval) clearInterval(this._interval);
            }
        }
    }
</script>

{{-- iOS Install Guide Modal --}}
<div x-data="{ show: false }"
     x-on:show-ios-guide.window="show = true"
     x-on:keydown.escape.window="show = false"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-[999] flex items-center justify-center p-4"
     style="display:none">
    {{-- Backdrop --}}
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-on:click="show = false"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Modal --}}
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="relative w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-white/10 shadow-pop overflow-hidden">

        {{-- Header --}}
        <div class="px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-slate-700 dark:text-slate-200" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                <span class="text-sm font-bold text-slate-900 dark:text-slate-100">Install di iPhone / iPad</span>
            </div>
            <button type="button" x-on:click="show = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- Steps --}}
        <div class="px-5 py-5 space-y-4">
            <p class="text-xs text-slate-500 dark:text-slate-400">Ikuti langkah berikut untuk menambahkan K3L Monitoring ke layar utama iPhone/iPad Anda:</p>

            <div class="space-y-3">
                {{-- Step 1 --}}
                <div class="flex gap-3">
                    <span class="shrink-0 w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-xs font-bold">1</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Buka di Safari</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Buka halaman ini menggunakan browser <strong>Safari</strong> (bukan Chrome/Firefox).</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="flex gap-3">
                    <span class="shrink-0 w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-xs font-bold">2</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Tap tombol Share</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ketuk ikon <strong>Share</strong>
                            <svg class="inline w-4 h-4 -mt-0.5 text-brand-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
                            di bagian bawah layar.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex gap-3">
                    <span class="shrink-0 w-7 h-7 rounded-lg bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-xs font-bold">3</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Tambah ke Layar Utama</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Scroll ke bawah lalu pilih <strong>"Add to Home Screen"</strong> atau <strong>"Tambah ke Layar Utama"</strong>.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="flex gap-3">
                    <span class="shrink-0 w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xs font-bold">✓</span>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Selesai!</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Aplikasi akan muncul di layar utama seperti app biasa.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-white/5">
            <button type="button" x-on:click="show = false"
                class="w-full px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>
