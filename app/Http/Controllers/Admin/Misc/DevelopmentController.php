<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\User; 
use App\Models\Development;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DevelopmentImage;    
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class DevelopmentController extends Controller
{
    public function index() {
        $developments = Development::orderBy('id')->paginate(20);
        $developmentsCount = $developments->total();        
        $scripts = ['development.js'];
        return view('admin.developments.index', compact('scripts', 'developments', 'developmentsCount'));
    }

    public function create() {
        $scripts = ['development.js'];
        return view('admin.developments.create', compact('scripts'));
    }

    public function store(Request $request) {
        $messages = [ 
            'title.required' => 'Debes ingresar el título del emprendimiento.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder los 200 caracteres.',
            'title.unique' => 'Ya existe un emprendimiento con este título.',
            
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser texto.',
            'description.min' => 'La descripción debe tener al menos 100 caracteres.',
            'description.max' => 'La descripción no puede exceder los 3000 caracteres.',
            
            'property_type.required' => 'Selecciona un tipo de propiedad.',
            'property_type.string' => 'El tipo de emprendimiento debe ser texto.',
            'status.required' => 'El estado del emprendimiento es obligatorio.',
            'status.string' => 'El estado debe ser texto.',
            'featured.boolean' => 'El campo destacado debe ser verdadero o falso.',

            'public_address.string' => 'La dirección pública debe ser texto.',
            'public_address.max' => 'La dirección pública no puede exceder los 255 caracteres.',
            'real_address.string' => 'La dirección real debe ser texto.',
            'country.required' => 'Debes ingresar el país.',
            'country.string' => 'El país debe ser texto.',
            'country.max' => 'El país no puede exceder los 100 caracteres.',
            'state.required' => 'Debes ingresar el estado/provincia.',
            'state.string' => 'El estado/provincia debe ser texto.',
            'state.max' => 'El estado/provincia no puede exceder los 100 caracteres.',
            'city.required' => 'Debes ingresar la ciudad.',
            'city.string' => 'La ciudad debe ser texto.',
            'city.max' => 'La ciudad no puede exceder los 100 caracteres.',
            'neighborhood.string' => 'El barrio debe ser texto.',
            'neighborhood.max' => 'El barrio no puede exceder los 100 caracteres.',

            'estimated_delivery_date.string' => 'La fecha estimada de entrega debe ser texto.',
            'estimated_delivery_date.max' => 'La fecha estimada de entrega no puede exceder los 100 caracteres.',
            'project_status.string' => 'El estado del proyecto debe ser texto.',
            'project_status.max' => 'El estado del proyecto no puede exceder los 100 caracteres.',
            'developer.string' => 'El desarrollador debe ser texto.',
            'developer.max' => 'El desarrollador no puede exceder los 100 caracteres.',

            'services.string' => 'Los servicios deben ser texto.',
            'amenities.string' => 'Las amenities deben ser texto.',
            
            'price_range.required' => 'Debes ingresar el rango de precio.',
            'price_range.string' => 'El rango de precio debe ser texto.',

            'video.string' => 'El video debe ser texto.',
            'external_url.string' => 'El link externo debe ser texto.',

            'private_notes.string' => 'Las notas privadas deben ser texto.',
            'private_notes.max' => 'Las notas privadas no pueden exceder los 2000 caracteres.',
            'seller_notes.string' => 'Las notas del vendedor deben ser texto.',
            'seller_notes.max' => 'Las notas del vendedor no pueden exceder los 2000 caracteres.',

            'images.*.image' => 'Los archivos deben ser imágenes válidas.',
            'images.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg.',
            'images.*.max' => 'Las imágenes no deben superar los 5MB.',
            'image_order.array' => 'El orden de las imágenes debe ser un array válido.',
  
        ];

        $validations = $request->validate([ 
            'title' => 'required|min:3|max:200|unique:developments',
            'description' => 'required|string|min:100|max:3000',
            'property_type' => 'required|string',
            'status' => 'required|string',
            'featured' => 'boolean',

            'public_address' => 'nullable|string|max:255',
            'real_address' => 'nullable|string',
            'country' => 'required|string|max:100', 
            'state' => 'required|string|max:100', 
            'city' => 'required|string|max:100', 
            'neighborhood' => 'nullable|string|max:100', 

            'estimated_delivery_date' => 'nullable|string|max:100', 
            'project_status' => 'nullable|string|max:100', 
            'developer' => 'nullable|string|max:100', 

            'services' => 'nullable|string',
            'amenities' => 'nullable|string',
            'price_range' => 'required|string',

            'video' => 'nullable',
            'external_url' => 'nullable',

            'private_notes' => 'nullable|string|max:2000',
            'seller_notes' => 'nullable|string|max:2000',

            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'image_order' => 'array',
        ], $messages);


        $developmentModel = new Development();
        $developmentModel->fill($request->only([
            'title', 'description', 'property_type', 'status', 'featured', 
            'public_address', 'real_address', 'country', 'state', 'city', 'neighborhood',
            'estimated_delivery_date', 'project_status', 'developer', 'services', 'amenities', 
            'price_range', 'video', 'external_url', 'private_notes', 'seller_notes'
        ]));

        // Parse Google Maps iframe
        if (!empty($developmentModel->real_address)) {
            $originalIframe = $developmentModel->real_address;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $developmentModel->real_address = $adaptedIframe;

        }

        // Parse YouTube iframe
        if (!empty($developmentModel->video)) {
            $originalIframe = $developmentModel->video;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $developmentModel->video = $adaptedIframe;

        }

        $developmentModel->slug = Str::slug($developmentModel->title);
        $developmentModel->save();
        $developmentId = $developmentModel->id;


        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imageOrder = $request->input('image_order'); 
            foreach ($images as $index => $image) {                
                $order = array_search($index, $imageOrder);
                
                $uniqueTag = substr(md5($developmentModel->slug . rand(0, 9999)), 0, 6);
                $truncatedSlugifiedTitle = Str::limit($developmentModel->slug, 40, ''); // falta converitr a slug

                $mainFileName = $developmentId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                $mediumImageFileName = $developmentId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                $thumbnailImageFileName = $developmentId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';
                

                $DevelopmentImage = New DevelopmentImage();
                $DevelopmentImage->development_id = $developmentId;
                $DevelopmentImage->image = $mainFileName;
                $DevelopmentImage->medium_image = $mediumImageFileName;
                $DevelopmentImage->thumbnail_image = $thumbnailImageFileName;
                $DevelopmentImage->img_alt = Str::limit($developmentModel->title, 60, '');
                $DevelopmentImage->img_class = 'gallery-image';
                $DevelopmentImage->order = $order;
                $DevelopmentImage->save();

                $destinationPath = public_path('/files/img/developments');
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
            'message' => 'Se registró el emprendimiento con éxito'
        ]);
    }

    public function edit($id) {
        $development = Development::with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])->find($id);

        if (!$development) {
            return redirect()->route('home')->with('error', 'El emprendimiento no existe.');
        }

        $scripts = ['development.js'];
        return view('admin.developments.edit', compact('scripts', 'development'));

    }

    public function update($id, Request $request) {
        $development = Development::where('id', $id)->firstOrFail(); 
        
        $messages = [ 
            'title.required' => 'Debes ingresar el título del emprendimiento.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder los 200 caracteres.',
            'title.unique' => 'Ya existe un emprendimiento con este título.',
            
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser texto.',
            'description.min' => 'La descripción debe tener al menos 100 caracteres.',
            'description.max' => 'La descripción no puede exceder los 3000 caracteres.',
            
            'property_type.required' => 'Selecciona un tipo de propiedad.',
            'property_type.string' => 'El tipo de emprendimiento debe ser texto.',
            'status.required' => 'El estado del emprendimiento es obligatorio.',
            'status.string' => 'El estado debe ser texto.',
            'featured.boolean' => 'El campo destacado debe ser verdadero o falso.',

            'public_address.string' => 'La dirección pública debe ser texto.',
            'public_address.max' => 'La dirección pública no puede exceder los 255 caracteres.',
            'real_address.string' => 'La dirección real debe ser texto.',
            'country.required' => 'Debes ingresar el país.',
            'country.string' => 'El país debe ser texto.',
            'country.max' => 'El país no puede exceder los 100 caracteres.',
            'state.required' => 'Debes ingresar el estado/provincia.',
            'state.string' => 'El estado/provincia debe ser texto.',
            'state.max' => 'El estado/provincia no puede exceder los 100 caracteres.',
            'city.required' => 'Debes ingresar la ciudad.',
            'city.string' => 'La ciudad debe ser texto.',
            'city.max' => 'La ciudad no puede exceder los 100 caracteres.',
            'neighborhood.string' => 'El barrio debe ser texto.',
            'neighborhood.max' => 'El barrio no puede exceder los 100 caracteres.',

            'estimated_delivery_date.string' => 'La fecha estimada de entrega debe ser texto.',
            'estimated_delivery_date.max' => 'La fecha estimada de entrega no puede exceder los 100 caracteres.',
            'project_status.string' => 'El estado del proyecto debe ser texto.',
            'project_status.max' => 'El estado del proyecto no puede exceder los 100 caracteres.',
            'developer.string' => 'El desarrollador debe ser texto.',
            'developer.max' => 'El desarrollador no puede exceder los 100 caracteres.',

            'services.string' => 'Los servicios deben ser texto.',
            'amenities.string' => 'Las amenities deben ser texto.',
            
            'price_range.required' => 'Debes ingresar el rango de precio.',
            'price_range.string' => 'El rango de precio debe ser texto.',

            'video.string' => 'El video debe ser texto.',
            'external_url.string' => 'El link externo debe ser texto.',

            'private_notes.string' => 'Las notas privadas deben ser texto.',
            'private_notes.max' => 'Las notas privadas no pueden exceder los 2000 caracteres.',
            'seller_notes.string' => 'Las notas del vendedor deben ser texto.',
            'seller_notes.max' => 'Las notas del vendedor no pueden exceder los 2000 caracteres.',

            'images.*.image' => 'Los archivos deben ser imágenes válidas.',
            'images.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg.',
            'images.*.max' => 'Las imágenes no deben superar los 5MB.',
            'image_order.array' => 'El orden de las imágenes debe ser un array válido.',
        ];

        $validations = $request->validate([ 
            'title' => 'required|min:3|max:200|unique:developments,title,' . $id,
            'description' => 'required|string|min:100|max:3000',
            'property_type' => 'required|string',
            'status' => 'required|string',
            'featured' => 'boolean',

            'public_address' => 'nullable|string|max:255',
            'real_address' => 'nullable|string',
            'country' => 'required|string|max:100', 
            'state' => 'required|string|max:100', 
            'city' => 'required|string|max:100', 
            'neighborhood' => 'nullable|string|max:100', 

            'estimated_delivery_date' => 'nullable|string|max:100', 
            'project_status' => 'nullable|string|max:100', 
            'developer' => 'nullable|string|max:100', 

            'services' => 'nullable|string',
            'amenities' => 'nullable|string',
            'price_range' => 'required|string',

            'video' => 'nullable',
            'external_url' => 'nullable',

            'private_notes' => 'nullable|string|max:2000',
            'seller_notes' => 'nullable|string|max:2000',

            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'image_order' => 'array',
        ], $messages);


        $title = $request->input('title');
        $slug = Str::slug($request->input('title'));
        $description = $request->input('description');
        $propertyType = $request->input('property_type');
        $status = $request->input('status');
        $featured = $request->input('featured');
        
        $publicAddress = $request->input('public_address');
        $realAddress = $request->input('real_address');
        $country = $request->input('country');
        $state = $request->input('state');
        $city = $request->input('city');
        $neighborhood = $request->input('neighborhood');
        
        $estimatedDeliveryDate = $request->input('estimated_delivery_date');
        $projectStatus = $request->input('project_status');
        $developer = $request->input('developer');

        $services = $request->input('services');
        $amenities = $request->input('amenities');
        $priceRange = $request->input('price_range');
        
        $video = $request->input('video');
        $externalUrl = $request->input('external_url');
        
        $privateNotes = $request->input('private_notes');
        $sellerNotes = $request->input('seller_notes');


        $originalDevelopmentTitle = $development->title;
        $updateExistingImagesNames = false;
        if ($originalDevelopmentTitle != $title) {
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

        $development->update([ 
            'title' => $title,
            'description' => $description,
            'property_type' => $propertyType,
            'status' => $status,
            'featured' => $featured,
            
            'public_address' => $publicAddress,
            'real_address' => $realAddress,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'neighborhood' => $neighborhood,
            
            'estimated_delivery_date' => $estimatedDeliveryDate,
            'project_status' =>$projectStatus,
            'developer' => $developer,
            
            'services' => $services,
            'amenities' => $amenities,
            'price_range' => $priceRange,
                        
            'video' => $video,
            'external_url' => $externalUrl,
            
            'private_notes' => $privateNotes,
            'seller_notes' => $sellerNotes,
            
            'slug' => $slug
        ]);
    
        // Handle deleted images
        if ($request->has('deleted_images')) {
            $deletedImages = $request->input('deleted_images');
            foreach ($deletedImages as $imageId) {
                $image = DevelopmentImage::find($imageId);
                if ($image) {
                    $basePath = public_path('/files/img/developments/');
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
                    $developmentImage = DevelopmentImage::find($imageId);
                    if ($developmentImage) {
                        $developmentImage->order = $index;
                        $maxOrder = max($maxOrder, $index);
                        
                        if ($updateExistingImagesNames) {
                            $developmentsImagesBasePath = public_path('/files/img/developments/');
                            $uniqueTag = substr(md5($slug . rand(0, 9999)), 0, 6);
                            $truncatedSlugifiedTitle = Str::limit($slug, 40, '');

                            $mainFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                            $mediumImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                            $thumbnailImageFileName = $id . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';

                            $originalMainImagePath = $developmentsImagesBasePath . $developmentImage->image;
                            $originalMediumImagePath = $developmentsImagesBasePath . $developmentImage->medium_image;
                            $originalThumbnailImagePath = $developmentsImagesBasePath . $developmentImage->thumbnail_image;

                            if (file_exists($originalMainImagePath)) {
                                rename($originalMainImagePath, $developmentsImagesBasePath . $mainFileName);
                            }

                            if (file_exists($originalMediumImagePath)) {
                                rename($originalMediumImagePath, $developmentsImagesBasePath . $mediumImageFileName);
                            }

                            if (file_exists($originalThumbnailImagePath)) {
                                rename($originalThumbnailImagePath, $developmentsImagesBasePath . $thumbnailImageFileName);
                            }

                            $developmentImage->image = $mainFileName;
                            $developmentImage->medium_image = $mediumImageFileName;
                            $developmentImage->thumbnail_image = $thumbnailImageFileName;
                        }
                        
                        $developmentImage->save();
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

                    $developmentImage = new DevelopmentImage();
                    $developmentImage->development_id = $id;
                    $developmentImage->image = $mainFileName;
                    $developmentImage->medium_image = $mediumImageFileName;
                    $developmentImage->thumbnail_image = $thumbnailImageFileName;
                    $developmentImage->img_alt = Str::limit($development->title, 60, '');
                    $developmentImage->img_class = $request->input('image_class')[0] ?? 'gallery-image';
                    $developmentImage->order = $order;
                    $developmentImage->save();

                    $destinationPath = public_path('/files/img/developments');
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
            'message' => 'El emprendimiento se actualizó con éxito'
        ]);
    }

    public function delete($id) {
        $development = Development::where('id', $id)
        ->with(['images' => function($query) {
            $query->orderBy('order', 'asc');
        }])
        ->first();

        if (!$development) {
            return Response()->json([
                'success' => false,
                'message' => 'No se ha encontrado el emprendimiento'
            ]);
        }

        foreach($development->images as $image) {

            if ($image) {
                $basePath = public_path('/files/img/developments/');
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

        $development->delete();


        return response()->json([
            'success' => true, 
            'message' => 'El emprendimiento se eliminó con éxito'
        ]);
    }

    public function search(Request $request) {
        $search = $request->search;
        $developments = Development::where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%search%")
                    ->paginate(20);
        $developmentsCount = $developments->total();
        $scripts = [''];
        return view('admin.developments.index', compact('developments', 'developmentsCount', 'scripts', 'search'));
    }
}
