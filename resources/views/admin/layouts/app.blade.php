<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title') | Admin Panel Haruki Manga
    </title>
    @yield('styles')
</head>
<body>
    @include('admin.partials.header')
    @include('admin.partials.sidebar')
    <br>
    @yield('content')
    <br>
    @include('admin.partials.footer')

    @yield('scripts')
</body>
</html>