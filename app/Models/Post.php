<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'body',
        'button_text',
        'button_url',
        'author',
        'status'
    ];

    public function images()
    {
        return $this->hasMany(PostImage::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author');
    }

}