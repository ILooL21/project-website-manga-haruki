<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Manga Website Home
    </title>
</head>
<body>
    <h1>Welcome to the Manga Website</h1>
    <p>Your one-stop destination for all things manga!</p>
    <p>Name: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <br>
    {{-- jika ada auth munculkan profile jika tidak login register --}}
    @if (Auth::check())
        <a href="{{ route('profile') }}">Profile</a>
        <br>
        <br>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @else
        <a href="{{ route('login') }}">Login</a>
        <br>
        <a href="{{ route('register') }}">Register</a>
    @endif
    <br>
    <br>

    @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'Super Admin')
        <a href="{{ route('admin.dashboard') }}">Go to Admin Dashboard</a>
    @endif

    @if (session('status'))
       <p>Status: {{ session('status') }}</p>
       <p>Message: {{ session('message') }}</p>
       <br>
    @endif
</body>
</html>