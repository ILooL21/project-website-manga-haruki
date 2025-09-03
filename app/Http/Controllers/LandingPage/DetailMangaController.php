<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DetailMangaController extends Controller
{
    /**
     * Display the detail page for a manga.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        // load manga with relations
        $manga = Manga::with(['genres', 'chapters', 'author'])->findOrFail($id);

        // compute cover URL robustly (supports full URLs, storage/public, public/images, or public assets)
        $img = $manga->cover_image;
        $coverUrl = asset('images/pomu.webp'); // default fallback

        if ($img) {
            // absolute URL stored in DB
            if (Str::startsWith($img, ['http://', 'https://'])) {
                $coverUrl = $img;
            }

            // storage disk (public) - e.g. 'mascot.jpg' or 'covers/mascot.jpg'
            elseif (Storage::disk('public')->exists($img)) {
                $coverUrl = Storage::url($img);
            }

            // common variants: stored as filename in 'images/' folder on public
            elseif (Storage::disk('public')->exists('images/' . $img)) {
                $coverUrl = Storage::url('images/' . $img);
            }

            // public path exact
            elseif (file_exists(public_path($img))) {
                $coverUrl = asset($img);
            }

            // public/images/filename.jpg
            elseif (file_exists(public_path('images/' . $img))) {
                $coverUrl = asset('images/' . $img);
            }

            // storage link under public/storage/...
            elseif (file_exists(public_path('storage/' . $img))) {
                $coverUrl = asset('storage/' . $img);
            }
        }

    // paginate chapters for the detail page (15 per page)
    $chapters = $manga->chapters()->orderBy('chapter_number', 'desc')->paginate(15);

    // determine the earliest (smallest) chapter number to start reading from
    $firstChapter = $manga->chapters()->orderBy('chapter_number', 'asc')->first();
    $firstChapterNumber = $firstChapter ? $firstChapter->chapter_number : 1;

        return view('landing-page.manga_detail', compact('manga', 'coverUrl', 'chapters', 'firstChapterNumber'));
    }
}
