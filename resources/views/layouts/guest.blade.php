<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0284C7">
    <title>{{ config('app.name', 'K3L Monitoring') }}</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-k3l-monitoring.jpeg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
<main class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md surface-card p-6 sm:p-8">

        <a href="{{ route('login') }}" class="flex items-center gap-2.5 mb-8">
            <img src="{{ asset('images/logo-k3l-monitoring.jpeg') }}" alt="PLN" class="w-10 h-10 rounded-xl object-contain shrink-0">
            <span class="flex flex-col leading-tight">
                <span class="font-bold text-slate-900 dark:text-slate-100">K3L Monitoring</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 -mt-0.5">PT PLN Safety Suite</span>
            </span>
        </a>

        {{ $slot }}
    </div>
</main>
</body>
</html>
