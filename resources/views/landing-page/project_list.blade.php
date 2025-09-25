@extends('landing-page.layouts.main')

@section('content')
    <div class="container max-w-7xl mx-auto px-4 my-12">
        <!-- Page header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-base-content">Daftar Projek</h1>
            <p class="text-sm text-base-content/60">Projek manga yang sedang dikerjakan dan contoh-contohnya.</p>
        </div>

        <!-- Search and Filter Section -->
        <form method="GET" action="{{ route('landing-page.project_list') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Search Section -->
            <div class="md:col-span-1">
                <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari Manga..." class="input input-bordered w-full py-2" />
            </div>

            <!-- Search and Sort Section -->
            <div class="md:col-span-3">
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Genre Select -->
                    <div class="flex-1">
                        <select name="genre" id="genre" class="select select-bordered w-full py-2">
                            <option value="">Semua Genre</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->slug }}" @if(request('genre') == $g->slug) selected @endif>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex-1">
                        <select name="sort" class="select select-bordered w-full py-2">
                            <option value="latest" @if(request('sort','latest')=='latest') selected @endif>Terbaru</option>
                            <option value="popular" @if(request('sort')=='popular') selected @endif>Terpopuler</option>
                            <option value="alphabet" @if(request('sort')=='alphabet') selected @endif>A-Z</option>
                            <option value="chapter" @if(request('sort')=='chapter') selected @endif>Chapter Terbanyak</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div>
                        <button type="submit" class="btn btn-primary w-full md:w-auto py-2">Search</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Manga List Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($mangas as $m)
                <div class="rounded-lg shadow p-4 flex flex-col items-center bg-base-100 border border-base-200">
                    @if($m->cover_image)
                        <img src="{{ $m->cover_image }}" alt="{{ $m->title }}" class="w-24 h-32 object-cover rounded mb-3">
                    @else
                        <div class="w-24 h-32 bg-base-200 rounded mb-3 flex items-center justify-center text-sm">No Image</div>
                    @endif

                    <a href="{{ route('landing-page.detail_manga', ['slug' => $m->slug]) }}" class="font-semibold text-lg hover:underline text-center text-base-content">{{ $m->title }}</a>
                    <div class="text-sm text-base-content/60 mt-1">Chapter {{ $m->chapters_count ?? 0 }}</div>
                    <div class="flex flex-wrap gap-1 mt-2 justify-center">
                        @foreach($m->genres as $g)
                            <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-xs">{{ $g->name }}</span>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12 text-base-content/60">Tidak ada manga yang sesuai.</div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $mangas->links() }}
        </div>
    </div>
@endsection
