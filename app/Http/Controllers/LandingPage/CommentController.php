<?php

namespace App\Http\Controllers\LandingPage;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:2500',
            'chapter_id' => 'required|exists:chapters,id', // Validasi ke tabel chapters
        ]);

        Comment::create([
            'user_id' => Auth::user()->id,
            'chapter_id' => $request->chapter_id,
            'body' => $request->body,
            'parent_id' => $request->parent_id ?? null,
        ]);

        return back()->with([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan!'
        ]);
    }
}
