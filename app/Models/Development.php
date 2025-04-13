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
        'property_type',
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
        'project_status', 

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
