<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0284C7">
    <meta name="view-transition" content="same-origin">
    <title>Login · K3L Monitoring</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-k3l-monitoring.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Theme: apply early to prevent flash --}}
    <script>
        (function() {
            try {
                const stored = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = stored || (prefersDark ? 'dark' : 'light');
                if (theme === 'dark') document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
    </script>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300" style="overscroll-behavior:contain">
<main class="min-h-screen grid lg:grid-cols-2">

    {{-- ───────── Form panel ───────── --}}
    <section class="flex items-center justify-center px-4 py-10 sm:px-8">
        <div class="w-full max-w-md">

            <a href="{{ route('login') }}" class="flex items-center gap-2.5 mb-10">
                <img src="{{ asset('images/logo-k3l-monitoring.jpeg') }}" alt="PLN" class="w-10 h-10 rounded-xl object-contain shrink-0">
                <span class="flex flex-col leading-tight">
                    <span class="font-bold text-slate-900 dark:text-slate-100">K3L Monitoring</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 -mt-0.5">PLN Icon Plus</span>
                </span>
            </a>

            <p class="eyebrow">Selamat datang</p>
            <h1 class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Masuk ke akun Anda</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Gunakan email dan password yang sudah terdaftar.</p>

            @if(session('status'))
                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 rounded-xl">
                    <x-icon name="check-circle" class="w-4 h-4" />
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4" data-submit-text="Sedang masuk...">
                @csrf

                <div>
                    <label for="email" class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <div class="relative mt-1">
                        <x-icon name="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" />
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               placeholder="nama@email.com"
                               class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring" />
                    </div>
                    @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-brand-700 dark:text-brand-300 hover:text-brand-800 dark:text-brand-300 cursor-pointer font-semibold">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative mt-1">
                        <x-icon name="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500" />
                        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••"
                               class="w-full pl-9 pr-10 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring" />
                        <button type="button" id="togglePassword" aria-label="Tampilkan/sembunyikan password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer">
                            <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="iconEyeOff" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 select-none cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 dark:border-slate-600 dark:bg-slate-800 text-brand-600 focus:ring-brand-500">
                    Ingat saya di perangkat ini
                </label>

                <button type="submit" data-loading-text="Sedang masuk..."
                    class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                    Masuk
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
            </form>

            <p class="text-center text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 pt-8">© {{ date('Y') }} K3L Monitoring · PT PLN.</p>
        </div>
    </section>

    {{-- ───────── Brand panel ───────── --}}
    <section class="hidden lg:flex relative overflow-hidden brand-gradient">
        <div class="absolute inset-0 opacity-30 map-grid"></div>
        <div class="relative z-10 p-12 flex flex-col justify-between text-white">
            <div class="flex items-center gap-2 text-sm">
                <span class="w-2 h-2 rounded-full bg-white"></span>
                <span class="opacity-80">Production · Asia/Jakarta</span>
            </div>
            <div class="space-y-6 max-w-md">
                <h2 class="text-3xl font-extrabold leading-tight text-white">Monitoring K3L untuk operasi yang lebih aman.</h2>
                <p class="text-white/80">Geofencing GPS, checklist APD, dan dokumentasi lapangan dalam satu dashboard yang siap audit.</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold text-white">99%</p>
                        <p class="text-xs text-white/70 mt-1">Sync uptime</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold text-white">8</p>
                        <p class="text-xs text-white/70 mt-1">Item APD</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold text-white">GPS</p>
                        <p class="text-xs text-white/70 mt-1">Realtime</p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-white/70">© {{ date('Y') }} K3L Monitoring. PT PLN.</p>
        </div>
    </section>
</main>

@include('layouts.partials.native-feel')

<script>
    // Toggle show/hide password
    (function() {
        const btn = document.getElementById('togglePassword');
        const input = document.getElementById('password');
        const iconEye = document.getElementById('iconEye');
        const iconEyeOff = document.getElementById('iconEyeOff');
        if (!btn || !input) return;
        btn.addEventListener('click', function() {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            iconEye.classList.toggle('hidden', isPassword);
            iconEyeOff.classList.toggle('hidden', !isPassword);
        });
    })();

    window.setTheme = function(theme) {
        const root = document.documentElement;
        if (theme === 'system') {
            localStorage.removeItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.toggle('dark', prefersDark);
        } else {
            localStorage.setItem('theme', theme);
            root.classList.toggle('dark', theme === 'dark');
        }
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme } }));
    };

    window.getTheme = function() {
        return localStorage.getItem('theme') || 'system';
    };

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                document.documentElement.classList.toggle('dark', e.matches);
            }
        });
    }
</script>

</body>
</html>
