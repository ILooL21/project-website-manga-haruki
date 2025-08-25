<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
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
            // nama file timestamp_$request->title_nama file asli
            $filename = time() . '_' . $request->title . '_' . $request->file('cover_image')->getClientOriginalName();
            $path = $request->file('cover_image')->storeAs('manga_covers', $filename, 'public');
        }

        try {
            // mulai transaksi db
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
            if (isset($path)) {
                Storage::disk('public')->delete($path);
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
        $lastChapter = ceil($chapters->first()->chapter_number ?? 0);

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
            // nama file timestamp_$request->title_nama file asli
            $filename = time() . '_' . $request->title . '_' . $request->file('cover_image')->getClientOriginalName();
            $path = $request->file('cover_image')->storeAs('manga_covers', $filename, 'public');
        }

        try {
            // mulai transaksi db
            DB::beginTransaction();

            // hapus gambar lama jika ada dan ada upload gambar baru
            if (isset($path) && $manga->cover_image) {
                Storage::disk('public')->delete($manga->cover_image);
            }

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
            if (isset($path)) {
                Storage::disk('public')->delete($path);
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
            Storage::disk('public')->delete($manga->cover_image);
        }

        // hapus manga
        $manga->delete();

        return redirect()->route('admin.mangas')->with([
            'status' => 'success',
            'message' => 'Manga berhasil dihapus.'
        ]);
    }
}
