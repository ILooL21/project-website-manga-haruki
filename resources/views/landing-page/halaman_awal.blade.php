@extends('landing-page.layouts.main')

@section('title', 'Haruki')

@section('content')
    <div x-data='{
        mangas: @json($mangas ?? []),
        current: 0,
        next() { if(this.mangas.length) this.current = (this.current + 1) % this.mangas.length },
        prev() { if(this.mangas.length) this.current = (this.current - 1 + this.mangas.length) % this.mangas.length },
        goTo(idx) { this.current = idx },
    }'
        class="relative w-full h-[500px] flex items-center justify-center bg-base-200 overflow-hidden rounded-xl mx-auto max-w-7xl mt-4">
        <!-- Background Blur -->
        <template x-for="(manga, idx) in mangas" :key="idx">
            <div x-show="current === idx" class="absolute inset-0 w-full h-full z-0">
                <img :src="manga.img" alt="cover"
                    class="object-cover w-full h-full blur-lg opacity-40">
            </div>
        </template>
        <!-- Carousel Content -->
        <div class="relative z-10 w-full flex items-center justify-between px-8 py-8">
            <!-- Manga Detail -->
            <div class="w-full md:w-1/2 text-base-content">
                <template x-for="(manga, idx) in mangas" :key="idx">
                    <div x-show="current === idx" class="transition-all duration-500">
                        <div class="text-lg font-semibold mb-2">Chapter: <span x-text="manga.chapter ?? '-' "></span></div>
                        <div class="text-4xl md:text-5xl font-bold mb-4">
                            <a :href="manga.url" class="hover:underline" x-text="manga.title"></a>
                        </div>
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </template>
            </div>
            <!-- Manga Cover -->
            <div class="hidden md:flex w-1/2 justify-end items-center">
                <template x-for="(manga, idx) in mangas" :key="idx">
                    <div x-show="current === idx" class="transition-all duration-500">
                        <a :href="manga.url" class="block transform-gpu">
                            <img :src="manga.img" alt="cover"
                                class="w-[350px] h-[480px] object-cover rounded-xl shadow-2xl border-4 border-base-200/40"
                                style="transform: skewX(-10deg);">
                        </a>
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
                    @if($iklanBanner && ($iklanBanner->image_path ?? null))
                        <figure>
                            <a href="{{ $iklanBanner->link ?? '#' }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $iklanBanner->image_path }}" alt="{{ $iklanBanner->section ?? 'Iklan' }}"
                                    class="rounded-md w-[320px] h-[180px] md:w-[400px] md:h-[220px] lg:w-[480px] lg:h-[260px] shadow-md object-fitr" />
                            </a>
                        </figure>
                    @else
                        <figure>
                            <img src="{{ asset('images/iklan.webp') }}" alt="Iklan"
                                class="rounded-md w-[320px] h-[180px] md:w-[400px] md:h-[220px] lg:w-[480px] lg:h-[260px] shadow-md object-cover" />
                        </figure>
                    @endif
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
            <div class="col-span-12 md:col-span-6 lg:col-span-4 space-y-4">
                <!-- New Releases -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Rilisan Baru</h2>
                        <ul class="space-y-3">
                            @foreach($newReleases ?? [] as $r)
                                <li class="flex items-center gap-3">
                                    <img src="{{ $r['cover'] }}" alt="{{ $r['title'] }}" class="w-12 h-16 object-cover rounded shadow">
                                    <div>
                                        <a href="{{ $r['url'] }}" class="font-semibold hover:underline">{{ $r['title'] }}</a>
                                        <div class="text-sm text-gray-500">{{ $r['created_human'] ?? '' }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Random Spotlight -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Rekomendasi</h2>
                        <ul class="space-y-3">
                            @foreach($spotlight ?? [] as $s)
                                <li class="flex items-center gap-3">
                                    <img src="{{ $s['cover'] }}" alt="{{ $s['title'] }}" class="w-12 h-16 object-cover rounded shadow">
                                    <div>
                                        <a href="{{ $s['url'] }}" class="font-semibold hover:underline">{{ $s['title'] }}</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-12 lg:col-span-12 card bg-base-100 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4">Projek Manga yang Dikerjakan</h2>
                    {{-- Projek Komik Section --}}
                    @php
                        // projek awal yang dirender dari server
                        $projects = app(\App\Http\Controllers\LandingPage\HalamanAwalController::class)->latestProjects();
                    @endphp

                    @include('landing-page.partials.projects_grid', ['projects' => $projects])

                    <script>
                        (function(){
                            // polling configuration
                            const url = '{{ route('landing-page.projects_fragment') }}';
                            let interval = 8000; // 8s
                            let backoff = 1;
                            let timer = null;

                            async function fetchAndReplace(){
                                if (document.hidden) return schedule();
                                try{
                                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                                    if (!res.ok) throw new Error('Network response not ok');
                                    const html = await res.text();
                                    // parse and extract the fragment
                                    const tmp = document.createElement('div');
                                    tmp.innerHTML = html;
                                    const newGrid = tmp.querySelector('#projects-grid');
                                    const oldGrid = document.querySelector('#projects-grid');
                                    if (newGrid && oldGrid && newGrid.innerHTML !== oldGrid.innerHTML){
                                        oldGrid.innerHTML = newGrid.innerHTML;
                                    }
                                    backoff = 1; // reset on success
                                }catch(e){
                                    console.error('projects poll error', e);
                                    backoff = Math.min(backoff * 2, 8); // up to 8x
                                }
                                schedule();
                            }

                            function schedule(){
                                clearTimeout(timer);
                                timer = setTimeout(fetchAndReplace, interval * backoff);
                            }

                            // start
                            document.addEventListener('visibilitychange', function(){ if(!document.hidden) fetchAndReplace(); });
                            schedule();
                        })();
                    </script>
                </div>
            </div>
            <!-- Komik Terpopuler dihapus; ruang digunakan untuk memperluas konten utama -->
            <!-- Tambahkan lebih banyak kartu di sini -->
        </div>
    </div>
    </div>
@endsection
