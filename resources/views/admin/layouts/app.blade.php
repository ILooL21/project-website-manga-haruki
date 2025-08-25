<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title') | Admin Panel Haruki Manga
    </title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    
    @yield('styles')
</head>
<body>
    <div x-data="{ userDropdownOpen: false, notificationsDropdownOpen: false, mobileNavOpen: false }">
        <!-- Page Container -->
        <div id="page-container" class="mx-auto flex min-h-screen w-full min-w-[320px] flex-col bg-zinc-100">
            <!-- Page Header -->
            @include('admin.partials.header')
            <!-- END Page Header -->

            <!-- Page Content -->
            <main id="page-content"
                class="mx-auto flex w-full flex-auto flex-col border-y-8 border-zinc-200/60 bg-white sm:max-w-2xl sm:rounded-xl sm:border-8 md:max-w-3xl lg:max-w-5xl xl:max-w-7xl">
               @yield('content')
            </main>
            <!-- END Page Content -->

            <!-- Page Footer -->
            @include('admin.partials.footer')
            <!-- END Page Footer -->
        </div>
        <!-- END Page Container -->
    </div>
    
    @yield('scripts')
</body>
</html>