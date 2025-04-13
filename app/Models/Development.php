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

}
