@php
    $pageTitle = 'Profil';
    $pageSubtitle = 'Pengaturan akun & password';
    $layout = auth()->user()->role === 'supervisor' ? 'layouts.app-supervisor' : 'layouts.app-petugas';
@endphp
@extends($layout)

@section('content')

<section class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-5">

    {{-- Identity (sticky on desktop) --}}
    <aside class="lg:sticky lg:top-24 lg:self-start">
        <article class="surface-card overflow-hidden">
            {{-- Header --}}
            <div class="p-5">
                <div class="flex items-center gap-4">
                    <span class="w-14 h-14 rounded-2xl bg-brand-100 dark:bg-brand-900/40 text-brand-700 dark:text-brand-300 flex items-center justify-center text-lg font-extrabold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-base font-extrabold text-slate-900 dark:text-slate-100 tracking-tight truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @if($user->role === 'supervisor')
                        <span class="pill pill-info"><span class="dot"></span>Supervisor</span>
                    @else
                        <span class="pill pill-muted"><span class="dot"></span>Petugas</span>
                    @endif
                    @if($user->email_verified_at)
                        <span class="pill pill-success"><span class="dot"></span>Terverifikasi</span>
                    @endif
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 border-t border-slate-100 dark:border-white/5 divide-x divide-slate-100 dark:divide-white/5">
                @foreach($stats as $stat)
                    <div class="px-4 py-3">
                        <p class="text-[10px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase">{{ $stat['label'] }}</p>
                        <div class="mt-1 flex items-center gap-2">
                            <x-icon :name="$stat['icon']" class="w-4 h-4 text-brand-600 dark:text-brand-400 shrink-0" />
                            <p class="kpi-value text-lg font-bold text-slate-900 dark:text-slate-100">{{ $stat['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Meta info --}}
            <dl class="border-t border-slate-100 dark:border-white/5 divide-y divide-slate-100 dark:divide-white/5 text-sm">
                <div class="flex items-center gap-3 px-5 py-2.5">
                    <x-icon name="user-cog" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                    <dt class="text-xs text-slate-500 dark:text-slate-400 flex-1">Role</dt>
                    <dd class="text-xs font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $user->role }}</dd>
                </div>
                <div class="flex items-center gap-3 px-5 py-2.5">
                    <x-icon name="calendar" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                    <dt class="text-xs text-slate-500 dark:text-slate-400 flex-1">Bergabung</dt>
                    <dd class="text-xs font-semibold text-slate-900 dark:text-slate-100">{{ optional($user->created_at)->translatedFormat('d M Y') ?? '—' }}</dd>
                </div>
                <div class="flex items-center gap-3 px-5 py-2.5">
                    <x-icon name="refresh-cw" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                    <dt class="text-xs text-slate-500 dark:text-slate-400 flex-1">Update terakhir</dt>
                    <dd class="text-xs font-semibold text-slate-900 dark:text-slate-100">{{ optional($user->updated_at)->diffForHumans() ?? '—' }}</dd>
                </div>
                <div class="flex items-center gap-3 px-5 py-2.5">
                    <x-icon name="check-circle" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                    <dt class="text-xs text-slate-500 dark:text-slate-400 flex-1">Status</dt>
                    <dd class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Aktif</dd>
                </div>
            </dl>
        </article>
    </aside>

    {{-- Forms --}}
    <div class="space-y-5 min-w-0">


        {{-- Profile form --}}
        <article class="surface-card overflow-hidden">
            <header class="px-5 sm:px-6 py-5 border-b border-slate-100 dark:border-white/5">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Informasi Profil</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-0.5">Nama dan email yang ditampilkan di seluruh dashboard.</p>
            </header>

            <form method="POST" action="{{ route('profile.update') }}" class="p-5 sm:p-6 space-y-4" data-submit-text="Menyimpan profil...">
                @csrf @method('PATCH')

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    @error('name') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    @error('email') <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-white/5 -mx-5 sm:-mx-6 px-5 sm:px-6 mt-6 -mb-5 sm:-mb-6 pb-5 sm:pb-6">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 active:bg-brand-800 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
                        <x-icon name="save" class="w-4 h-4" />
                        Simpan
                    </button>
                </div>
            </form>
        </article>

        {{-- Password form --}}
        <article class="surface-card overflow-hidden">
            <header class="px-5 sm:px-6 py-5 border-b border-slate-100 dark:border-white/5">
                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Update Password</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-0.5">Gunakan password yang kuat agar akun tetap aman.</p>
            </header>

            <form method="POST" action="{{ route('password.update') }}" class="p-5 sm:p-6 space-y-4" data-submit-text="Mengubah password...">
                @csrf @method('PUT')

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password Saat Ini</label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                           class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                    @if($errors->updatePassword->get('current_password'))
                        <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Password Baru</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                               class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                        @if($errors->updatePassword->get('password'))
                            <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">Konfirmasi</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="mt-1 w-full px-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus-ring">
                        @if($errors->updatePassword->get('password_confirmation'))
                            <p class="text-xs font-semibold text-red-600 dark:text-red-400 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-white/5 -mx-5 sm:-mx-6 px-5 sm:px-6 mt-6 -mb-5 sm:-mb-6 pb-5 sm:pb-6">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 active:bg-slate-700 rounded-full cursor-pointer focus-ring transition-colors shadow-soft">
                        <x-icon name="lock" class="w-4 h-4" />
                        Ganti Password
                    </button>
                </div>
            </form>
        </article>
    </div>
</section>

@endsection
