<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'image_public_id',
        'status',
        'author_name',
        'slug',
    ];


    // Relasi: Satu Manga bisa punya banyak Chapter
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // Relasi: Satu Manga bisa punya banyak Genre (Many-to-Many)
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_manga');
    }
}
