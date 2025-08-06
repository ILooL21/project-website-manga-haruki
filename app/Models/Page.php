<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'page_number',
        'image_url',
    ];

    // Relasi: Satu Page dimiliki oleh satu Chapter
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
