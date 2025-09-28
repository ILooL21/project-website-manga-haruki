<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chapter_id',
        'parent_id',
        'body'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Diubah: Relasi langsung ke Chapter
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies')->orderBy('created_at', 'desc');
    }
}
