@extends('layouts.app-supervisor')

@section('content')

<div class="mx-auto max-w-2xl space-y-5">

    <div>
        <a href="{{ route('petugas.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted transition hover:text-base-content">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
        <h1 class="mt-3 text-[1.65rem] font-extrabold tracking-tight">Edit Petugas</h1>
        <p class="mt-1 text-sm text-muted">Perbarui nama, email, dan role pengguna.</p>
    </div>

    <div class="k3l-card-static p-5 sm:p-7">
        <form action="{{ route('petugas.update', $petugas) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="form-control">
                <label class="label pb-1.5"><span class="text-xs font-bold">Nama Lengkap</span></label>
                <input type="text" name="name" value="{{ old('name', $petugas->name) }}" class="input input-bordered w-full">
                @error('name') <p class="mt-1.5 text-xs font-semibold text-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-control">
                <label class="label pb-1.5"><span class="text-xs font-bold">Email</span></label>
                <input type="email" name="email" value="{{ old('email', $petugas->email) }}" class="input input-bordered w-full">
                @error('email') <p class="mt-1.5 text-xs font-semibold text-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-control">
                <label class="label pb-1.5"><span class="text-xs font-bold">Role</span></label>
                <select name="role" class="select select-bordered w-full">
                    <option value="petugas" {{ old('role', $petugas->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="supervisor" {{ old('role', $petugas->role) == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                </select>
                @error('role') <p class="mt-1.5 text-xs font-semibold text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col-reverse gap-2.5 pt-4 sm:flex-row sm:justify-end">
                <a href="{{ route('petugas.index') }}" class="btn btn-ghost btn-sm">Batal</a>
                <button class="btn btn-primary btn-sm">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
