<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;

class MangaPagesController extends Controller
{
    // Ini buat contoh kedepannya
    public function show($manga_id, $chapter_number)
    {
        // Ambil manga berdasarkan ID
        $manga = Manga::findOrFail($manga_id);

        // Ambil chapter berdasarkan nomor chapter dan manga_id
        $chapter = Chapter::where('manga_id', $manga_id)
            ->where('chapter_number', $chapter_number)
            ->firstOrFail();

        // Ambil pages berdasarkan chapter_id
        $pages = $chapter->pages;

        // Navigasi chapter
        $previousChapter = Chapter::where('manga_id', $manga_id)
            ->where('chapter_number', '<', $chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();

        $nextChapter = Chapter::where('manga_id', $manga_id)
            ->where('chapter_number', '>', $chapter_number)
            ->orderBy('chapter_number', 'asc')
            ->first();

        return view('landing-page.manga_pages', [
            'manga' => $manga,
            'chapter' => $chapter,
            'pages' => $pages,
            'previousChapter' => $previousChapter ? $previousChapter->chapter_number : null,
            'nextChapter' => $nextChapter ? $nextChapter->chapter_number : null,
        ]);
    }

    public function indexDummy($chapter)
    {
        // Data dummy untuk halaman manga
        $pages = [
            '01.rawkuma.net.jpg',
            '02.rawkuma.net.jpg',
            '03.rawkuma.net.jpg',
        ];

        // Data dummy untuk navigasi chapter
        $previousChapter = $chapter > 1 ? $chapter - 1 : null;
        $nextChapter = $chapter < 10 ? $chapter + 1 : null;

        return view('landing-page.manga_pages_dummy', [
            'pages' => $pages,
            'previousChapter' => $previousChapter,
            'nextChapter' => $nextChapter,
        ]);
    }
}
