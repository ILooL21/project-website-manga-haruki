<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relasi: Satu Genre bisa dimiliki oleh banyak Manga (Many-to-Many)
    public function mangas()
    {
        return $this->belongsToMany(Manga::class, 'genre_manga');
    }
}
