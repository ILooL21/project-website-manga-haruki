<!-- filepath: d:\Kumpulan Program dan Projek\project-website-manga-haruki\resources\views\landing-page\layouts\main.blade.php -->
<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Early theme init: read localStorage and set data-theme to avoid flash-of-unstyled-theme -->
    <script>
        (function(){
            try {
                var theme = localStorage.getItem('theme');
                if (!theme) {
                    // default to our softlight theme
                    theme = 'valentine';
                }
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                console && console.warn && console.warn('theme init failed', e);
            }
            // expose toggle helper for navbar control
            window.toggleTheme = function(checked){
                var t = checked ? 'dark' : 'valentine';
                try { localStorage.setItem('theme', t); } catch(e){}
                document.documentElement.setAttribute('data-theme', t);
            }
            window.syncThemeToggle = function(toggleEl){
                try{
                    var cur = localStorage.getItem('theme') || 'valentine';
                    toggleEl.checked = cur !== 'valentine';
                }catch(e){}
            }
        })();
    </script>
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
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    var el = document.getElementById('themeToggle');
                    if (el && window.syncThemeToggle) window.syncThemeToggle(el);
                });
            </script>
</body>

</html>