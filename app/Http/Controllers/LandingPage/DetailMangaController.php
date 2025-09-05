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

    $coverUrl = $this->resolveCover($manga->cover_image);

    // paginate chapters for the detail page (15 per page)
    $chapters = $manga->chapters()->orderBy('chapter_number', 'desc')->paginate(15);

    // determine the earliest (smallest) chapter number to start reading from
    $firstChapter = $manga->chapters()->orderBy('chapter_number', 'asc')->first();
    $firstChapterNumber = $firstChapter ? $firstChapter->chapter_number : 1;

        return view('landing-page.manga_detail', compact('manga', 'coverUrl', 'chapters', 'firstChapterNumber'));
    }

    /**
     * Resolve a cover image value to a usable URL.
     */
    private function resolveCover($img)
    {
        $fallback = asset('images/pomu.webp');
        if (!$img) return $fallback;

        if (Str::startsWith($img, ['http://', 'https://'])) return $img;
        if (Storage::disk('public')->exists($img)) return Storage::url($img);
        if (Storage::disk('public')->exists('images/' . $img)) return Storage::url('images/' . $img);
        if (file_exists(public_path($img))) return asset($img);
        if (file_exists(public_path('images/' . $img))) return asset('images/' . $img);
        if (file_exists(public_path('storage/' . $img))) return asset('storage/' . $img);

        // Try Cloudinary
        try {
            $cloud = app(\Cloudinary\Cloudinary::class);
            if ($cloud && is_string($img) && strlen($img) > 0) {
                $image = $cloud->image($img);
                if (method_exists($image, 'toUrl')) {
                    return (string) $image->toUrl();
                }
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }

        return $fallback;
    }
}
