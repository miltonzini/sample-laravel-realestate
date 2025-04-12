<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
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

        'rooms',
        'bathrooms',
        'covered_area',
        'total_area',
        'width',
        'length',
        'orientation', // north, south, etc
        'position', // position within building
        'year_built',
        'storage_room',
        
        'services',
        'heating_type',
        'amenities',

        'price',
        'transaction_type',
        'hoa_fees',

        'video',
        'external_url',
        
        'private_notes',
        'seller_notes'
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }
    
}
