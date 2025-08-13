<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TestDashboardController extends Controller
{
    public function index()
    {
        $totalMangas = Manga::count();
        $totalChapters = Chapter::count();
        $totalGenres = Genre::count();
        $totalUsers = User::count();
        $mangas = Manga::with('genres')->withCount('chapters')->get();

        return view('dashboard-untuk-ilul.index', [
            'totalMangas' => $totalMangas,
            'totalChapters' => $totalChapters,
            'totalGenres' => $totalGenres,
            'totalUsers' => $totalUsers,
            'mangas' => $mangas,
        ]);
    }

    public function indexManga()
    {
        $mangas = Manga::with('genres')->withCount('chapters')->get();
        $allGenres = Genre::orderBy('name')->get();

        return view('dashboard-untuk-ilul.manga', [
            'mangas' => $mangas,
            'allGenres' => $allGenres,
        ]);
    }

    public function viewChapters(Manga $manga)
    {
        $chapters = $manga->chapters()->get();

        return view('dashboard-untuk-ilul.chapters', [
            'manga' => $manga,
            'chapters' => $chapters,
        ]);
    }

    public function storeChapter(Request $request, Manga $manga)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'chapter_number' => ['required','numeric','min:0'],
            'release_date' => ['nullable','date'],
        ]);

        $data['manga_id'] = $manga->id;
        $data['user_id'] = auth()->id();

        Chapter::create($data);

        return redirect()
            ->route('manga.chapters', $manga->id)
            ->with('success', 'Chapter berhasil ditambahkan.');
    }

    public function viewPages(Chapter $chapter)
    {
        $pages = $chapter->pages()->orderBy('page_number')->get();

        return view('dashboard-untuk-ilul.pages', [
            'chapter' => $chapter,
            'pages' => $pages,
        ]);
    }

    public function reorderPages(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'order' => ['required','array','min:1'],
            'order.*.id' => ['required','integer','distinct'],
            'order.*.position' => ['required','integer','min:1'],
        ]);

        // Fetch the set of page IDs for this chapter to assert ownership (cast to int)
        $validIds = $chapter->pages()->pluck('id')->map(fn($id) => (int) $id)->toArray();
        foreach ($validated['order'] as $row) {
            $rid = (int) $row['id'];
            if (! in_array($rid, $validIds, true)) {
                return response()->json(['message' => 'Invalid page id for this chapter.'], 422);
            }
        }

        // Apply updates in a transaction, normalize to consecutive integers starting at 1
        DB::transaction(function () use ($validated, $chapter) {
            // create a map id => position
            $map = collect($validated['order'])
                ->sortBy('position')
                ->values()
                ->map(function ($row, $idx) {
                    // Ensure consecutive numbering
                    $row['position'] = $idx + 1;
                    $row['id'] = (int) $row['id'];
                    return $row;
                })
                ->keyBy('id');

            $chapter->pages()->get()->each(function ($page) use ($map) {
                if (isset($map[$page->id])) {
                    $page->update(['page_number' => $map[$page->id]['position']]);
                }
            });
        });

        return response()->json(['message' => 'Order updated']);
    }

    public function viewGenres()
    {
        $genres = Genre::withCount('mangas')->get();

        return view('dashboard-untuk-ilul.genres', [
            'genres' => $genres,
        ]);
    }
}
