@extends('landing-page.layouts.main')

@section('content')
    <div class="container max-w-7xl mx-auto px-4 my-12">
        <!-- Page header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-base-content">Daftar Manga</h1>
            <p class="text-sm text-base-content/60">Telusuri koleksi manga dan manhwa yang tersedia.</p>
        </div>
        <!-- Search and Filter Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Genre Section -->
            <div class="md:col-span-1">
                <input type="text" placeholder="Cari Manga..." class="input input-bordered w-full py-2" />
            </div>

            <!-- Search and Sort Section -->
            <div class="md:col-span-3">
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <select name="genre" id="genre" class="select select-bordered w-full py-2">
                            <option value="">Semua Genre</option>
                            <option value="action">Action</option>
                            <option value="adventure">Adventure</option>
                            <option value="comedy">Comedy</option>
                            <option value="drama">Drama</option>
                            <option value="fantasy">Fantasy</option>
                            <option value="romance">Romance</option>
                            <option value="school">School</option>
                            <option value="slice-of-life">Slice of Life</option>
                            <option value="supernatural">Supernatural</option>
                            <option value="horror">Horror</option>
                            <option value="mystery">Mystery</option>
                            <option value="sci-fi">Sci-Fi</option>
                            <option value="sports">Sports</option>
                            <option value="isekai">Isekai</option>
                        </select>
                    </div>
                    <!-- Sort Dropdown -->
                    <div class="flex-1">
                        <select class="select select-bordered w-full py-2">
                            <option value="latest">Terbaru</option>
                            <option value="popular">Terpopuler</option>
                            <option value="alphabet">A-Z</option>
                            <option value="chapter">Chapter Terbanyak</option>
                        </select>
                    </div>
                    <!-- Search Button -->
                    <div>
                        <button class="btn btn-primary w-full md:w-auto py-2">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manga List Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Contoh Manga -->
            <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                <img src="images/pomu.webp" alt="Mercenary Enrollment" class="w-24 h-32 object-cover rounded mb-3">
                <a href="#" class="font-semibold text-lg hover:underline text-center text-base-content">Mercenary
                    Enrollment</a>
                <div class="text-sm text-base-content/60 mt-1">Chapter 249</div>
                <div class="flex flex-wrap gap-1 mt-2 justify-center">
                    <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Action</span>
                    <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">Drama</span>
                </div>
            </div>
            <!-- Tambahkan lebih banyak manga di sini -->
        </div>
    </div>
@endsection
