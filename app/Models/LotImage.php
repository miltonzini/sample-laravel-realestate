<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id',
        'image',
        'medium_image',
        'thumbnail_image',
        'img_alt',
        'img_class',
        'order',
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

}
