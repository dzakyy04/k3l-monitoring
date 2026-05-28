<x-guest-layout>
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">Keamanan</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-base-content">Konfirmasi Password</h1>
        <p class="mt-2 text-sm leading-relaxed text-base-content/60">Area ini memerlukan konfirmasi password sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-4">
        @csrf

        <div class="form-control">
            <label class="label" for="password"><span class="label-text font-bold">Password</span></label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="input input-bordered w-full" placeholder="Masukkan password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end pt-2">
            <button class="btn btn-primary">Konfirmasi</button>
        </div>
    </form>
</x-guest-layout>
