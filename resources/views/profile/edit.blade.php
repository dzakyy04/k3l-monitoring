@extends(auth()->user()->role === 'supervisor' ? 'layouts.app-supervisor' : 'layouts.app-petugas')

@section('content')

<div class="mx-auto max-w-2xl space-y-5">

    <div>
        <p class="eyebrow">Pengaturan Akun</p>
        <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">Profil & Password</h1>
        <p class="mt-1 text-sm text-muted">Perbarui informasi akun dan password login.</p>
    </div>

    {{-- Profile --}}
    <div class="k3l-card-static p-5 sm:p-7">
        <h2 class="text-base font-extrabold">Informasi Profil</h2>
        <p class="mt-0.5 text-xs text-muted">Nama dan email untuk identitas di dashboard.</p>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
            @csrf @method('PATCH')
            <div class="form-control">
                <label for="name" class="label pb-1.5"><span class="text-xs font-bold">Nama Lengkap</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="input input-bordered w-full">
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div class="form-control">
                <label for="email" class="label pb-1.5"><span class="text-xs font-bold">Email</span></label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="input input-bordered w-full">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-primary btn-sm">Simpan</button>
                @if (session('status') === 'profile-updated')
                    <p class="text-xs font-bold text-success">Tersimpan ✓</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Password --}}
    <div class="k3l-card-static p-5 sm:p-7">
        <h2 class="text-base font-extrabold">Update Password</h2>
        <p class="mt-0.5 text-xs text-muted">Gunakan password yang kuat agar akun tetap aman.</p>

        <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-4">
            @csrf @method('PUT')
            <div class="form-control">
                <label for="update_password_current_password" class="label pb-1.5"><span class="text-xs font-bold">Password Saat Ini</span></label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="input input-bordered w-full">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="form-control">
                    <label for="update_password_password" class="label pb-1.5"><span class="text-xs font-bold">Password Baru</span></label>
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="input input-bordered w-full">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
                <div class="form-control">
                    <label for="update_password_password_confirmation" class="label pb-1.5"><span class="text-xs font-bold">Konfirmasi</span></label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="input input-bordered w-full">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-neutral btn-sm">Update</button>
                @if (session('status') === 'password-updated')
                    <p class="text-xs font-bold text-success">Tersimpan ✓</p>
                @endif
            </div>
        </form>
    </div>

</div>

@endsection
