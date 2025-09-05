<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Iklan extends Model
{
    use HasFactory;
    
    protected $fillable = ['section', 'image_path', 'image_public_id', 'link'];
}
