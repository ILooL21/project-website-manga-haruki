<aside>
    <h2>Sidebar</h2>
    <ul>
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        @if(auth()->user()->role === 'Super Admin')
            <li><a href="{{ route('admin.users') }}">Users</a></li>
        @endif
        <li><a href="{{ route('admin.genres') }}">Genres</a></li>
        <li><a>Mangas</a></li>
    </ul>
</aside>
