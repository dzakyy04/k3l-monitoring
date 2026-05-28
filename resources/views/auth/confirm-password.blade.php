<x-guest-layout>
    <p class="eyebrow">Keamanan</p>
    <h1 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Konfirmasi Password</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Area ini memerlukan konfirmasi password sebelum melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password</label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Konfirmasi
            </button>
        </div>
    </form>
</x-guest-layout>
