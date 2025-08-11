@extends('landing-page.layouts.main')

@section('content')
<div class="container mx-auto px-4 my-12">
    <!-- Navigation Bar -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('landing-page.manga_list') }}" class="btn btn-outline">Kembali ke Halaman Awal</a>
        <div class="flex items-center gap-4">
            <a href="#" class="btn btn-outline" @if(!$previousChapter) disabled @endif>
                Chapter Sebelumnya
            </a>
            <a href="#" class="btn btn-outline" @if(!$nextChapter) disabled @endif>
                Chapter Berikutnya
            </a>
            <button id="fullscreen-btn" class="btn btn-primary">Mode Full Screen</button>
        </div>
    </div>

    <!-- Manga Pages -->
    <div id="manga-reader" class="bg-[#1a1625] p-4 rounded-lg shadow-md">
        @foreach ($pages as $page)
            <img src="{{ asset('images/' . $page) }}" alt="Manga Page" class="w-full mb-4 rounded-lg">
        @endforeach
    </div>
</div>

<script>
    // Full Screen Mode
    document.getElementById('fullscreen-btn').addEventListener('click', () => {
        const reader = document.getElementById('manga-reader');
        if (reader.requestFullscreen) {
            reader.requestFullscreen();
        } else if (reader.webkitRequestFullscreen) { // Safari
            reader.webkitRequestFullscreen();
        } else if (reader.msRequestFullscreen) { // IE11
            reader.msRequestFullscreen();
        }
    });
</script>
@endsection