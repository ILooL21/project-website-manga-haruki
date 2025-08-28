<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCloudinary extends Model
{
    protected $fillable = ['name', 'description','price','image_url', 'image_public_id'];
}
