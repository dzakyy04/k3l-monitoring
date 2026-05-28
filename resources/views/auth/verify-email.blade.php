<x-guest-layout>
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">Verifikasi</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-base-content">Verifikasi Email</h1>
        <p class="mt-2 text-sm leading-relaxed text-base-content/60">
            Terima kasih telah mendaftar! Silakan verifikasi email Anda dengan mengklik link yang telah kami kirim. Jika belum menerima email, klik tombol kirim ulang.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mt-4 text-sm font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Link verifikasi baru telah dikirim ke email Anda.</span>
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn btn-primary btn-sm">Kirim Ulang Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm text-error">Logout</button>
        </form>
    </div>
</x-guest-layout>
