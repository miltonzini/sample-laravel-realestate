<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'featured',

        'public_address',
        'real_address',
        'country',
        'state',
        'city',
        'neighborhood',

        'frontage',
        'depth',
        'total_area',
        
        'services',

        'price',
        'transaction_type',

        'video',
        'external_url',

        'is_in_gated_community',
        
        'private_notes',
        'seller_notes'
    ];

    public function images()
    {
        return $this->hasMany(LotImage::class)->orderBy('order');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'parent')->orderBy('order');
    }

    public function pdfs()
    {
        return $this->morphMany(File::class, 'parent')
                    ->where('file_type', 'pdf')
                    ->orderBy('order');
    }

    public function imageFiles()
    {
        return $this->morphMany(File::class, 'parent')
                    ->whereIn('file_type', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])
                    ->orderBy('order');
    }

    public function documents()
    {
        return $this->morphMany(File::class, 'parent')
                    ->whereIn('file_type', ['pdf', 'doc', 'docx', 'txt', 'rtf'])
                    ->orderBy('order');
    }

    public function publicFiles()
    {
        return $this->morphMany(File::class, 'parent')
                    ->where('is_public', true)
                    ->orderBy('order');
    }

    
    // Enable $lot->short_location on read (accessor)
    public function getShortLocationAttribute()
    {
        if (in_array($this->state, ['Capital Federal', 'CABA', 'Ciudad Autónoma de Buenos Aires'])) {
            return $this->neighborhood . ' (CABA)';
        }
        return $this->city . ', ' . $this->state;
    }

    // Enable $lot->short_title on read (accessor)
    public function getShortTitleAttribute() {
        if (strlen($this->title) >= 50) {
            return substr($this->title, 0, 47) . '...';
        }
        return $this->title;
    }
    
}
