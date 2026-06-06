@php
    $pageTitle = 'Petugas';
    $pageSubtitle = 'Manajemen akun pengguna';

    // Pre-encode petugas data buat dipakai modal edit (tanpa password)
    $petugasJson = $petugas->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'email' => $p->email,
        'role' => $p->role,
        'updateUrl' => route('petugas.update', $p),
    ])->keyBy('id');

    $hasErrors = $errors->any();
    $oldRole = old('role', 'petugas');
    $editingId = old('_editing_id');
@endphp
@extends('layouts.app-supervisor')

@section('content')

<section
    x-data="{
        mode: 'create',
        action: @js(route('petugas.store')),
        method: 'POST',
        form: { id: null, name: '', email: '', role: 'petugas', password: '' },
        records: @js($petugasJson),
        openCreate() {
            this.mode = 'create';
            this.action = @js(route('petugas.store'));
            this.method = 'POST';
            this.form = { id: null, name: @js(old('name', '')), email: @js(old('email', '')), role: @js(old('role', 'petugas')), password: '' };
            this.$dispatch('open-modal-petugas', { title: 'Tambah Petugas', subtitle: 'Buat akun baru untuk petugas atau supervisor.' });
        },
        openEdit(id) {
            const r = this.records[id];
            if (!r) return;
            this.mode = 'edit';
            this.action = r.updateUrl;
            this.method = 'PUT';
            this.form = { id: r.id, name: r.name, email: r.email, role: r.role, password: '' };
            this.$dispatch('open-modal-petugas', { title: 'Edit Petugas', subtitle: 'Perbarui data akun ' + r.name + '.' });
        },
        init() {
            @if($hasErrors)
                @if($editingId && isset($petugasJson[$editingId]))
                    this.openEdit({{ (int) $editingId }});
                @else
                    this.openCreate();
                @endif
            @endif
        }
    }"
    class="space-y-5"
>
    {{-- Title row --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <p class="eyebrow">Manajemen Akun</p>
            <h1 class="mt-1.5 text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">Data Petugas</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ $petugas->count() }} akun terdaftar di sistem.</p>
        </div>
        <button type="button" x-on:click="openCreate()"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft self-start">
            <x-icon name="plus" class="w-4 h-4" />
            Tambah Petugas
        </button>
    </div>


    {{-- Mobile cards --}}
    <div class="space-y-3 lg:hidden">
        @forelse($petugas as $item)
            <article class="surface-card p-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold shrink-0">
                        {{ strtoupper(substr($item->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">{{ $item->name }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 truncate">{{ $item->email }}</p>
                    </div>
                    @if($item->role === 'supervisor')
                        <span class="pill pill-info"><span class="dot"></span>Supervisor</span>
                    @else
                        <span class="pill pill-muted"><span class="dot"></span>Petugas</span>
                    @endif
                </div>
                <div class="mt-3 flex items-center gap-1 border-t border-slate-100 dark:border-white/5 pt-3">
                    <button type="button" x-on:click="openEdit({{ $item->id }})"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-500/10 dark:bg-amber-500/10 rounded-full cursor-pointer focus-ring">
                        <x-icon name="pencil" class="w-3.5 h-3.5" />Edit
                    </button>
                    <form action="{{ route('petugas.destroy', $item) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="button" data-confirm="Hapus {{ $item->name }}?"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10 dark:bg-red-500/10 rounded-full cursor-pointer focus-ring">
                            <x-icon name="trash-2" class="w-3.5 h-3.5" />Hapus
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="surface-card p-10 text-center">
                <span class="mx-auto w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                    <x-icon name="users" class="w-5 h-5" />
                </span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada akun terdaftar</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop table --}}
    <article class="surface-card hidden lg:block overflow-hidden">
        <div class="overflow-x-auto thin-scroll">
            <table class="w-full text-sm">
                <thead class="text-[11px] text-slate-500 dark:text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/60/60 dark:bg-slate-800/40">
                    <tr class="text-left">
                        <th class="font-semibold px-5 py-3">Nama</th>
                        <th class="font-semibold px-5 py-3">Email</th>
                        <th class="font-semibold px-5 py-3">Role</th>
                        <th class="font-semibold px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($petugas as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 dark:bg-slate-800/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($item->name, 0, 2)) }}
                                    </span>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300">{{ $item->email }}</td>
                            <td class="px-5 py-3">
                                @if($item->role === 'supervisor')
                                    <span class="pill pill-info"><span class="dot"></span>Supervisor</span>
                                @else
                                    <span class="pill pill-muted"><span class="dot"></span>Petugas</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" x-on:click="openEdit({{ $item->id }})"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                            aria-label="Edit">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </button>
                                    <form action="{{ route('petugas.destroy', $item) }}" method="POST" class="inline-flex">
                                        @csrf @method('DELETE')
                                        <button type="button" data-confirm="Hapus {{ $item->name }}?"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer focus-ring"
                                                aria-label="Hapus">
                                            <x-icon name="trash-2" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-12 text-center text-sm font-semibold text-slate-700 dark:text-slate-300">Belum ada akun terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

    {{-- Modal: create/edit form --}}
    <x-modal name="petugas" max-width="sm:max-w-lg" title="Tambah Petugas" subtitle="Buat akun baru.">
        <form id="petugas-form" :action="action" method="POST" class="space-y-4">
            @csrf
            <template x-if="method === 'PUT'"><input type="hidden" name="_method" value="PUT"></template>
            <input type="hidden" name="_editing_id" :value="form.id">

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Masukkan nama"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    @error('name') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <input type="email" name="email" x-model="form.email" required placeholder="nama@email.com"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span x-show="mode === 'create'">Password</span>
                            <span x-show="mode === 'edit'">Password Baru <span class="text-slate-400 dark:text-slate-500 font-normal">(opsional)</span></span>
                        </label>
                        <input type="password" name="password" x-model="form.password"
                               :required="mode === 'create'"
                               :placeholder="mode === 'create' ? 'Minimal 6 karakter' : 'Kosongkan jika tidak diubah'"
                               class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                        @error('password') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Role</label>
                        <select name="role" x-model="form.role" required
                                class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                            <option value="petugas">Petugas</option>
                            <option value="supervisor">Supervisor</option>
                        </select>
                        @error('role') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </form>

        <x-slot:footer>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                <button type="button" x-on:click="$dispatch('close-modal-petugas')"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20 active:bg-slate-50 dark:bg-slate-800/60 rounded-full cursor-pointer focus-ring transition-colors">
                    Batal
                </button>
                <button type="submit" form="petugas-form"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 active:bg-brand-800 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
                    <x-icon name="save" class="w-4 h-4" />
                    <span x-text="mode === 'create' ? 'Simpan Akun' : 'Simpan Perubahan'"></span>
                </button>
            </div>
        </x-slot:footer>
    </x-modal>
</section>

@endsection
