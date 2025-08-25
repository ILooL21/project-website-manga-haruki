@extends('landing-page.layouts.main')

@section('title', 'Haruki')

@section('content')
    <div x-data="{
        mangas: [{
                chapter: '249',
                title: 'Mercenary Enrollment',
                desc: 'Yu Ijin adalah satu-satunya yang selamat dari kecelakaan pesawat saat dia masih kecil. Setelah menjadi tentara bayaran untuk bertahan hidup selama 10 tahun, ia kembali ke keluarganya di kampung.',
                genres: ['Action', 'Drama', 'Romance', 'School Life', 'Sci-fi'],
                img: 'images/pomu.webp',
                url: '#'
            },
            // Tambahkan manga lain di sini
        ],
        current: 0,
        next() { this.current = (this.current + 1) % this.mangas.length },
        prev() { this.current = (this.current - 1 + this.mangas.length) % this.mangas.length },
        goTo(idx) { this.current = idx },
    }"
        class="relative w-full h-[500px] flex items-center justify-center bg-base-200 overflow-hidden rounded-xl mx-auto max-w-7xl mt-4">
        <!-- Background Blur -->
        <template x-for="(manga, idx) in mangas" :key="idx">
            <div x-show="current === idx" class="absolute inset-0 w-full h-full z-0">
                <img :src="'{{ asset('') }}' + manga.img" alt="cover"
                    class="object-cover w-full h-full blur-lg opacity-40">
            </div>
        </template>
        <!-- Carousel Content -->
        <div class="relative z-10 w-full flex items-center justify-between px-8 py-8">
            <!-- Manga Detail -->
            <div class="w-full md:w-1/2 text-base-content">
                <template x-for="(manga, idx) in mangas" :key="idx">
                    <div x-show="current === idx" class="transition-all duration-500">
                        <div class="text-lg font-semibold mb-2">Chapter: <span x-text="manga.chapter"></span></div>
                        <div class="text-4xl md:text-5xl font-bold mb-4" x-text="manga.title"></div>
                        <div class="mb-6 text-lg" x-text="manga.desc"></div>
                        <div class="flex flex-wrap gap-2 mb-8">
                            <template x-for="genre in manga.genres" :key="genre">
                                <span class="px-4 py-1 rounded-md bg-base-100/40 border border-base-200/50 text-sm font-medium text-base-content"
                                    x-text="genre"></span>
                            </template>
                        </div>
                        <a :href="manga.url"
                            class="inline-flex items-center px-6 py-3 bg-yellow-400 text-black font-semibold rounded-lg shadow hover:bg-yellow-500 transition">
                            Mulai Membaca
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </template>
            </div>
            <!-- Manga Cover -->
            <div class="hidden md:flex w-1/2 justify-end items-center">
                <template x-for="(manga, idx) in mangas" :key="idx">
                    <div x-show="current === idx" class="transition-all duration-500">
                        <img :src="'{{ asset('') }}' + manga.img" alt="cover"
                            class="w-[350px] h-[480px] object-cover rounded-xl shadow-2xl border-4 border-base-200/40"
                            style="transform: skewX(-10deg);">
                    </div>
                </template>
            </div>
        </div>
        <!-- Indicators -->
        <div class="absolute bottom-8 right-8 flex gap-3 z-20">
            <template x-for="(manga, idx) in mangas" :key="idx">
                <button @click="goTo(idx)" :class="current === idx ? 'bg-primary' : 'bg-base-300'"
                    class="w-4 h-4 rounded-full transition"></button>
            </template>
        </div>
        <!-- Controls -->
        <button @click="prev"
            class="absolute top-1/2 left-6 -translate-y-1/2 z-20 bg-base-200/60 hover:bg-base-200 rounded-full p-3 focus:outline-none">
            <svg class="w-6 h-6 text-base-content" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button @click="next"
            class="absolute top-1/2 right-6 -translate-y-1/2 z-20 bg-base-200/60 hover:bg-base-200 rounded-full p-3 focus:outline-none">
            <svg class="w-6 h-6 text-base-content" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <div class="container max-w-7xl mx-auto px-4 my-12">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 md:col-span-6 lg:col-span-8 card bg-base-100 shadow-sm">
                <!-- Iklan Section -->
                <div class="flex flex-col items-center justify-center py-8">
                    <span class="text-sm text-gray-500 mb-2">Sponsored</span>
                    <figure>
                        <img src="{{ asset('images/iklan.webp') }}" alt="Iklan"
                            class="rounded-md w-[320px] h-[180px] md:w-[400px] md:h-[220px] lg:w-[480px] lg:h-[260px] shadow-md object-cover" />
                    </figure>
                    {{-- <div class="mt-4 text-center">
                        <h2 class="text-lg font-semibold">Iklan Spesial</h2>
                        <p class="text-gray-600">Dapatkan penawaran menarik hanya di sini! Klik gambar untuk info lebih
                            lanjut.</p>
                        <div class="mt-3">
                            <a href="#" class="btn btn-warning">Lihat Iklan</a>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 card bg-base-100 shadow-sm">
                <!-- Riwayat Baca Pengguna -->
                <div class="card-body">
                    <h2 class="card-title">Riwayat Baca Anda</h2>
                    <ul class="space-y-4">
                        <!-- Contoh riwayat, ganti dengan data dinamis jika tersedia -->
                        <li class="flex items-center gap-3">
                            <img src="images/pomu.webp" alt="Mercenary Enrollment"
                                class="w-12 h-16 object-cover rounded shadow">
                            <div>
                                <a href="#" class="font-semibold text-base hover:underline">Mercenary Enrollment</a>
                                <div class="text-sm text-gray-500">Chapter 249 • Terakhir dibaca 2 hari lalu</div>
                            </div>
                        </li>
                        <!-- Tambahkan riwayat lain di sini -->
                    </ul>
                    <div class="card-actions justify-end mt-4">
                        <a href="#" class="btn btn-secondary">Lihat Semua Riwayat</a>
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-8 lg:col-span-8 card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Projek Manga yang Dikerjakan</h2>
                    {{-- Projek Komik Section --}}
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                            <img src="images/pomu.webp" alt="Mercenary Enrollment"
                                class="w-24 h-32 object-cover rounded mb-3">
                            <a href="#" class="font-semibold text-lg hover:underline text-center text-base-content">Mercenary
                                Enrollment</a>
                            <div class="text-sm text-base-content/60 mt-1">Chapter 249</div>
                            <div class="flex flex-wrap gap-1 mt-2 justify-center">
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Action</span>
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Drama</span>
                            </div>
                        </div>
                        <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                            <img src="images/yuji.webp" alt="Sample Manga 2" class="w-24 h-32 object-cover rounded mb-3">
                            <a href="#" class="font-semibold text-lg hover:underline text-center text-base-content">Sample
                                Manga 2</a>
                            <div class="text-sm text-base-content/60 mt-1">Chapter 120</div>
                            <div class="flex flex-wrap gap-1 mt-2 justify-center">
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Romance</span>
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Comedy</span>
                            </div>
                        </div>
                        <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                            <img src="images/slamdunknew01.webp" alt="Sample Manga 3"
                                class="w-24 h-32 object-cover rounded mb-3">
                            <a href="#" class="font-semibold text-lg hover:underline text-center text-base-content">Sample
                                Manga 3</a>
                            <div class="text-sm text-base-content/60 mt-1">Chapter 87</div>
                            <div class="flex flex-wrap gap-1 mt-2 justify-center">
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Fantasy</span>
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Adventure</span>
                            </div>
                        </div>
                        <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                            <img src="images/slamdunknew01.webp" alt="Sample Manga 4"
                                class="w-24 h-32 object-cover rounded mb-3">
                            <a href="#" class="font-semibold text-lg hover:underline text-center text-base-content">Sample
                                Manga 4</a>
                            <div class="text-sm text-base-content/60 mt-1">Chapter 45</div>
                            <div class="flex flex-wrap gap-1 mt-2 justify-center">
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">School
                                    Life</span>
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Slice of
                                    Life</span>
                            </div>
                        </div>
                        <!-- Tambahkan projek manga lain di sini -->
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-4 lg:col-span-4 card bg-base-100 shadow-sm">
                <!-- Komik Terpopuler Section -->
                <div class="card-body">
                    <h2 class="card-title">Komik Terpopuler</h2>
                    <ul class="space-y-4">
                        <!-- Contoh komik populer, ganti dengan data dinamis jika tersedia -->
                        <li class="flex items-center gap-3">
                            <img src="images/pomu.webp" alt="Mercenary Enrollment"
                                class="w-12 h-16 object-cover rounded shadow">
                            <div>
                                <a href="#" class="font-semibold text-base hover:underline">Mercenary Enrollment</a>
                                <div class="text-sm text-gray-500">Chapter 249 • 1.2M views</div>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <img src="images/slamdunknew01.webp" alt="Sample Manga 2"
                                class="w-12 h-16 object-cover rounded shadow">
                            <div>
                                <a href="#" class="font-semibold text-base hover:underline">Sample Manga 2</a>
                                <div class="text-sm text-gray-500">Chapter 120 • 950K views</div>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <img src="images/slamdunknew01.webp" alt="Sample Manga 3"
                                class="w-12 h-16 object-cover rounded shadow">
                            <div>
                                <a href="#" class="font-semibold text-base hover:underline">Sample Manga 3</a>
                                <div class="text-sm text-gray-500">Chapter 87 • 800K views</div>
                            </div>
                        </li>
                        <!-- Tambahkan komik populer lain di sini -->
                    </ul>
                    <div class="card-actions justify-end mt-4">
                        <a href="#" class="btn btn-secondary">Lihat Semua Komik Populer</a>
                    </div>
                </div>
            </div>
            <!-- Tambahkan lebih banyak kartu di sini -->
        </div>
    </div>
    </div>
@endsection
