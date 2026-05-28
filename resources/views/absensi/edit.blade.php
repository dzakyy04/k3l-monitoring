@extends('layouts.app-supervisor')

@section('content')

@php
    $checkedApd = old('checklist_apd', $absensi->checklist_apd ?? []);
@endphp

<div class="mx-auto max-w-5xl space-y-5">

    <div>
        <a
            href="{{ route('absensi.index', ['tanggal_dari' => optional($absensi->tanggal)->format('Y-m-d'), 'tanggal_sampai' => optional($absensi->tanggal)->format('Y-m-d')]) }}"
            class="btn btn-ghost btn-xs gap-1 text-muted"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
        <h1 class="mt-3 text-[1.65rem] font-extrabold tracking-tight">Edit Absensi</h1>
        <p class="mt-1 text-sm text-muted">Koreksi data absensi petugas bila ada kesalahan input.</p>
    </div>

    <form action="{{ route('absensi.update', $absensi) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Basic info --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="form-control sm:col-span-2">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Petugas</span></label>
                        <input type="text" value="{{ $absensi->user->name ?? 'User tidak ditemukan' }}" class="input input-bordered input-sm bg-base-200" readonly>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Tanggal</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', optional($absensi->tanggal)->format('Y-m-d')) }}" class="input input-bordered input-sm w-full" required>
                        @error('tanggal') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Jam</span></label>
                        <input type="time" name="jam" value="{{ old('jam', $absensi->jam ? substr($absensi->jam, 0, 5) : '') }}" class="input input-bordered input-sm w-full" required>
                        @error('jam') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Status</span></label>
                        <select name="status" class="select select-bordered select-sm w-full" required>
                            <option value="standby" {{ old('status', $absensi->status) === 'standby' ? 'selected' : '' }}>Standby</option>
                            <option value="progress" {{ old('status', $absensi->status) === 'progress' ? 'selected' : '' }}>Progress</option>
                        </select>
                        @error('status') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-control sm:col-span-2 lg:col-span-3">
                        <label class="label py-1"><span class="label-text text-xs font-bold">Lokasi</span></label>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $absensi->lokasi) }}" class="input input-bordered input-sm w-full" placeholder="Lokasi pekerjaan">
                        @error('lokasi') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- APD Checklist --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <h2 class="text-base font-extrabold">Checklist APD</h2>
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach ($apdItems as $item)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-base-300 px-4 py-3 text-sm font-semibold transition hover:bg-base-200">
                            <input type="checkbox" name="checklist_apd[]" value="{{ $item }}" class="checkbox checkbox-primary checkbox-sm" {{ in_array($item, $checkedApd, true) ? 'checked' : '' }}>
                            {{ $item }}
                        </label>
                    @endforeach
                </div>
                @error('checklist_apd') <p class="mt-2 text-xs font-semibold text-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Uraian --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-xs font-bold">Uraian Kegiatan</span></label>
                    <textarea name="uraian" rows="3" class="textarea textarea-bordered w-full text-sm" required>{{ old('uraian', $absensi->uraian) }}</textarea>
                    @error('uraian') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Dokumentasi --}}
        <div class="k3l-card-static">
            <div class="p-5 lg:p-7">
                <h2 class="text-base font-extrabold">Dokumentasi</h2>
                <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-center">
                    @if ($absensi->foto)
                        <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto absensi" class="h-28 w-28 rounded-2xl object-cover ring-1 ring-base-300">
                    @else
                        <div class="flex h-28 w-28 items-center justify-center rounded-2xl bg-base-200 text-sm font-semibold text-base-content/30">No Photo</div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="foto" accept="image/*" class="file-input file-input-bordered file-input-sm w-full">
                        <p class="mt-2 text-xs text-muted">Kosongkan bila tidak ingin mengganti foto.</p>
                        @error('foto') <p class="mt-1 text-xs font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('absensi.index', ['tanggal_dari' => optional($absensi->tanggal)->format('Y-m-d'), 'tanggal_sampai' => optional($absensi->tanggal)->format('Y-m-d')]) }}" class="btn btn-outline btn-sm">Batal</a>
            <button class="btn btn-primary btn-sm gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@endsection
