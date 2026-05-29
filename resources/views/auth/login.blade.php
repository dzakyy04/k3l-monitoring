<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0284C7">
    <meta name="view-transition" content="same-origin">
    <title>Login · K3L Monitoring</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300" style="overscroll-behavior:contain">
<main class="min-h-screen grid lg:grid-cols-2">

    {{-- ───────── Form panel ───────── --}}
    <section class="flex items-center justify-center px-4 py-10 sm:px-8">
        <div class="w-full max-w-md">

            <a href="{{ route('login') }}" class="flex items-center gap-2.5 mb-10">
                <span class="w-10 h-10 rounded-xl brand-gradient flex items-center justify-center text-white">
                    <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-5 w-5 object-contain">
                </span>
                <span class="flex flex-col leading-tight">
                    <span class="font-bold text-slate-900 dark:text-slate-100">K3L Monitoring</span>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 -mt-0.5">PT PLN Safety Suite</span>
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
                               class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring" />
                    </div>
                    @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300 select-none cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
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
                <span class="w-2 h-2 rounded-full bg-white dark:bg-slate-900/80"></span>
                <span class="opacity-80">Production · Asia/Jakarta</span>
            </div>
            <div class="space-y-6 max-w-md">
                <h2 class="text-3xl font-extrabold leading-tight">Monitoring K3L untuk operasi yang lebih aman.</h2>
                <p class="text-white/80">Geofencing GPS, checklist APD, dan dokumentasi lapangan dalam satu dashboard yang siap audit.</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white dark:bg-slate-900/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold">99%</p>
                        <p class="text-xs text-white/70 mt-1">Sync uptime</p>
                    </div>
                    <div class="rounded-2xl bg-white dark:bg-slate-900/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold">8</p>
                        <p class="text-xs text-white/70 mt-1">Item APD</p>
                    </div>
                    <div class="rounded-2xl bg-white dark:bg-slate-900/10 backdrop-blur p-4">
                        <p class="text-2xl font-mono-data font-bold">GPS</p>
                        <p class="text-xs text-white/70 mt-1">Realtime</p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-white/70">© {{ date('Y') }} K3L Monitoring. PT PLN.</p>
        </div>
    </section>
</main>

@include('layouts.partials.native-feel')

</body>
</html>
