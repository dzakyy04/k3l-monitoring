<nav class="navbar bg-base-100 border-b border-base-300">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost text-lg font-extrabold text-base-content">
            K3L Monitoring
        </a>

        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-2">
                <span class="text-sm font-bold">{{ Auth::user()->name }}</span>
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
            <ul tabindex="0" class="menu dropdown-content z-50 w-52 rounded-xl bg-base-100 p-2 shadow-lg ring-1 ring-base-300">
                <li><a href="{{ route('profile.edit') }}" class="font-semibold">Profil</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Yakin ingin logout?')" class="w-full text-left font-semibold text-error">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
