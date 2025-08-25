<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chapter_number' => ['required', 'numeric', 'min:0'],
            'release_date' => ['nullable', 'date'],
        ]);

        $data['manga_id'] = $id;
        $data['user_id'] = Auth::user()->id;

        Chapter::create($data);

        return redirect()
            ->route('admin.mangas.show', $id)
            ->with([
                'status' => 'success',
                'message' => 'Chapter berhasil ditambahkan.',
            ]);
    }

    public function edit($id)
    {
        $chapter = Chapter::findOrFail($id);
        return view('admin.chapters.edit', compact('chapter'));
    }

    public function update(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chapter_number' => ['required', 'numeric', 'min:0'],
            'release_date' => ['nullable', 'date'],
        ]);

        $chapter->update($data);

        return redirect()
            ->route('admin.mangas.show', $chapter->manga_id)
            ->with([
                'status' => 'success',
                'message' => 'Chapter berhasil diperbarui.',
            ]);
    }

    public function destroy($id)
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();

        return redirect()
            ->route('admin.mangas.show', $chapter->manga_id)
            ->with([
                'status' => 'success',
                'message' => 'Chapter berhasil dihapus.',
            ]);
    }

    public function viewPages($id)
    {
        $chapter = Chapter::findOrFail($id);
        $pages = $chapter->pages()->orderBy('page_number')->get();

        return view('admin.pages.index', [
            'chapter' => $chapter,
            'pages' => $pages,
        ]);
    }

    public function reorderPages(Request $request, $id)
    {
        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*.id' => ['required', 'integer', 'distinct'],
            'order.*.position' => ['required', 'integer', 'min:1'],
        ]);

        $chapter = Chapter::findOrFail($id);

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

        return response()->json(['message' => 'Order updated', 'status' => 'success']);
    }
}
