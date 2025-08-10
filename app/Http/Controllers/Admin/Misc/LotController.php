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
use Log;
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
        // Log::channel('debug')->info('--- Storing Lot');
        $messages = [
            'title.required' => 'Debes ingresar el título del lote/terreno.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder los 200 caracteres.',
            'title.unique' => 'Ya existe un lote/terreno con este título.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 100 caracteres.',
            'description.max' => 'La descripción debe tener al menos 3000 caracteres.',
            'status.required' => 'El estado del lote/terreno es obligatorio.',

            'country.required' => 'Debes ingresar el país.',
            'country.max' => 'El campo País debe tener un máximo de 100 caracteres.',
            'state.required' => 'Debes ingresar el Estado/Provincia.',
            'state.max' => 'El campo Estado/Provincia debe tener un máximo de 100 caracteres.',
            'city.required' => 'Debes ingresar la ciudad.',
            'city.max' => 'El campo Ciudad debe tener un máximo de 100 caracteres.',

            'frontage.string' => 'El campo Frente debe ser texto plano',
            'depth.string' => 'El campo Fondo debe ser texto plano',
            'total_area.string' => 'El campo Superficie Total debe ser texto plano',

            'price.required' => 'Debes ingresar el precio del lote/terreno.',
            'transaction_type.required' => 'El tipo de operación es obligatorio.',
            
            'external_url.string' => 'Debe ingresar una url válida en el campo "Link Externo"',

            'private_notes.max' => 'El campo "Notas (Privado Admin) no puede exceder los 2000 caracteres.',
            'seller_notes.max' => 'El campo "Notas (Vendedor) no puede exceder los 2000 caracteres.',

            'images.*.image' => 'Los archivos deben ser imágenes válidas.',
            'images.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg.',
            'images.*.max' => 'Las imágenes no deben superar los 5MB.',
            'image_order.array' => 'El orden de las imágenes debe ser un array válido.',

        ];

        $validations = $request->validate([
            'title' => 'required|min:3|max:200|unique:lots',
            'description' => 'required|string|min:100|max:3000',
            'status' => 'required|string',
            'featured' => 'boolean',
            
            'public_address' => 'nullable|string|max:255',
            'real_address' => 'nullable|string',
            'country' => 'required|string|max:100', 
            'state' => 'required|string|max:100', 
            'city' => 'required|string|max:100', 
            'neighborhood' => 'nullable|string|max:100', 

            'frontage' => 'nullable|string',
            'depth' => 'nullable|string',
            'total_area' => 'nullable|string',

            'services' => 'nullable|string',

            'price' => 'required|string',
            'transaction_type' => 'required|string',

            'video' => 'nullable',
            'external_url' => 'nullable|string',

            'is_in_gated_community' => 'boolean',

            'private_notes' => 'nullable|string|max:2000',
            'seller_notes' => 'nullable|string|max:2000',

            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'image_order' => 'array',


        ], $messages);
                

        $lotModel = new Lot();
        $lotModel->fill($request->only([
            'title', 'description', 'status', 'featured',
            'public_address', 'real_address', 'country', 'state', 'city', 'neighborhood',
            'frontage', 'depth', 'total_area', 'services', 'price', 'transaction_type',
            'video', 'external_url', 'is_in_gated_community', 'private_notes', 'seller_notes'
        ]));

        // Parse Google Maps iframe
        if (!empty($lotModel->real_address)) {
            $originalIframe = $lotModel->real_address;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $lotModel->real_address = $adaptedIframe;

        }

        // Parse YouTube iframe
        if (!empty($lotModel->video)) {
            $originalIframe = $lotModel->video;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $lotModel->video = $adaptedIframe;

        }

        $lotModel->slug = Str::slug($lotModel->title);
        $lotModel->save();
        $lotId = $lotModel->id;
    
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imageOrder = $request->input('image_order'); 
            foreach ($images as $index => $image) {                
                $order = array_search($index, $imageOrder);
                
                $uniqueTag = substr(md5($lotModel->slug . rand(0, 9999)), 0, 6);
                $truncatedSlugifiedTitle = Str::limit($lotModel->slug, 40, ''); // falta converitr a slug

                $mainFileName = $lotId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                $mediumImageFileName = $lotId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                $thumbnailImageFileName = $lotId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';
                

                $lotImage = New LotImage();
                $lotImage->lot_id = $lotId;
                $lotImage->image = $mainFileName;
                $lotImage->medium_image = $mediumImageFileName;
                $lotImage->thumbnail_image = $thumbnailImageFileName;
                $lotImage->img_alt = Str::limit($lotModel->title, 60, '');
                $lotImage->img_class = 'gallery-image';
                $lotImage->order = $order;
                $lotImage->save();

                $destinationPath = public_path('/files/img/lots');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                // Resize img, convert to webp and save file in different versions
                $isWebp = $image->getMimeType() === 'image/webp';
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image);
                $optimizedImage = optimizeImage($image, $isWebp);
                $optimizedImage->save($destinationPath . '/' . $mainFileName);
                
                $mediumImage = $manager->read($image);
                $optimizedMediumImage = optimizeImageMedium($mediumImage, $isWebp);
                $optimizedMediumImage->save($destinationPath . '/' . $mediumImageFileName);

                $thumbnailImage = $manager->read($image);
                $optimizedThumbnailImage = optimizeImageThumb($thumbnailImage, $isWebp);
                $optimizedThumbnailImage->save($destinationPath . '/' . $thumbnailImageFileName);
            }
        }
        


        return response()->json([
            'success' => true, 
            'message' => 'Se registró el lote/terreno con éxito'
        ]);
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
