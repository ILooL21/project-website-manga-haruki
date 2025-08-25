<div class="navbar bg-base-100 shadow-sm max-w-full">
  <div class="navbar-start">
    <a class="btn btn-ghost text-xl font-bold text-purple-600">HarukiManga</a>
  </div>
  <div class="navbar-end flex items-center gap-6">
    <!-- Dropdown menu untuk layar kecil -->
    <div class="dropdown lg:hidden">
      <label tabindex="0" class="btn btn-ghost">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>
      <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
        <li><a href="{{ route('landing-page.index') }}">Beranda</a></li>
        <li><a href="{{ route('landing-page.manga_list') }}">Manga/Manhwa List</a></li>
        <li><a href="{{ route('landing-page.project_list') }}">Project</a></li>
      </ul>
    </div>
    <!-- Menu untuk layar besar -->
    <ul class="hidden lg:flex menu menu-horizontal">
      <li><a href="{{ route('landing-page.index') }}">Beranda</a></li>
      <li><a href="{{ route('landing-page.manga_list') }}">Manga/Manhwa List</a></li>
      <li><a href="{{ route('landing-page.project_list') }}">Project</a></li>
    </ul>
    <!-- Tombol Search -->
    <button class="btn btn-ghost btn-circle">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </button>
    <!-- Tombol Notifikasi -->
    <button class="btn btn-ghost btn-circle">
      <div class="indicator">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="badge badge-xs badge-primary indicator-item"></span>
      </div>
    </button>
      <!-- Theme toggle -->
      <div class="flex items-center">
        <label class="swap swap-rotate">
          <input id="themeToggle" type="checkbox" onchange="toggleTheme(this.checked)" />
          <!-- sun (light) -->
          <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M5.64 17.657l-.707.707-1.414-1.414.707-.707a8 8 0 001.414 1.414zM12 18a6 6 0 100-12 6 6 0 000 12zm6.36-2.343l1.414 1.414-.707.707a8 8 0 00-1.414-1.414l.707-.707zM12 2v2a8 8 0 010 16v2a10 10 0 000-20z"/></svg>
          <!-- moon (dark) -->
          <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.64 13.418A9 9 0 1110.582 2.36 7 7 0 0021.64 13.418z"/></svg>
        </label>
      </div>
  </div>
</div>