<x-guest-layout>
    <p class="eyebrow">Reset Password</p>
    <h1 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Lupa Password?</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Masukkan email Anda. Kami akan kirim link reset password.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-brand-700 dark:text-brand-300 hover:text-brand-800 dark:text-brand-300 cursor-pointer">Kembali ke login</a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Kirim Link
            </button>
        </div>
    </form>
</x-guest-layout>
