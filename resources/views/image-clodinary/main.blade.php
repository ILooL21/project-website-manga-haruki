<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CSS play CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Cloudinary Image Upload</title>
</head>
<body>
    <main class="px-20 py-10">
        @yield('content')
    </main>
</body>
</html>