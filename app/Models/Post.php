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

    public function getShortTitleAttribute()
    {
        if (strlen($this->title) >= 50) {
            return substr($this->title, 0, 47) . '...';
        }
        return $this->title;
    }

    public function getExcerptAttribute()
    {
        if ($this->short_description) {
            return $this->short_description;
        }
        
        return substr(strip_tags($this->body), 0, 150) . '...';
    }

    public function getAuthorFullNameAttribute()
    {
        return $this->author ? $this->author()->first()?->name . ' ' . $this->author()->first()?->surname : 'Desconocido';
    }
}