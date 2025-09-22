<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MangaController extends Controller
{
    public function index()
    {
        $mangas = Manga::with('genres')->withCount('chapters')->orderBy('id', 'desc')->get();
        $allGenres = Genre::orderBy('name')->get();

        return view('admin.mangas.index', [
            'mangas' => $mangas,
            'allGenres' => $allGenres,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Ongoing,Completed,Hiatus',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
            'author_name' => 'nullable|string|max:255',
        ]);

        // upload cover to Cloudinary (store secure url + public_id)
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'manga_covers',
                'resource_type' => 'image'
            ]);
            $coverUrl = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
            $coverPublicId = $uploaded['public_id'] ?? null;
        }

        try {
            DB::beginTransaction();

            $slug = $this->generateUniqueSlug($request->title);

            Manga::create([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'cover_image' => $coverUrl ?? null,
                'image_public_id' => $coverPublicId ?? null,
                'status' => $request->status ?? 'Ongoing',
                'author_name' => $request->author_name ?? null
            ])->genres()->sync($request->genre_ids);

            DB::commit();

            return redirect()->route('admin.mangas')->with([
                'status' => 'success',
                'message' => 'Manga berhasil ditambahkan.'
            ]);
        } catch (\Throwable $th) {
            // Log full error and context for debugging
            try {
                Log::error('Failed to store Manga', [
                    'exception_message' => $th->getMessage(),
                    'exception_trace' => $th->getTraceAsString(),
                    'request' => $request->only(['title', 'description', 'status', 'author_name', 'genre_ids']),
                    'uploaded' => isset($uploaded) ? $uploaded : null,
                ]);
            } catch (\Throwable $logEx) {
                // if logging fails, still continue to cleanup and rollback
                report($logEx);
            }

            // hapus gambar jika ada upload baru saat error
            if (isset($uploaded) && ! empty($uploaded['public_id'])) {
                try {
                    Cloudinary::uploadApi()->destroy($uploaded['public_id'], ["invalidate" => true]);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            DB::rollBack();

            return redirect()->route('admin.mangas')->with([
                'status' => 'failed',
                'message' => 'gagal menambahkan manga.'
            ]);
        }
    }

    public function show($id)
    {
        $mangaData = Manga::findOrFail($id)->load('genres');
        $chapters = $mangaData->chapters()->orderBy('chapter_number', 'desc')->get();

        $chapterNumber = $chapters->first()->chapter_number ?? 0;
        $lastChapter = fmod($chapterNumber, 1) !== 0.0 ? ceil($chapterNumber) : $chapterNumber + 1;

        return view('admin.mangas.show', [
            'mangaData' => $mangaData,
            'chapters' => $chapters,
            'lastChapter' => $lastChapter
        ]);
    }

    public function edit($id)
    {
        $mangaData = Manga::findOrFail($id)->load('genres:id');
        $allGenres = Genre::orderBy('name')->get();

        return view('admin.mangas.edit', [
            'mangaData' => $mangaData,
            'allGenres' => $allGenres
        ]);
    }

    public function update(Request $request, $id)
    {
        $manga = Manga::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Ongoing,Completed,Hiatus',
            'genre_ids' => 'required|array',
            'genre_ids.*' => 'exists:genres,id',
        ]);

        // upload new cover if provided
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'manga_covers',
                'resource_type' => 'image'
            ]);
            $newCoverUrl = $uploaded['secure_url'] ?? ($uploaded['url'] ?? null);
            $newCoverPublicId = $uploaded['public_id'] ?? null;
        }

        try {
            // mulai transaksi db
            DB::beginTransaction();

            // if new cover uploaded, remove old cloudinary asset by stored public id
            if (isset($newCoverPublicId) && ! empty($manga->image_public_id)) {
                try {
                    Cloudinary::uploadApi()->destroy($manga->image_public_id, ["invalidate" => true]);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            $slug = $this->generateUniqueSlug($request->title, $manga->id);

            $manga->update([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'cover_image' => $newCoverUrl ?? $manga->cover_image,
                'image_public_id' => $newCoverPublicId ?? $manga->image_public_id,
                'author_name' => $request->author_name ?? $manga->author_name ?? Auth::user()->name ?? null,
                'status' => $request->status ?? 'Ongoing',
            ]);

            $manga->genres()->sync($request->genre_ids);

            DB::commit();

            return redirect()->route('admin.mangas')->with([
                'status' => 'success',
                'message' => 'Manga berhasil diupdate.'
            ]);
        } catch (\Throwable $th) {
            // cleanup newly uploaded asset on failure
            if (isset($newCoverPublicId) && ! empty($newCoverPublicId)) {
                try {
                    Cloudinary::uploadApi()->destroy($newCoverPublicId, ["invalidate" => true]);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            DB::rollBack();
            // Handle error

            return redirect()->route('admin.mangas')->with([
                'status' => 'failed',
                'message' => 'gagal mengupdate manga.'
            ]);
        }
    }

    public function destroy($id)
    {
        $manga = Manga::findOrFail($id);

        // hapus remote cloudinary asset jika ada public id tersimpan
        if (! empty($manga->image_public_id)) {
            try {
                Cloudinary::uploadApi()->destroy($manga->image_public_id, ["invalidate" => true]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $manga->chapters->each(function ($chapter) {
            $chapter->pages->each(function ($page) {
                if ($page->image_public_id) {
                    Cloudinary::uploadApi()->destroy($page->image_public_id, ["invalidate" => true]);
                }
                $page->delete();
            });
        });

        // hapus manga
        $manga->delete();

        return redirect()->route('admin.mangas')->with([
            'status' => 'success',
            'message' => 'Manga berhasil dihapus.'
        ]);
    }

    /**
     * Generate a unique slug for the manga based on title.
     * If $excludeId is provided, exclude that record when checking uniqueness (for updates).
     */
    private function generateUniqueSlug(string $title, $excludeId = null): string
    {
        $base = Str::slug($title ?: 'manga');
        $slug = $base;
        $i = 1;
        while (Manga::where('slug', $slug)->when($excludeId, function ($q) use ($excludeId) {
            return $q->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }
}
