@php
    $pageTitle = 'Edit Absensi';
    $checkedApd = old('checklist_apd', $absensi->checklist_apd ?? []);
@endphp
@extends('layouts.app-supervisor')

@section('content')

<section class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <p class="eyebrow">Koreksi Data</p>
        <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Edit Absensi</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">Perbarui data absensi {{ $absensi->user->name ?? 'petugas' }}.</p>
    </div>
    <a href="{{ route('absensi.index', ['tanggal_dari' => optional($absensi->tanggal)->format('Y-m-d'), 'tanggal_sampai' => optional($absensi->tanggal)->format('Y-m-d')]) }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-brand-300 rounded-full cursor-pointer focus-ring transition-colors self-start">
        <x-icon name="arrow-left" class="w-4 h-4" />
        Kembali
    </a>
</section>

@if($errors->any())
    @push('scripts')
    <script>Swal.fire({ icon: 'error', title: 'Oops!', text: 'Ada kesalahan input. Periksa field yang ditandai.', confirmButtonColor: '#0284C7' });</script>
    @endpush
@endif

<form action="{{ route('absensi.update', $absensi) }}" method="POST" enctype="multipart/form-data" class="space-y-5" data-submit-text="Menyimpan perubahan...">
    @csrf @method('PUT')

    {{-- Basic info --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Informasi Dasar</h3>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Petugas</label>
                <input type="text" value="{{ $absensi->user->name ?? 'User tidak ditemukan' }}" readonly
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-slate-300">
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', optional($absensi->tanggal)->format('Y-m-d')) }}" required
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                @error('tanggal') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Jam</label>
                <input type="time" name="jam" value="{{ old('jam', $absensi->jam ? substr($absensi->jam, 0, 5) : '') }}" required
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                @error('jam') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Status</label>
                <select name="status" required
                        class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    <option value="standby" {{ old('status', $absensi->status) === 'standby' ? 'selected' : '' }}>Standby</option>
                    <option value="progress" {{ old('status', $absensi->status) === 'progress' ? 'selected' : '' }}>Progress</option>
                </select>
                @error('status') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Lokasi Pekerjaan</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $absensi->lokasi) }}" placeholder="Lokasi pekerjaan"
                       class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                @error('lokasi') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </article>

    {{-- APD --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Checklist APD</h3>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @foreach($apdItems as $item)
                <label class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl hover:border-brand-300 hover:bg-brand-50 dark:hover:bg-brand-900/30 dark:bg-brand-900/20/50 cursor-pointer transition-colors">
                    <input type="checkbox" name="checklist_apd[]" value="{{ $item }}"
                           class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                           {{ in_array($item, $checkedApd, true) ? 'checked' : '' }}>
                    {{ $item }}
                </label>
            @endforeach
        </div>
    </article>

    {{-- Uraian --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Uraian Kegiatan</h3>
        <textarea name="uraian" rows="4" required
                  class="mt-4 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring resize-y">{{ old('uraian', $absensi->uraian) }}</textarea>
        @error('uraian') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
    </article>

    {{-- Foto --}}
    <article class="surface-card p-5 lg:p-6">
        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Dokumentasi</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">Kosongkan bila tidak ingin mengganti foto.</p>

        <div class="mt-4 flex flex-col sm:flex-row gap-4 sm:items-center">
            @if($absensi->foto)
                <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto absensi"
                     class="h-28 w-28 rounded-2xl object-cover border border-slate-200 dark:border-white/10 shrink-0">
            @else
                <div class="h-28 w-28 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 flex items-center justify-center text-xs font-semibold text-slate-400 dark:text-slate-500 shrink-0">
                    <x-icon name="camera" class="w-6 h-6" />
                </div>
            @endif
            <input type="file" name="foto" accept="image/*"
                   class="flex-1 text-sm text-slate-700 dark:text-slate-300 file:mr-3 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-50 dark:bg-brand-900/20 file:text-brand-700 dark:text-brand-300 hover:file:bg-brand-100 dark:bg-brand-900/40 cursor-pointer">
        </div>
        @error('foto') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
    </article>

    {{-- Actions --}}
    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
        <a href="{{ route('absensi.index', ['tanggal_dari' => optional($absensi->tanggal)->format('Y-m-d'), 'tanggal_sampai' => optional($absensi->tanggal)->format('Y-m-d')]) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 rounded-full cursor-pointer focus-ring transition-colors">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors">
            <x-icon name="save" class="w-4 h-4" />
            Simpan Perubahan
        </button>
    </div>
</form>

@endsection
