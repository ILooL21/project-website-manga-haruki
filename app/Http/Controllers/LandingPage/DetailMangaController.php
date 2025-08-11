<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailMangaController extends Controller
{
    /**
     * Display the detail page for a manga.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        // Logic to retrieve manga details based on request parameters
        // For example, you might fetch manga data from a model or service

        return view('landing-page.manga_detail', [
            'mangaId' => $id,
            // Add other necessary data to the view, such as manga details
        ]);
    }
}
