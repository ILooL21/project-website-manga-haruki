<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;

class ProjectListController extends Controller
{
    public function index() 
    {
        $q = request()->query('q');
        $genre = request()->query('genre');
        $sort = request()->query('sort', 'latest');

        $query = Manga::query()->with('genres')
            ->withCount('chapters');

        if ($q) {
            $query->where(function($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('author_name', 'like', "%{$q}%");
            });
        }

        if ($genre) {
            $query->whereHas('genres', function($b) use ($genre) {
                $b->where('slug', $genre)->orWhere('genres.id', $genre);
            });
        }

        switch ($sort) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'alphabet':
                $query->orderBy('title', 'asc');
                break;
            case 'chapter':
                $query->orderBy('chapters_count', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $mangas = $query->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('landing-page.project_list', compact('mangas', 'genres'));
    }
}
