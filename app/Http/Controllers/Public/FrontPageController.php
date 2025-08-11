<?php

namespace App\Http\Controllers\Public;

use App\Models\Property;
use App\Models\Development;
use App\Models\Lot;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FrontPageController extends Controller
{
    public function home() {
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

    public function lots() {
        $lots = Lot::where('status', 'activo')
        ->with(['images' => function ($query) {
            $query->orderBy('order', 'asc')->take(1);
        }])
        ->paginate(20);

        $scripts = [''];
        return view('lots', compact('scripts', 'lots'));
    }

    public function propertyDetails($slug) {

        $property = Property::where('slug', $slug)
        ->with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])
        ->first();

        if (!$property) {
            return redirect()->route('home')->with('error', 'La propiedad no existe.');
        }

        if ($property->status == 'oculto' || $property->status == 'eliminado' || $property->status == 'pausado') {
            return redirect()->route('home')->with('error', 'La propiedad no está disponible.');
        }

        // Calc property age
        if (!empty($property->year_built)) {
            $currentYear = Carbon::now()->year;
            $age = $currentYear - $property->year_built;
            switch ($age) {
                case 0:
                    $property->years_age = 'Nueva';
                    break;
                case 1:
                    $property->years_age = $age . ' año';
                    break;
                default:
                $property->years_age = $age . ' años'; 
            }
        }

        // content-aware layout
        $hasVideoBlock = !empty($property->video);
        $hasMap = !empty($property->real_address);
        

        $scripts = ['properties.js'];

        return view('property-details', compact('property', 'hasVideoBlock', 'hasMap', 'scripts'));
    }

    public function developmentDetails($slug) {

        $development = Development::where('slug', $slug)
        ->with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])
        ->first();

        if (!$development) {
            return redirect()->route('home')->with('error', 'El emprendimiento no existe.');
        }

        if ($development->status == 'oculto' || $development->status == 'eliminado' || $development->status == 'pausado') {
            return redirect()->route('home')->with('error', 'El emprendimiento no está disponible.');
        }

        // content-aware layout
        $hasVideoBlock = !empty($development->video);
        $hasMap = !empty($development->real_address);
        
        $scripts = ['developments.js'];

        return view('development-details', compact('development', 'hasVideoBlock', 'hasMap', 'scripts'));
    }

    public function lotDetails($slug) {

        $lot = Lot::where('slug', $slug)
        ->with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])
        ->first();

        if (!$lot) {
            return redirect()->route('home')->with('error', 'El Lote/Terreno no existe.');
        }

        if ($lot->status == 'oculto' || $lot->status == 'eliminado' || $lot->status == 'pausado') {
            return redirect()->route('home')->with('error', 'El Lote/Terreno no está disponible.');
        }

        // content-aware layout
        $hasVideoBlock = !empty($lot->video);
        $hasMap = !empty($lot->real_address);
        

        $scripts = ['lots.js'];

        return view('lot-details', compact('lot', 'hasVideoBlock', 'hasMap', 'scripts'));
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

    public function filterLots(Request $request) {
        $search = $request->input('search');
    
        $query = Lot::where('status', 'activo');
    
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
    
        $lots = $query->paginate(20)->appends(request()->query());
    
        $lotsCount = $lots->total();
        $message = ($lotsCount == 0) ? 'No se encontraron lotes/terrenos con ese término de búsqueda.' : '';

    
        return view('lot-search-results', compact('lots', 'lotsCount', 'search'))
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
        $posts = Post::where('status', 'activo')->orderBy('created_at', 'DESC')->paginate(20);
        $postsCount = $posts->total();

        $scripts = ['blog.js'];
        return view('blog.index', compact('scripts', 'posts', 'postsCount'));
    }

    public function post($slug) {
        $post = Post::where('slug', $slug)->first();

        if(!$post) {
            return redirect()->route('blog.index')->with('error', 'El post no existe.');
        }

        if($post->status != 'activo') {
            return redirect()->route('blog.index')->with('error', 'El post no está disponible.');
        }

        $recentPosts = Post::where('id', '!=', $post->id)
                        ->where('status', 'activo')
                        ->orderBy('created_at', 'DESC')
                        ->take(10)
                        ->get();

        $scripts = ['blog.js'];
        return view('blog.post', compact('scripts', 'post', 'recentPosts'));
    }
    
    public function filterPosts(Request $request) {
        $search = $request->input('search');
    
        $query = Post::where('status', 'activo');
    
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%");
            });
        }
    
        $posts = $query->paginate(20)->appends(request()->query());
    
        $postsCount = $posts->total();
        $message = ($postsCount == 0) ? 'No se encontraron posts con ese término de búsqueda.' : '';

        return view('blog.blog-search-results', compact('posts', 'postsCount', 'search'))
            ->with('message', $message);
    }

}


