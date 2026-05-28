<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - K3L Monitoring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

<div class="flex min-h-screen">

    {{-- ══════ BRAND PANEL (Desktop only) ══════ --}}
    <div class="hidden lg:flex lg:w-[48%] lg:flex-col lg:items-center lg:justify-center"
         style="background: oklch(0.13 0.025 265);">

        <div class="w-full max-w-md px-12">
            {{-- Logo --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/8 p-2.5">
                <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-full w-full object-contain">
            </div>

            <h1 class="mt-8 text-[2rem] font-extrabold leading-[1.15] tracking-tight text-white">
                K3L Monitoring<br>
                <span style="color: oklch(0.65 0.18 230);">System</span>
            </h1>

            <p class="mt-4 text-sm leading-relaxed" style="color: oklch(0.45 0.015 260);">
                Sistem monitoring absensi, APD, dan kegiatan K3L PT PLN secara realtime dengan geofencing GPS.
            </p>

            {{-- Feature pills --}}
            <div class="mt-10 flex flex-wrap gap-2">
                <span class="rounded-full px-3.5 py-1.5 text-[0.7rem] font-bold" style="background: oklch(1 0 0 / 0.04); color: oklch(0.55 0.015 260);">
                    📍 Geofencing GPS
                </span>
                <span class="rounded-full px-3.5 py-1.5 text-[0.7rem] font-bold" style="background: oklch(1 0 0 / 0.04); color: oklch(0.55 0.015 260);">
                    🦺 Checklist APD
                </span>
                <span class="rounded-full px-3.5 py-1.5 text-[0.7rem] font-bold" style="background: oklch(1 0 0 / 0.04); color: oklch(0.55 0.015 260);">
                    📷 Dokumentasi
                </span>
            </div>
        </div>
    </div>

    {{-- ══════ LOGIN FORM ══════ --}}
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-12">

        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="mb-10 flex items-center gap-3 lg:hidden">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-base-200 p-1.5">
                    <img src="{{ asset('images/iconpln.png') }}" alt="PLN" class="h-full w-full object-contain">
                </div>
                <p class="text-sm font-extrabold tracking-tight">K3L Monitoring</p>
            </div>

            <p class="eyebrow">Selamat Datang</p>
            <h2 class="mt-2 text-[1.5rem] font-extrabold tracking-tight">Masuk ke akun Anda</h2>
            <p class="mt-1.5 text-sm text-muted">Gunakan email dan password yang terdaftar.</p>

            <x-auth-session-status class="mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                @csrf

                <div class="form-control">
                    <label class="label pb-1.5" for="email">
                        <span class="text-xs font-bold">Email</span>
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="input input-bordered w-full" placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-control">
                    <label class="label pb-1.5" for="password">
                        <span class="text-xs font-bold">Password</span>
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="input input-bordered w-full" placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" name="remember" class="checkbox checkbox-xs checkbox-primary">
                        <span class="text-xs font-semibold text-muted">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-primary hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-full mt-2">
                    Login
                </button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
