<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0284C7">
    <title>{{ config('app.name', 'K3L Monitoring') }}</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/iconpln.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
<main class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md surface-card p-6 sm:p-8">

        <a href="{{ route('login') }}" class="flex items-center gap-2.5 mb-8">
            <span class="w-10 h-10 rounded-xl brand-gradient flex items-center justify-center text-white">
                <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-5 w-5 object-contain">
            </span>
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
