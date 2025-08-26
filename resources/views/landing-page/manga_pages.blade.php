@extends('landing-page.layouts.main')

@section('title', $manga->title . ' - Chapter ' . $chapter->chapter_number)

@section('content')
    <div class="container mx-auto px-4 my-12">
        <!-- Navigation Bar -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between mb-6 gap-3">
            <a href="{{ route('landing-page.manga_list') }}" class="btn btn-outline w-full md:w-auto">Kembali ke Halaman Awal</a>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <a href="{{ $previousChapter ? route('landing-page.manga_pages', ['manga_id' => $manga->id, 'chapter_number' => $previousChapter]) : '#' }}"
                        class="btn btn-outline w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" @if (!$previousChapter) disabled @endif aria-label="Chapter Sebelumnya">
                        <!-- Left chevron icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.707 3.707a1 1 0 010 1.414L4.414 9H15a1 1 0 110 2H4.414l3.293 3.293a1 1 0 11-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                    <a href="{{ route('landing-page.manga_pages', ['manga_id' => $manga->id, 'chapter_number' => $nextChapter]) }}"
                        class="btn btn-outline w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" @if (!$nextChapter) disabled @endif aria-label="Chapter Berikutnya">
                        <!-- Right chevron icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010-1.414L15.586 11H5a1 1 0 110-2h10.586l-3.293-3.293a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Berikutnya</span>
                    </a>
                    <button id="fullscreen-btn" class="btn btn-primary w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" aria-label="Mode Full Screen">
                        <!-- Fullscreen / expand icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3 9a1 1 0 011-1h3a1 1 0 110 2H5v2a1 1 0 11-2 0V9zM17 9a1 1 0 00-1-1h-3a1 1 0 100 2h2v2a1 1 0 102 0V9zM3 3a1 1 0 011-1h3a1 1 0 110 2H5v2a1 1 0 11-2 0V3zM17 17a1 1 0 01-1 1h-3a1 1 0 110-2h2v-2a1 1 0 112 0v3z"/>
                        </svg>
                        <span class="hidden sm:inline">Fullscreen</span>
                    </button>
            </div>
        </div>

        <!-- Manga Pages -->
        <div id="manga-reader" tabindex="0" class="bg-base-200 p-4 rounded-lg shadow-md overflow-y-auto border border-base-200/40 text-base-content">
            @foreach ($pages as $page)
                <img src="{{ asset('images/' . $page->image_url) }}" alt="Manga Page" class="w-full mb-4 rounded-lg border-4 border-base-200/40">
            @endforeach
        </div>
    </div>

    <script>
        // Full Screen Mode
        const fsBtn = document.getElementById('fullscreen-btn');
        const reader = document.getElementById('manga-reader');

        function enterFullscreen(el){
            if (el.requestFullscreen) return el.requestFullscreen();
            if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen(); // Safari
            if (el.msRequestFullscreen) return el.msRequestFullscreen(); // IE11
            return null;
        }

        fsBtn.addEventListener('click', () => enterFullscreen(reader));

        // When element becomes fullscreen, ensure it fills viewport and is scrollable
        function onFullScreenChange(){
            const isFs = document.fullscreenElement === reader || document.webkitFullscreenElement === reader || document.msFullscreenElement === reader;
            if(isFs){
                // focus so wheel/keyboard scrolls the reader
                reader.focus();
                // optional: hide body scroll to avoid double scroll on some browsers
                document.documentElement.style.overflow = 'hidden';
            } else {
                document.documentElement.style.overflow = '';
            }
        }

        document.addEventListener('fullscreenchange', onFullScreenChange);
        document.addEventListener('webkitfullscreenchange', onFullScreenChange);
        document.addEventListener('msfullscreenchange', onFullScreenChange);
    </script>

    <style>
        /* Ensure the reader takes full viewport height and remains scrollable while in fullscreen */
        #manga-reader:fullscreen, #manga-reader:-webkit-full-screen, #manga-reader:-ms-fullscreen {
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: auto !important;
            padding: 1rem !important;
            box-sizing: border-box !important;
        }
    </style>
@endsection
