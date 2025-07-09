<?php

namespace App\Http\Controllers\Public;

use App\Models\Property;
use App\Models\Development;
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
        $developments = Development::where('status', 'activo')
        ->with(['images' => function ($query) {
            $query->orderBy('order', 'asc')->take(1);
        }])
        ->paginate(20);

        $scripts = [''];
        return view('developments', compact('scripts', 'developments'));
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
        $search = $request->input('search');
        $transactionType = $request->input('transaction_type'); // 'venta', 'alquiler' o 'all'

        if (!in_array($transactionType, ['sell', 'rent', 'all'])) {
            return redirect()->back()->with('message', 'Tipo de propiedad inválido');
        }

        $query = Property::where('status', 'activo');

        if ($transactionType !== 'all') {
            $query->where('transaction_type', $transactionType);
        }

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%")
                ->orWhere('state', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('neighborhood', 'like', "%$search%");
            });
        }

        $properties = $query->paginate(20)->appends(request()->query());

        $propertiesCount = $properties->total();
        $message = ($propertiesCount == 0) ? 'No se encontraron propiedades con ese término de búsqueda.' : '';

        return view('property-search-results', compact('properties', 'propertiesCount', 'search', 'transactionType'))
            ->with('message', $message);
    }

    public function filterDevelopments(Request $request) {
        $search = $request->input('search');
    
        $query = Development::where('status', 'activo');
    
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%")
                ->orWhere('state', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('neighborhood', 'like', "%$search%");
            });
        }
    
        $developments = $query->paginate(20)->appends(request()->query());
    
        $developmentsCount = $developments->total();
        $message = ($developmentsCount == 0) ? 'No se encontraron emprendimientos con ese término de búsqueda.' : '';

    
        return view('development-search-results', compact('developments', 'developmentsCount', 'search'))
            ->with('message', $message);
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


