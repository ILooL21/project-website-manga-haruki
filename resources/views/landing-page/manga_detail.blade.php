@extends('landing-page.layouts.main')

@section('title', $manga->title)

@section('content')
    <div class="container mx-auto px-4 my-12">
        <!-- Header Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manga Cover -->
            <div class="md:col-span-1 flex justify-center">
                <img src="{{ $coverUrl }}" alt="{{ $manga->title }}"
                    class="rounded-lg shadow-md w-[300px] h-[400px] object-cover">
            </div>
            <!-- Manga Info -->
            <div class="md:col-span-2">
                <h1 class="text-3xl font-bold text-base-content mb-4">{{ $manga->title }}</h1>
                <p class="text-base-content/70 mb-6">{{ $manga->description }}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach ($manga->genres as $g)
                        <span class="px-3 py-1 rounded bg-primary/10 text-primary text-sm">{{ $g->name }}</span>
                    @endforeach
                </div>
                <div class="flex items-center gap-4 mb-4 text-base-content/70">
                    <span>Author: <span
                            class="text-base-content">{{ optional($manga->author)->name ?? 'Unknown' }}</span></span>
                    <span>Status: <span class="text-base-content">{{ $manga->status ?? 'Unknown' }}</span></span>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('landing-page.manga_pages', ['slug' => $manga->slug, 'chapter_number' => $firstChapterNumber ?? (optional($chapters->first())->chapter_number ?? 1)]) }}"
                        class="btn btn-primary">Baca</a>
                    {{-- <button class="btn btn-outline">Bookmark</button>
                    <button class="btn btn-outline">Tambah ke Read List</button> --}}
                </div>
            </div>
        </div>

        <!-- Chapter List Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-base-content mb-4">Daftar Chapter</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <!-- Chapter list -->
                @forelse($chapters as $chapter)
                    <a href="{{ route('landing-page.manga_pages', ['slug' => $manga->slug, 'chapter_number' => $chapter->chapter_number]) }}"
                        class="block bg-base-200 p-4 rounded-lg shadow-md hover:bg-primary/10"
                        aria-label="Buka Chapter {{ $chapter->chapter_number }}">
                        <h3 class="text-lg font-semibold text-base-content">Chapter {{ $chapter->chapter_number }} -
                            {{ $chapter->title }}</h3>
                        <p class="text-base-content/60 text-sm">
                            {{ $chapter->release_date ? $chapter->release_date->diffForHumans() : '' }}</p>
                    </a>
                @empty
                    <p class="text-base-content/70">Belum ada chapter tersedia.</p>
                @endforelse
                <!-- Tambahkan lebih banyak chapter -->
            </div>
            <!-- Pagination -->
            <div class="flex justify-center mt-6 gap-3">
                @if ($chapters->lastPage() > 1)
                    @for ($i = 1; $i <= $chapters->lastPage(); $i++)
                        @php
                            $isActive = $chapters->currentPage() == $i;
                        @endphp
                        <a href="{{ $chapters->url($i) }}" aria-current="{{ $isActive ? 'page' : 'false' }}"
                            class="w-12 h-12 rounded-full inline-flex items-center justify-center border-2 transition-colors duration-150 {{ $isActive ? 'bg-primary border-primary text-white shadow' : 'bg-transparent border-primary text-primary' }}">
                            {{ $i }}
                        </a>
                    @endfor
                @endif
            </div>
        </div>

        <!-- Comment Section -->
        @include("landing-page.partials.comment_section")
    </div>
@endsection
