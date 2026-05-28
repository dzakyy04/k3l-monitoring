@extends('layouts.app-supervisor')

@section('content')

<div class="space-y-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="eyebrow">Manajemen Akun</p>
            <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">Data Petugas</h1>
        </div>
        <a href="{{ route('petugas.create') }}" class="btn btn-sm btn-primary gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah
        </a>
    </div>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- Mobile --}}
    <div class="space-y-2.5 lg:hidden">
        @forelse($petugas as $item)
            <div class="k3l-card p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary/70 text-sm font-extrabold text-white">
                        {{ strtoupper(substr($item->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold tracking-tight">{{ $item->name }}</p>
                        <p class="truncate text-xs text-muted">{{ $item->email }}</p>
                    </div>
                    <span class="badge badge-sm rounded-full font-bold uppercase {{ $item->role == 'supervisor' ? 'badge-primary' : 'badge-ghost' }}">{{ $item->role }}</span>
                </div>
                <div class="mt-3 flex gap-1.5 border-t border-base-200 pt-3">
                    <a href="{{ route('petugas.edit', $item) }}" class="btn btn-ghost btn-xs text-warning">Edit</a>
                    <form action="{{ route('petugas.destroy', $item) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus {{ $item->name }}?')" class="btn btn-ghost btn-xs text-error">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="k3l-card-static p-10 text-center">
                <p class="text-sm font-semibold text-muted">Belum ada data petugas</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop --}}
    <div class="k3l-card-static hidden overflow-hidden lg:block">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugas as $item)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-primary/70 text-xs font-extrabold text-white">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="text-sm text-muted">{{ $item->email }}</td>
                            <td>
                                <span class="badge badge-sm rounded-full font-bold uppercase {{ $item->role == 'supervisor' ? 'badge-primary' : 'badge-ghost' }}">{{ $item->role }}</span>
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('petugas.edit', $item) }}" class="btn btn-ghost btn-xs text-warning">Edit</a>
                                    <form action="{{ route('petugas.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus {{ $item->name }}?')" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-12 text-center text-sm font-semibold text-muted">Belum ada data petugas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
