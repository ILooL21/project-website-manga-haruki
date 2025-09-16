@extends('landing-page.layouts.main')

@section('title', $manga->title . ' - Chapter ' . $chapter->chapter_number)

@section('content')
    <div class="container mx-auto px-4 my-12">
        <!-- Navigation Bar -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between mb-6 gap-3">
            <a href="{{ route('landing-page.index') }}" class="btn btn-outline w-full md:w-auto">Kembali ke Halaman Awal</a>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                    <a href="{{ $previousChapter ? route('landing-page.manga_pages', ['slug' => $manga->slug, 'chapter_number' => $previousChapter]) : '#' }}"
                        class="btn btn-outline w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" @if (!$previousChapter) disabled @endif aria-label="Chapter Sebelumnya">
                        <!-- Left chevron icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.707 3.707a1 1 0 010 1.414L4.414 9H15a1 1 0 110 2H4.414l3.293 3.293a1 1 0 11-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </a>
                    <a href="{{ $nextChapter ? route('landing-page.manga_pages', ['slug' => $manga->slug, 'chapter_number' => $nextChapter]) : '#' }}"
                        class="btn btn-outline w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" @if (!$nextChapter) disabled @endif aria-label="Chapter Berikutnya">
                        <!-- Right chevron icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010-1.414L15.586 11H5a1 1 0 110-2h10.586l-3.293-3.293a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="hidden sm:inline">Berikutnya</span>
                    </a>
                    <button id="fullscreen-btn" class="btn btn-primary w-full sm:w-auto flex items-center justify-center sm:justify-start gap-2" aria-label="Mode Full Screen">
                        <!-- Enter fullscreen icon -->
                        <svg id="fs-enter" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4h4M20 8V4h-4M4 16v4h4M20 16v4h-4" />
                        </svg>
                        <!-- Exit fullscreen icon (hidden by default) -->
                        <svg id="fs-exit" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 4h4v4M16 20h-4v-4M20 8v4h-4M4 16v-4h4" />
                        </svg>
                        <span class="hidden sm:inline">Fullscreen</span>
                    </button>
            </div>
        </div>

        <!-- Manga Pages -->
        <div id="manga-reader" tabindex="0" class="bg-base-200 p-4 rounded-lg shadow-md overflow-y-auto border border-base-200/40 text-base-content">
            @foreach ($pages as $page)
                <img src="{{ asset('storage/' . $page->image_url) }}" alt="Manga Page" class="w-full mb-4 rounded-lg border-4 border-base-200/40">
            @endforeach
        </div>
    </div>

    <script>
        // Full Screen Mode
        const fsBtn = document.getElementById('fullscreen-btn');
        const reader = document.getElementById('manga-reader');
        const fsEnter = document.getElementById('fs-enter');
        const fsExit = document.getElementById('fs-exit');

        function enterFullscreen(el){
            if (el.requestFullscreen) return el.requestFullscreen();
            if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen(); // Safari
            if (el.msRequestFullscreen) return el.msRequestFullscreen(); // IE11
            return null;
        }
        function exitFullscreen(){
            if (document.exitFullscreen) return document.exitFullscreen();
            if (document.webkitExitFullscreen) return document.webkitExitFullscreen();
            if (document.msExitFullscreen) return document.msExitFullscreen();
            return null;
        }

        fsBtn.addEventListener('click', () => {
            // toggle fullscreen on reader
            if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                enterFullscreen(reader).catch(()=>{});
            } else {
                exitFullscreen().catch(()=>{});
            }
        });

        // Update icon state on fullscreen change
        function onFullScreenChange(){
            const isFs = document.fullscreenElement === reader || document.webkitFullscreenElement === reader || document.msFullscreenElement === reader;
            if(isFs){
                reader.focus();
                document.documentElement.style.overflow = 'hidden';
                fsEnter.classList.add('hidden');
                fsExit.classList.remove('hidden');
            } else {
                document.documentElement.style.overflow = '';
                fsEnter.classList.remove('hidden');
                fsExit.classList.add('hidden');
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
