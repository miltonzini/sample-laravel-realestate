<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevelopmentImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'development_id',
        'image',
        'medium_image',
        'thumbnail_image',
        'img_alt',
        'img_class',
        'order',
    ];

    public function property()
    {
        return $this->belongsTo(Development::class);
    }
}
