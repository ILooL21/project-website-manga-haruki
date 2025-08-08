<?php

namespace App\Http\Controllers;

use App\Models\Genre;
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
        return view('admin.dashboard.index', [
            'totalUsers' => User::count(),
            'totalGenres' => Genre::count(),
            // 'totalMangas' => Manga::count(),
            'totalMangas' => 0,
        ]);
    }
}
