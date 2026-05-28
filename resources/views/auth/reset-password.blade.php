<x-guest-layout>
    <p class="eyebrow">Reset Password</p>
    <h1 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Password Baru</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Buat password baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password Baru</label>
            <input type="password" name="password" required autocomplete="new-password"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
            @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                   class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
