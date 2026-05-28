<x-guest-layout>
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">Reset Password</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-base-content">Lupa Password?</h1>
        <p class="mt-2 text-sm leading-relaxed text-base-content/60">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>
    </div>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div class="form-control">
            <label class="label" for="email"><span class="label-text font-bold">Email</span></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input input-bordered w-full" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            <a class="text-sm font-bold text-primary hover:underline" href="{{ route('login') }}">Kembali ke login</a>
            <button class="btn btn-primary">Kirim Link Reset</button>
        </div>
    </form>
</x-guest-layout>
