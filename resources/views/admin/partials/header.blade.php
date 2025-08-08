<header>
    <h1>Admin Panel Haruki Manga</h1>
    <h2>Header</h2>
    <nav>
        <ul>
            <li><a href="{{ route('profile') }}">Profile</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</header>
