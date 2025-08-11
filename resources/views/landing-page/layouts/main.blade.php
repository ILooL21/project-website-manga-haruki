<!-- filepath: d:\Kumpulan Program dan Projek\project-website-manga-haruki\resources\views\landing-page\layouts\main.blade.php -->
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    <title>@yield('title', 'Haruki')</title>
</head>

<body class="min-h-screen flex flex-col overflow-x-hidden">
    <!-- Header -->
    @include('landing-page.layouts.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('landing-page.layouts.footer')
</body>

</html>