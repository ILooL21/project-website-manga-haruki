<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        ]);

        // simpan gambar storage public
        if ($request->hasFile('cover_image')) {
            $filename = time() . '_' . $request->title . '_' . $request->file('cover_image')->getClientOriginalName();
            $uploaded = Cloudinary::uploadApi()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'manga_covers',
                'public_id' => pathinfo($filename, PATHINFO_FILENAME),
                'resource_type' => 'image'
            ]);
        }

        $path = $uploaded['public_id'];

        try {
            DB::beginTransaction();

            Manga::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'cover_image' => $path ?? null,
                'status' => $request->status ?? 'Ongoing',
                'author_id' => Auth::user()->id
            ])->genres()->sync($request->genre_ids);

            DB::commit();

            return redirect()->route('admin.mangas')->with([
                'status' => 'success',
                'message' => 'Manga berhasil ditambahkan.'
            ]);
        } catch (\Throwable $th) {
            // hapus gambar jika ada
            if (isset($uploaded)) {
                Cloudinary::destroy($uploaded['public_id']);
            }

            DB::rollBack();
            // Handle error

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

        // simpan gambar storage public
        if ($request->hasFile('cover_image')) {
            $filename = time() . '_' . $request->title . '_' . $request->file('cover_image')->getClientOriginalName();
            $uploaded = Cloudinary::uploadApi()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'manga_covers',
                'public_id' => pathinfo($filename, PATHINFO_FILENAME),
                'resource_type' => 'image'
            ]);
        }

        try {
            // mulai transaksi db
            DB::beginTransaction();

            // hapus gambar lama jika ada dan ada upload gambar baru
            if (isset($uploaded) && $manga->cover_image) {
                Cloudinary::uploadApi()->destroy($manga->cover_image);
            }

            $path = $uploaded['public_id'];

            $manga->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'cover_image' => $path ?? $manga->cover_image,
                'status' => $request->status ?? 'Ongoing',
            ]);

            $manga->genres()->sync($request->genre_ids);

            DB::commit();

            return redirect()->route('admin.mangas')->with([
                'status' => 'success',
                'message' => 'Manga berhasil diupdate.'
            ]);
        } catch (\Throwable $th) {
            // hapus gambar jika ada
            if (isset($uploaded)) {
                Cloudinary::uploadApi()->destroy([$uploaded['public_id']], ["invalidate" => true]);
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

        // hapus gambar jika ada
        if ($manga->cover_image) {
            Cloudinary::uploadApi()->destroy([$manga->cover_image], ["invalidate" => true]);
        }

        $manga->chapters->each(function ($chapter) {
            $chapter->pages->each(function ($page) {
                if ($page->image_url) {
                    Storage::disk('public')->delete($page->image_url);
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
}
