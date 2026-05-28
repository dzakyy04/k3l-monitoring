<x-guest-layout>
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-primary">Reset Password</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-base-content">Password Baru</h1>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-control">
            <label class="label" for="email"><span class="label-text font-bold">Email</span></label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="input input-bordered w-full">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-control">
            <label class="label" for="password"><span class="label-text font-bold">Password Baru</span></label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="input input-bordered w-full" placeholder="Minimal 8 karakter">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-control">
            <label class="label" for="password_confirmation"><span class="label-text font-bold">Konfirmasi Password</span></label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input input-bordered w-full" placeholder="Ulangi password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex justify-end pt-2">
            <button class="btn btn-primary">Reset Password</button>
        </div>
    </form>
</x-guest-layout>
