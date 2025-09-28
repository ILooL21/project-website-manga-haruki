<dialog id="login_modal" class="modal">
    <div class="modal-box relative">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 class="font-bold text-2xl text-center mb-8">Sign In</h3>

        <div class="space-y-4">
            <a href="{{ route('login.google') }}" class="btn w-full font-semibold">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.222,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571l6.19,5.238C42.012,35.816,44,30.138,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                </svg>
                Sign in with Google
            </a>
        </div>

        <div class="divider text-sm my-6">OR</div>

        <div class="text-center text-sm">
            <p>Untuk mendapatkan password lihat tutorial ini:</p>
            <a href="#" class="link link-primary">Mengatur Password</a>
        </div>

    </div>
</dialog>
<div class="navbar bg-base-100 shadow-sm max-w-full">
    <div class="navbar-start">
        <a class="btn btn-ghost text-xl font-bold text-purple-600" href="{{ route('landing-page.index') }}">HarukiManga</a>
    </div>
    <div class="navbar-end flex items-center gap-6">
        <!-- Dropdown menu untuk layar kecil -->
        <div class="dropdown lg:hidden">
            <label tabindex="0" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="{{ route('landing-page.index') }}">Beranda</a></li>
                <li><a href="{{ route('landing-page.project_list') }}">Project</a></li>
                @auth
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full text-left">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a onclick="login_modal.showModal()">Login</a></li>
                @endauth
            </ul>
        </div>
        <!-- Menu untuk layar besar -->
        <ul class="hidden lg:flex menu menu-horizontal">
            <li><a href="{{ route('landing-page.index') }}">Beranda</a></li>
            <li><a href="{{ route('landing-page.project_list') }}">Project</a></li>
            @auth
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full text-left">Logout</button>
                    </form>
                </li>
            @else
                <li><a onclick="login_modal.showModal()">Login</a></li>
            @endauth
        </ul>
        <!-- Tombol Search -->
        <button class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <!-- Theme toggle -->
        <div class="flex items-center">
            <label class="swap swap-rotate">
                <input id="themeToggle" type="checkbox" onchange="toggleTheme(this.checked)" />
                <!-- sun (light) -->
                <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M5.64 17.657l-.707.707-1.414-1.414.707-.707a8 8 0 001.414 1.414zM12 18a6 6 0 100-12 6 6 0 000 12zm6.36-2.343l1.414 1.414-.707.707a8 8 0 00-1.414-1.414l.707-.707zM12 2v2a8 8 0 010 16v2a10 10 0 000-20z" />
                </svg>
                <!-- moon (dark) -->
                <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M21.64 13.418A9 9 0 1110.582 2.36 7 7 0 0021.64 13.418z" />
                </svg>
            </label>
        </div>
    </div>
</div>
