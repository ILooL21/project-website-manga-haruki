<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('mangas')->orderBy('created_at', 'desc')->get();
        return view('admin.genres.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:genres,slug'
        ]);

        Genre::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.genres')->with([
            'status' => 'success',
            'message' => 'Genre created successfully.'
        ]);
    }

    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('admin.genres.edit', [
            'genre' => $genre
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255'
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($request->only('name', 'slug'));

        return redirect()->route('admin.genres')->with([
            'status' => 'success',
            'message' => 'Genre updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return redirect()->route('admin.genres')->with([
            'status' => 'success',
            'message' => 'Genre deleted successfully.'
        ]);
    }
}
