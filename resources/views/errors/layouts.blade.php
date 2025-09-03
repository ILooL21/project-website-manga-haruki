<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <!-- Early theme init: baca localStorage agar page error juga sesuai tema tanpa flash -->
  <script>
    (function(){
      try {
        var theme = localStorage.getItem('theme') || 'valentine';
        document.documentElement.setAttribute('data-theme', theme);
      } catch(e){}
    })();
  </script>

  <title>{{ $title ?? (isset($code) ? $code.' • Error' : 'Error') }}</title>
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-base-200 text-base-content">
  <div class="container mx-auto px-4 py-12">
    <div class="bg-base-100 shadow-lg rounded-lg overflow-hidden flex flex-col md:flex-row items-center gap-6 p-6">
      <div class="w-full md:w-1/2 text-center md:text-left px-4 py-6">
        <h1 class="text-6xl md:text-7xl font-extrabold text-primary mb-4">@yield('code', 'Error')</h1>
        <h2 class="text-2xl md:text-3xl font-semibold mb-3">@yield('title', 'Terjadi Kesalahan')</h2>
        <p class="text-base-content/70 mb-6">@yield('message', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.')</p>
        <div class="flex justify-center md:justify-start gap-3">
          <a href="{{ url('/') }}" class="btn btn-ghost">Beranda</a>
          <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
        </div>
      </div>

      <div class="w-full md:w-1/2 flex items-center justify-center p-4">
        <img src="{{ asset('images/mascot.jpg') }}" alt="Illustration" class="max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg object-contain"/>
      </div>
    </div>
  </div>
</body>
</html>