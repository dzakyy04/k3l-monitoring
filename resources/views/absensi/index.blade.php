@extends('layouts.app-supervisor')

@section('content')

<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="eyebrow">Data Absensi</p>
            <h1 class="mt-1.5 text-[1.65rem] font-extrabold tracking-tight">Riwayat Absensi</h1>
        </div>
        <a href="{{ route('absensi.create') }}" class="btn btn-sm btn-primary gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('absensi.index') }}" class="k3l-card-static p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="form-control min-w-0 flex-1">
                <label class="label pb-1"><span class="text-[0.65rem] font-bold uppercase tracking-[0.08em] text-muted">Dari</span></label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="input input-bordered input-sm w-full">
            </div>
            <div class="form-control min-w-0 flex-1">
                <label class="label pb-1"><span class="text-[0.65rem] font-bold uppercase tracking-[0.08em] text-muted">Sampai</span></label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="input input-bordered input-sm w-full">
            </div>
            <button class="btn btn-sm btn-primary">Filter</button>
        </div>
    </form>

    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- Mobile Cards --}}
    <div class="space-y-2.5 lg:hidden">
        @forelse($absensi as $item)
            <a href="{{ route('absensi.show', $item) }}" class="k3l-card block p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold tracking-tight">{{ $item->user->name ?? '—' }}</p>
                        <p class="mt-0.5 text-xs text-muted">{{ optional($item->tanggal)->format('d M Y') }} · {{ $item->jam ? substr($item->jam, 0, 5) : '' }}</p>
                    </div>
                    <span class="badge badge-sm rounded-full font-bold uppercase {{ $item->status === 'progress' ? 'badge-primary' : 'badge-ghost' }}">
                        {{ $item->status }}
                    </span>
                </div>
                @if($item->lokasi)
                    <p class="mt-2 truncate text-xs text-muted">📍 {{ $item->lokasi }}</p>
                @endif
            </a>
        @empty
            <div class="k3l-card-static p-10 text-center">
                <p class="text-sm font-semibold text-muted">Belum ada data absensi</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="k3l-card-static hidden overflow-hidden lg:block">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Petugas</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $item)
                        <tr>
                            <td class="font-bold">{{ $item->user->name ?? '—' }}</td>
                            <td class="text-sm text-muted">{{ optional($item->tanggal)->format('d M Y') }}</td>
                            <td class="text-sm text-muted">{{ $item->jam ? substr($item->jam, 0, 5) : '' }}</td>
                            <td>
                                <span class="badge badge-sm rounded-full font-bold uppercase {{ $item->status === 'progress' ? 'badge-primary' : 'badge-ghost' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="max-w-[200px] truncate text-sm text-muted">{{ $item->lokasi ?? '—' }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('absensi.show', $item) }}" class="btn btn-ghost btn-xs">Detail</a>
                                    <a href="{{ route('absensi.edit', $item) }}" class="btn btn-ghost btn-xs text-warning">Edit</a>
                                    <form action="{{ route('absensi.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus?')" class="btn btn-ghost btn-xs text-error">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-sm font-semibold text-muted">Belum ada data absensi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($absensi, 'links'))
        <div>{{ $absensi->withQueryString()->links() }}</div>
    @endif

</div>

@endsection
