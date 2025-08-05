<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\User; 
use App\Models\Lot;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LotImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;
use stdClass;

class LotController extends Controller
{
    public function index() {
        $lots = Lot::orderBy('id')->paginate(20);
        $lotsCount = $lots->total();
        $scripts = ['lot.js'];
        return view('admin.lots.index', compact('scripts', 'lots', 'lotsCount'));
    }


    public function create() {
        $scripts = ['lot.js'];
        return view('admin.lots.create', compact('scripts'));
    }

    public function store(Request $request){
        // ...
    }

    public function edit($id) {
        // $lot = Lot::with(['images' => function($query) {
            //     $query->orderBy('order', 'asc');
        // }])->find($id);
        // temp
        $tempId = request()->segment(count(request()->segments()));
        $lot = new stdClass;
        $lot->id = $tempId;

        if (!$lot) {
            return redirect()->route('home')->with('error', 'El Lote/Terreno no existe.');
        }

        $scripts = ['lot.js'];
        return view('admin.lots.edit', compact('scripts', 'lot'));
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        // ...
    }
}
