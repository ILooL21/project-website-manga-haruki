<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Page;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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

        try {
            $data['manga_id'] = $id;
            $data['user_id'] = Auth::user()->id;

            Chapter::create($data);

            return redirect()
                ->route('admin.mangas.show', $id)
                ->with([
                    'status' => 'success',
                    'message' => 'Chapter berhasil ditambahkan.',
                ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'status' => 'failed',
                    'message' => 'Gagal menambahkan chapter.',
                    'server_error' => $th->getMessage(),
                ]);
        }
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

        try {
            $chapter->update($data);

            return redirect()
                ->route('admin.mangas.show', $chapter->manga_id)
                ->with([
                    'status' => 'success',
                    'message' => 'Chapter berhasil diperbarui.',
                ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'status' => 'failed',
                    'message' => 'Gagal memperbarui chapter.',
                    'server_error' => $th->getMessage(),
                ]);
        }
    }

    public function destroy($id)
    {
        try {
            $chapter = Chapter::findOrFail($id);

            $chapter->pages()->each(function ($page) {
                if ($page->image_public_id) {
                    Cloudinary::uploadApi()->destroy($page->image_public_id, ['invalidate' => true]);
                }
            });

            $chapter->pages()->delete();

            $chapter->delete();

            return redirect()
                ->route('admin.mangas.show', $chapter->manga_id)
                ->with([
                    'status' => 'success',
                    'message' => 'Chapter berhasil dihapus.',
                ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->route('admin.mangas.show', isset($chapter) ? $chapter->manga_id : null)
                ->with([
                    'status' => 'failed',
                    'message' => 'Gagal menghapus chapter.',
                    'server_error' => $th->getMessage(),
                ]);
        }
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
            if (!in_array($rid, $validIds, true)) {
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

            $chapter
                ->pages()
                ->get()
                ->each(function ($page) use ($map) {
                    if (isset($map[$page->id])) {
                        $page->update(['page_number' => $map[$page->id]['position']]);
                    }
                });
        });

        return response()->json(['message' => 'Order updated', 'status' => 'success']);
    }

    public function storePages(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $files = $request->file('images');
            if ($files) {
                DB::transaction(function () use ($files, $chapter) {
                    $maxPageNumber = $chapter->pages()->max('page_number') ?? 0;

                    foreach ($files as $index => $file) {
                        $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                            'folder' => 'pages',
                            'resource_type' => 'image',
                        ]);
                        $pagesUrl = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
                        $coverPublicId = $uploaded['public_id'] ?? null;

                        Page::create([
                            'chapter_id' => $chapter->id,
                            'page_number' => $maxPageNumber + $index + 1,
                            'image_url' => $pagesUrl,
                            'image_public_id' => $coverPublicId,
                        ]);
                    }
                });
            }

            return redirect()
                ->route('admin.chapters.pages', $chapter->id)
                ->with([
                    'status' => 'success',
                    'message' => 'Pages berhasil ditambahkan.',
                ]);
        } catch (\Throwable $th) {
            report($th);
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'status' => 'failed',
                    'message' => 'Gagal menambahkan pages.',
                    'server_error' => $th->getMessage(),
                ]);
        }
    }

    public function editPages($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function updatePages(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $data = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // simpan gambar
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'pages',
                'resource_type' => 'image',
            ]);
            $data['image_url'] = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
            $data['image_public_id'] = $uploaded['public_id'] ?? null;
        }

        try {
            DB::beginTransaction();

            // hapus gambar lama
            if ($request->hasFile('image') && $page->image_public_id) {
                Cloudinary::uploadApi()->destroy($page->image_public_id, ['invalidate' => true]);
            }

            $page->update($data);

            DB::commit();

            return redirect()
                ->route('admin.chapters.pages', $page->chapter_id)
                ->with([
                    'status' => 'success',
                    'message' => 'Page berhasil diperbarui.',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.chapters.pages', $page->chapter_id)
                ->with([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui page: ' . $e->getMessage(),
                ]);
        }
    }

    public function deletePages($id)
    {
        $page = Page::findOrFail($id);

        // hapus gambar
        if ($page->image_public_id) {
            Cloudinary::uploadApi()->destroy($page->image_public_id, ['invalidate' => true]);
        }

        $page->delete();

        return redirect()
            ->route('admin.chapters.pages', $page->chapter_id)
            ->with([
                'status' => 'success',
                'message' => 'Page berhasil dihapus.',
            ]);
    }
}
