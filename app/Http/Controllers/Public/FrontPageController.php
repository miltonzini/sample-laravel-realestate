<?php

namespace App\Http\Controllers\Public;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FrontPageController extends Controller
{
    public function home() {
        // Log::channel('debug')->info('testing custom log');
        $scripts = [];
        return view('home', compact('scripts'));
    }

    public function properties() {
        $properties = Property::where('status', 'activo')
        ->with(['images' => function ($query) {
            $query->orderBy('order', 'asc')->take(1);
        }])
        ->paginate(20);

        
        $scripts = [];
        return view('properties', compact('scripts', 'properties'));
    }
    public function developments() {
        $scripts = [];
        return view('developments', compact('scripts'));
    }
    public function propertyDetails($slug) {        
        $property = (object)['title' => $slug]; // temp

        $scripts = [];
        return view('property-details', compact('scripts', 'property'));
    }
    public function developmentDetails($slug) {
        $development = (object)['title' => $slug]; // temp

        $scripts = [];
        return view('development-details', compact('scripts', 'development'));
    }

    public function filterProperties(Request $request) {
        // ... 
        $scripts = [];
        return view('property-search-results', compact('scripts'));
    }
    public function filterDevelopments(Request $request) {
        // ... 
        $scripts = [];
        return view('developments-search-results', compact('scripts'));
    }


    public function faq() {
        $scripts = [];
        return view('faq', compact('scripts'));
    }
    public function joinOurTeam() {
        $scripts = [];
        return view('join-our-team', compact('scripts'));
    }

    public function blog() {
        $scripts = ['blog.js'];
        return view('blog.index', compact('scripts'));
    }
    public function post($slug) {
        $post = (object)['title' => $slug]; // temp

        $scripts = ['blog.js'];
        return view('blog.post', compact('scripts','post'));
    }
    
    public function filterPosts(Request $request) {
        $scripts = ['blog.js'];
        return view('blog-search-results', compact('scripts'));
    }

}


