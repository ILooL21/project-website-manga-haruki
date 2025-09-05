<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Iklan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HalamanAwalController extends Controller
{
    public function index()
    {
        // load recent mangas with genres and chapters and map to a lightweight array for the hero
        $mangas = Manga::with(['genres', 'chapters'])->orderBy('created_at', 'desc')->take(6)->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => $m->title,
                'desc' => Str::limit($m->description ?? '', 260),
                'genres' => $m->genres->pluck('name'),
                // cover_image may be Cloudinary public_id, storage path or absolute URL
                'img' => $this->resolveCover($m->cover_image),
                'url' => route('landing-page.detail_manga', ['id' => $m->id]),
                'chapter' => $m->chapters->max('chapter_number') ?? null,
            ];
        });

        // New releases (most recently created)
        $newReleases = Manga::orderBy('created_at', 'desc')->take(6)->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => $m->title,
                'cover' => $this->resolveCover($m->cover_image),
                'url' => route('landing-page.detail_manga', ['id' => $m->id]),
                'created_human' => optional($m->created_at)->diffForHumans(),
            ];
        });

        // Random spotlight (small curated/random set)
        $spotlight = Manga::inRandomOrder()->take(4)->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => $m->title,
                'cover' => $this->resolveCover($m->cover_image),
                'url' => route('landing-page.detail_manga', ['id' => $m->id]),
            ];
        });

        $iklanBanner = Iklan::orderBy('created_at', 'desc')->first();

        return view('landing-page.halaman_awal', compact('mangas', 'newReleases', 'spotlight', 'iklanBanner'));
    }

    /**
     * Return an HTML fragment of the projects grid ordered by latest chapter (for realtime updates).
     */
    public function projectsFragment()
    {
        $projects = $this->latestProjects();

        return view('landing-page.partials.projects_grid', compact('projects'));
    }

    /**
     * Get latest projects ordered by most recent chapter added.
     * Returns a collection of arrays suitable for views.
     */
    public function latestProjects($limit = 8)
    {
        // load mangas with the latest chapter information
        $mangas = Manga::with(['chapters' => function ($q) {
            $q->orderBy('chapter_number', 'desc');
        }])->get();

        $mapped = $mangas->map(function ($m) {
            // take top 3 chapters ordered by chapter_number desc
            $chapters = $m->chapters->sortByDesc('chapter_number')->take(3)->map(function ($c) use ($m) {
                return [
                    'chapter_number' => $c->chapter_number,
                    'title' => $c->title,
                    'url' => route('landing-page.manga_pages', ['manga_id' => $m->id, 'chapter_number' => $c->chapter_number]),
                    'created_at' => $c->created_at,
                ];
            })->values();

            $latestChapter = $chapters->first();

            return [
                'id' => $m->id,
                'title' => $m->title,
                'cover' => $this->resolveCover($m->cover_image),
                'latest_chapter' => $latestChapter ? $latestChapter['chapter_number'] : null,
                'latest_at' => $latestChapter ? $latestChapter['created_at'] : $m->created_at,
                'url' => route('landing-page.detail_manga', ['id' => $m->id]),
                'chapters' => $chapters,
            ];
        });

        // order by latest_at desc and take limit
        return $mapped->sortByDesc('latest_at')->take($limit)->values();
    }

    /**
     * Resolve a cover image value to a usable URL.
     */
    private function resolveCover($img)
    {
        // default
        $fallback = asset('images/pomu.webp');
        if (!$img) return $fallback;

        if (Str::startsWith($img, ['http://', 'https://'])) return $img;
        if (Storage::disk('public')->exists($img)) return Storage::url($img);
        if (Storage::disk('public')->exists('images/' . $img)) return Storage::url('images/' . $img);
        if (file_exists(public_path($img))) return asset($img);
        if (file_exists(public_path('images/' . $img))) return asset('images/' . $img);
        if (file_exists(public_path('storage/' . $img))) return asset('storage/' . $img);
        // Try Cloudinary (public id stored in DB)
        try {
            $cloud = app(\Cloudinary\Cloudinary::class);
            if ($cloud && is_string($img) && strlen($img) > 0) {
                // image(...) returns an object with toUrl()
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
