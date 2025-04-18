<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'image',
        'thumbnail_image',
        'img_alt'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}