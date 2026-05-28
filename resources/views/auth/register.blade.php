<x-guest-layout>
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">Akun Baru</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-base-content">Registrasi</h1>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div class="form-control">
            <label class="label" for="name"><span class="label-text font-bold">Nama</span></label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input input-bordered w-full" placeholder="Nama lengkap">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="form-control">
            <label class="label" for="email"><span class="label-text font-bold">Email</span></label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input input-bordered w-full" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-control">
            <label class="label" for="password"><span class="label-text font-bold">Password</span></label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="input input-bordered w-full" placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-control">
            <label class="label" for="password_confirmation"><span class="label-text font-bold">Konfirmasi Password</span></label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input input-bordered w-full" placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            <a class="text-sm font-bold text-primary hover:underline" href="{{ route('login') }}">Sudah punya akun?</a>
            <button class="btn btn-primary">Register</button>
        </div>
    </form>
</x-guest-layout>
