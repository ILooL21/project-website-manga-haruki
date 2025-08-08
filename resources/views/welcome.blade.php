<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
  </head>
  <body>
    <!-- Header -->
    <header class="w-full bg-white shadow-md py-4 px-8 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <span class="text-2xl font-bold text-purple-600">HarukiManga</span>
      </div>
      <nav class="hidden md:flex gap-6">
        <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Home</a>
        <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Genre</a>
        <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Populer</a>
        <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Login</a>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-100 via-purple-100 to-pink-100 py-16 px-4 flex flex-col items-center">
      <h1 class="text-5xl font-extrabold text-purple-700 mb-4 text-center">Baca Manga Favoritmu Gratis!</h1>
      <p class="text-lg text-gray-700 mb-8 text-center max-w-xl">Temukan ribuan manga terbaru, populer, dan terupdate setiap hari. Nikmati pengalaman membaca yang nyaman dan responsif di HarukiManga.</p>
      <form class="flex w-full max-w-md mb-8">
        <input type="text" placeholder="Cari manga..." class="flex-1 px-4 py-2 rounded-l-lg border border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400" />
        <button class="px-6 py-2 bg-purple-600 text-white rounded-r-lg font-semibold hover:bg-purple-700 transition">Cari</button>
      </form>
      <!-- Manga List -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full max-w-5xl">
        <!-- Dummy Manga Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <img src="https://mangadex.org/covers/2e5e2e5e-2e5e-2e5e-2e5e-2e5e2e5e2e5e/cover.jpg" alt="Manga 1" class="w-full h-48 object-cover" />
          <div class="p-4">
            <h2 class="text-xl font-bold text-purple-700">One Piece</h2>
            <p class="text-gray-600 text-sm mb-2">Petualangan bajak laut mencari harta karun legendaris.</p>
            <span class="inline-block bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">Petualangan</span>
          </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <img src="https://mangadex.org/covers/3e5e3e5e-3e5e-3e5e-3e5e-3e5e3e5e3e5e/cover.jpg" alt="Manga 2" class="w-full h-48 object-cover" />
          <div class="p-4">
            <h2 class="text-xl font-bold text-purple-700">Naruto</h2>
            <p class="text-gray-600 text-sm mb-2">Kisah ninja muda yang ingin menjadi Hokage.</p>
            <span class="inline-block bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">Aksi</span>
          </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <img src="https://mangadex.org/covers/4e5e4e5e-4e5e-4e5e-4e5e-4e5e4e5e4e5e/cover.jpg" alt="Manga 3" class="w-full h-48 object-cover" />
          <div class="p-4">
            <h2 class="text-xl font-bold text-purple-700">Attack on Titan</h2>
            <p class="text-gray-600 text-sm mb-2">Manusia bertahan hidup dari serangan para Titan.</p>
            <span class="inline-block bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Fantasi</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="w-full py-6 bg-white text-center text-gray-500 mt-12 shadow-inner">
      &copy; 2025 HarukiManga. All rights reserved.
    </footer>
  </body>
</html>