<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'K3L Monitoring') }}</title>

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#0891b2">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 text-base-content antialiased">
    <main class="flex min-h-dvh items-center justify-center px-4 py-8">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl bg-base-100 shadow-2xl lg:grid-cols-[1fr_440px]">

            {{-- Left panel - brand info (desktop only) --}}
            <section class="sidebar-k3l hidden p-10 lg:flex lg:flex-col lg:justify-between">
                <div>
                    <img
                        src="{{ asset('images/iconpln.png') }}"
                        alt="Logo PLN"
                        class="h-14 w-14 rounded-2xl bg-white object-contain p-2"
                    >

                    <h1 class="mt-8 text-4xl font-extrabold tracking-tight text-white">
                        K3L Monitoring
                    </h1>

                    <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-300">
                        Sistem monitoring absensi, aktivitas petugas, dokumentasi lapangan, dan checklist APD.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="font-extrabold text-primary">Absensi</p>
                        <p class="mt-1 text-sm text-slate-300">GPS tracking</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="font-extrabold text-primary">APD</p>
                        <p class="mt-1 text-sm text-slate-300">Checklist</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="font-extrabold text-primary">Laporan</p>
                        <p class="mt-1 text-sm text-slate-300">Realtime</p>
                    </div>
                </div>
            </section>

            {{-- Right panel - form --}}
            <section class="p-6 sm:p-10">
                {{-- Mobile logo --}}
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <img
                        src="{{ asset('images/iconpln.png') }}"
                        alt="Logo PLN"
                        class="h-11 w-11 rounded-xl bg-white object-contain p-1.5 ring-1 ring-base-300"
                    >
                    <div>
                        <p class="text-lg font-extrabold text-base-content">K3L Monitoring</p>
                        <p class="text-sm text-base-content/50">PLN Safety System</p>
                    </div>
                </div>

                {{ $slot }}
            </section>
        </div>
    </main>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
    }
</script>
</body>
</html>
