<x-guest-layout>
    <p class="eyebrow">Akun Baru</p>
    <h1 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Registrasi</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Daftarkan akun baru untuk akses ke dashboard.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('name') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password</label>
            <input type="password" name="password" required autocomplete="new-password"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-brand-700 dark:text-brand-300 hover:text-brand-800 dark:text-brand-300 cursor-pointer">Sudah punya akun?</a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Daftar
            </button>
        </div>
    </form>
</x-guest-layout>
