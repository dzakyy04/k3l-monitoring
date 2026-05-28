@extends('layouts.app-supervisor')

@section('content')

<div class="space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="eyebrow">Geofencing</p>
            <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">Data Lokasi</h1>
        </div>
        <a href="{{ route('lokasi.create') }}" class="btn btn-primary btn-sm gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah
        </a>
    </div>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- Mobile --}}
    <div class="space-y-2.5 lg:hidden">
        @forelse($lokasi as $item)
            <div class="k3l-card p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold tracking-tight">{{ $item->nama_lokasi }}</p>
                        <p class="mt-0.5 text-xs text-muted">{{ $item->latitude }}, {{ $item->longitude }}</p>
                    </div>
                    @if($item->polygon && count($item->polygon) >= 3)
                        <span class="badge badge-primary badge-sm rounded-full font-bold">{{ count($item->polygon) }} titik</span>
                    @else
                        <span class="badge badge-warning badge-sm rounded-full font-bold">{{ $item->radius }}m</span>
                    @endif
                </div>
                <div class="mt-3 flex gap-1.5 border-t border-base-200 pt-3">
                    <a href="{{ route('lokasi.edit', $item) }}" class="btn btn-ghost btn-xs text-warning">Edit</a>
                    <form action="{{ route('lokasi.destroy', $item) }}" method="POST">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Hapus lokasi?')" class="btn btn-ghost btn-xs text-error">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="k3l-card-static p-10 text-center">
                <p class="text-sm font-semibold text-muted">Belum ada lokasi</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop --}}
    <div class="k3l-card-static hidden overflow-hidden lg:block">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Lokasi</th>
                        <th>Pusat (Lat, Lng)</th>
                        <th>Polygon</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasi as $item)
                        <tr>
                            <td class="font-bold">{{ $item->nama_lokasi }}</td>
                            <td class="text-sm text-muted">{{ $item->latitude }}, {{ $item->longitude }}</td>
                            <td>
                                @if($item->polygon && count($item->polygon) >= 3)
                                    <span class="badge badge-primary badge-sm rounded-full font-bold">{{ count($item->polygon) }} titik</span>
                                @else
                                    <span class="badge badge-warning badge-sm rounded-full font-bold">{{ $item->radius }}m</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('lokasi.edit', $item) }}" class="btn btn-ghost btn-xs text-warning">Edit</a>
                                    <form action="{{ route('lokasi.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus lokasi?')" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-12 text-center text-sm font-semibold text-muted">Belum ada lokasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
