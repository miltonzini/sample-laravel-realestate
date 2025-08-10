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
        $lot = Lot::with(['images' => function($query) {
                $query->orderBy('order', 'asc');
        }])->find($id);            

        if (!$lot) {
            return redirect()->route('home')->with('error', 'El Lote/Terreno no existe.');
        }

        $scripts = ['lot.js'];
        return view('admin.lots.edit', compact('scripts', 'lot'));
    }

    public function update($id, Request $request) {
        
        $lot = Lot::where('id', $id)->firstOrFail(); 

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
            'title' => 'required|min:3|max:200|unique:lots,title,' . $id,
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

        $title = $request->input('title');
        $slug = Str::slug($request->input('title'));
        $description = $request->input('description');
        $status = $request->input('status');
        $featured = $request->input('featured');

        $publicAddress = $request->input('public_address');
        $realAddress = $request->input('real_address');
        $country = $request->input('country');
        $state = $request->input('state');
        $city = $request->input('city');
        $neighborhood = $request->input('neighborhood');

        $frontage = $request->input('frontage');
        $depth = $request->input('depth');
        $totalArea = $request->input('total_area');

        $services = $request->input('services');

        $price = $request->input('price');
        $transactionType = $request->input('transaction_type');

        $video = $request->input('video');
        $externalUrl = $request->input('external_url');
        
        $isInGatedComunity = $request->input('is_in_gated_community');

        $privateNotes = $request->input('private_notes');
        $sellerNotes = $request->input('seller_notes');

        $originalLotTitle = $lot->title;
        $updateExistingImagesNames = false;
        if ($originalLotTitle != $title) {
            $updateExistingImagesNames = true;
        }


        if (!empty($realAddress)) {
            $realAddress = preg_replace('/width="([^"]+)"/', 'width="100%"', $realAddress);
            $realAddress = preg_replace('/height="([^"]+)"/', 'height="100%"', $realAddress);
        }

        if (!empty($video)) {
            $video = preg_replace('/width="([^"]+)"/', 'width="100%"', $video);
            $video = preg_replace('/height="([^"]+)"/', 'height="100%"', $video);
        }

        $lot->update([ 
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'featured' => $featured,

            'public_address' => $publicAddress,
            'real_address' => $realAddress,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'neighborhood' => $neighborhood,

            'frontage' => $frontage,
            'depth' => $depth,
            'total_area' => $totalArea,

            'services' => $services,

            'price' => $price,
            'transaction_type' => $transactionType,

            'video' => $video,
            'external_url' => $externalUrl,
            
            'is_in_gated_community' => $isInGatedComunity,

            'private_notes' => $privateNotes,
            'seller_notes' => $sellerNotes,
            
            'slug' => $slug

        ]);
        
        // Handle deleted images
        if ($request->has('deleted_images')) {
            $deletedImages = $request->input('deleted_images');
            foreach ($deletedImages as $imageId) {
                $image = LotImage::find($imageId);
                if ($image) {
                    $basePath = public_path('/files/img/lots/');
                    if (file_exists($basePath . $image->image)) {
                        unlink($basePath . $image->image);
                    }
                    if (file_exists($basePath . $image->medium_image)) {
                        unlink($basePath . $image->medium_image);
                    }
                    if (file_exists($basePath . $image->thumbnail_image)) {
                        unlink($basePath . $image->thumbnail_image);
                    }
                    
                    $image->delete();
                }
            }
        }

        // Handle image ordering and new images
        if ($request->has('image_order')) {
            $imageOrder = $request->input('image_order');
            $maxOrder = -1; // Start at -1 so first order will be 0
            
            // First process existing images
            foreach ($imageOrder as $index => $imageId) {
                if (is_numeric($imageId)) {
                    $lotImage = LotImage::find($imageId);
                    if ($lotImage) {
                        $lotImage->order = $index;
                        $maxOrder = max($maxOrder, $index);
                        
                        if ($updateExistingImagesNames) {
                            $lotsImagesBasePath = public_path('/files/img/lots/');
                            $uniqueTag = substr(md5($slug . rand(0, 9999)), 0, 6);
                            $truncatedSlugifiedTitle = Str::limit($slug, 40, '');

                            $mainFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                            $mediumImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                            $thumbnailImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';

                            $originalMainImagePath = $lotsImagesBasePath . $lotImage->image;
                            $originalMediumImagePath = $lotsImagesBasePath . $lotImage->medium_image;
                            $originalThumbnailImagePath = $lotsImagesBasePath . $lotImage->thumbnail_image;

                            if (file_exists($originalMainImagePath)) {
                                rename($originalMainImagePath, $lotsImagesBasePath . $mainFileName);
                            }

                            if (file_exists($originalMediumImagePath)) {
                                rename($originalMediumImagePath, $lotsImagesBasePath . $mediumImageFileName);
                            }

                            if (file_exists($originalThumbnailImagePath)) {
                                rename($originalThumbnailImagePath, $lotsImagesBasePath . $thumbnailImageFileName);
                            }

                            $lotImage->image = $mainFileName;
                            $lotImage->medium_image = $mediumImageFileName;
                            $lotImage->thumbnail_image = $thumbnailImageFileName;
                        }
                        
                        $lotImage->save();
                    }
                }
            }

            // Then process new images
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                
                foreach ($images as $index => $image) {
                    // Look for 'new-{$index}' in the order array
                    $newIndex = array_search('new-' . $index, $imageOrder);
                    // If found, use that position, otherwise put after last used position
                    $order = $newIndex !== false ? $newIndex : ++$maxOrder;
                    
                    $uniqueTag = substr(md5($slug . rand(0, 9999)), 0, 6);
                    $truncatedSlugifiedTitle = Str::limit($slug, 40, '');

                    $mainFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                    $mediumImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                    $thumbnailImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';

                    $lotImage = new LotImage();
                    $lotImage->lot_id = $id;
                    $lotImage->image = $mainFileName;
                    $lotImage->medium_image = $mediumImageFileName;
                    $lotImage->thumbnail_image = $thumbnailImageFileName;
                    $lotImage->img_alt = Str::limit($lot->title, 60, '');
                    $lotImage->img_class = $request->input('image_class')[0] ?? 'gallery-image';
                    $lotImage->order = $order;
                    $lotImage->save();

                    $destinationPath = public_path('/files/img/lots');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    $isWebp = $image->getMimeType() === 'image/webp';
                    $manager = new ImageManager(new Driver());
                    
                    $mainImage = $manager->read($image);
                    $optimizedImage = optimizeImage($mainImage, $isWebp);
                    $optimizedImage->save($destinationPath . '/' . $mainFileName);
                    
                    $mediumImage = $manager->read($image);
                    $optimizedMediumImage = optimizeImageMedium($mediumImage, $isWebp);
                    $optimizedMediumImage->save($destinationPath . '/' . $mediumImageFileName);

                    $thumbnailImage = $manager->read($image);
                    $optimizedThumbnailImage = optimizeImageThumb($thumbnailImage, $isWebp);
                    $optimizedThumbnailImage->save($destinationPath . '/' . $thumbnailImageFileName);
                }
            }
        }


        return response()->json([
            'success' => true, 
            'message' => 'El lote/terreno se actualizó con éxito'
        ]);
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        // ...
    }
}
