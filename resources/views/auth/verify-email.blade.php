<x-guest-layout>
    <p class="eyebrow">Verifikasi</p>
    <h1 class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Verifikasi Email</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Klik link verifikasi di email Anda. Bila belum diterima, kirim ulang.</p>


    <div class="mt-6 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
                Kirim Ulang
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-700 dark:text-red-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-red-300 rounded-full cursor-pointer focus-ring transition-colors">
                Logout
            </button>
        </form>
    </div>
</x-guest-layout>
