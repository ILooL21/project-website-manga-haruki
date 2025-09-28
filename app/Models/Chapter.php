<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'title',
        'chapter_number',
        'release_date',
        'user_id',
    ];

    /**
     * Cast attributes to proper types.
     */
    protected $casts = [
        'release_date' => 'datetime',
    ];

    // Relasi: Satu Chapter dimiliki oleh satu Manga
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

    // Relasi: Satu Chapter diupload oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu Chapter bisa punya banyak Page
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    // Relasi ke semua komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Hanya mengambil komentar utama (akar dari tree)
    public function mainComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
