<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Manga;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalMangas = Manga::count();
        $totalChapters = Chapter::count();
        $totalGenres = Genre::count();
        $totalUsers = User::count();
        $mangas = Manga::with('genres')->withCount('chapters')->get();

        return view('admin.dashboard.index', [
            'totalMangas' => $totalMangas,
            'totalChapters' => $totalChapters,
            'totalGenres' => $totalGenres,
            'totalUsers' => $totalUsers,
            'mangas' => $mangas,
        ]);
    }
}
