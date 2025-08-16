<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'project_details',
        'development_type',
        'status', 
        'featured',

        'public_address',
        'real_address',
        'country',
        'state',
        'city',
        'neighborhood',

        'estimated_delivery_date', 
        'project_status', 
        'developer',      

        'price_range',

        'services',
        'amenities',

        'video',
        'external_url',

        'private_notes',
        'seller_notes' 
    ];

    public function images()
    {
        return $this->hasMany(DevelopmentImage::class)->orderBy('order');
    }

    /**
     * Relación polimórfica con archivos
     */
    public function files()
    {
        return $this->morphMany(File::class, 'parent')->orderBy('order');
    }

    /**
     * Archivos por tipo
     */
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

    /**
     * Solo archivos públicos
     */
    public function publicFiles()
    {
        return $this->morphMany(File::class, 'parent')
                    ->where('is_public', true)
                    ->orderBy('order');
    }


    // Enable $development->short_location on read (accessor)
    public function getShortLocationAttribute()
    {
        if (in_array($this->state, ['Capital Federal', 'CABA', 'Ciudad Autónoma de Buenos Aires'])) {
            return $this->neighborhood . ' (CABA)';
        }
        return $this->city . ', ' . $this->state;
    }

    // Enable $development->short_title on read (accessor)
    public function getShortTitleAttribute() {
        if (strlen($this->title) >= 50) {
            return substr($this->title, 0, 47) . '...';
        }
        return $this->title;
    }    

}
