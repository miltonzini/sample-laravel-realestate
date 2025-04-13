<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\User; 
use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PropertyImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class PropertyController extends Controller
{
    public function index() {
        $scripts = ['property.js?v=1.01'];
        return view('admin.properties.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['property.js?v=1.01'];
        return view('admin.properties.create', compact('scripts'));
    }

    public function store(Request $request){
        $messages = [
            'title.required' => 'Debes ingresar el título de la propiedad.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede exceder los 200 caracteres.',
            'title.unique' => 'Ya existe una propiedad con este título.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 100 caracteres.',
            'description.max' => 'La descripción debe tener al menos 3000 caracteres.',
            'property_type.required' => 'Selecciona un tipo de propiedad.',
            'status.required' => 'El estado de la propiedad es obligatorio.',

            'country.required' => 'Debes ingresar el país.',
            'state.required' => 'Debes ingresar el estado/provincia.',
            'city.required' => 'Debes ingresar la ciudad.',

            'rooms.integer' => 'El número de habitaciones debe ser un número entero.',
            'bathrooms.integer' => 'El número de baños debe ser un número entero.',
            'storage_room.required' => 'Debes completar el campo "Tiene Baulera"',

            'heating_type.max' => 'El campo "Calefacción" tiene un máximo de 50 caracteres',
            
            'price.required' => 'Debes ingresar el precio de la propiedad.',
            'transaction_type.required' => 'El tipo de operación es obligatorio.',

            'external_url.url' => 'Debe ingresar una url válida en el campo "Link Externo"',

            'private_notes.max' => 'El campo "Notas (Privado Admin) no puede exceder los 2000 caracteres.',
            'seller_notes.max' => 'El campo "Notas (Vendedor) no puede exceder los 2000 caracteres.',

            'images.*.image' => 'Los archivos deben ser imágenes válidas.',
            'images.*.mimes' => 'Las imágenes deben ser de tipo: jpeg, png, jpg.',
            'images.*.max' => 'Las imágenes no deben superar los 5MB.',
            'image_order.array' => 'El orden de las imágenes debe ser un array válido.',

            
        ];

        $validations = $request->validate([
            'title' => 'required|min:3|max:200|unique:properties',
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

            'rooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'covered_area' => 'nullable|string',
            'total_area' => 'nullable|string',
            'width' => 'nullable|string',
            'length' => 'nullable|string',
            'orientation' => 'nullable|string|max:2',
            'position' => 'nullable|string|max:50',
            'year_built' => 'nullable|integer|digits:4',
            'storage_room' => 'required|boolean', 

            'services' => 'nullable|string',
            'heating_type' => 'nullable|string|max:50',
            'amenities' => 'nullable|string',

            'price' => 'required|string',
            'transaction_type' => 'required|string',
            'hoa_fees' => 'nullable|string',

            'video' => 'nullable',
            'external_url' => 'nullable',

            'private_notes' => 'nullable|string|max:2000',
            'seller_notes' => 'nullable|string|max:2000',

            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'image_order' => 'array',
        ], $messages);


        $propertyModel = new Property();
        $propertyModel->fill($request->only([
            'title', 'description', 'property_type', 'status', 'featured',
            'public_address', 'real_address', 'country', 'state', 'city', 'neighborhood',
            'rooms', 'bathrooms', 'covered_area', 'total_area', 'width', 'length', 
            'orientation', 'position', 'year_built', 'storage_room', 'services', 
            'heating_type', 'amenities', 'price', 'transaction_type', 'hoa_fees',
            'video', 'external_url', 'private_notes', 'seller_notes'
        ]));

        // Parse Google Maps iframe
        if (!empty($propertyModel->real_address)) {
            $originalIframe = $propertyModel->real_address;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $propertyModel->real_address = $adaptedIframe;

        }

        // Parse YouTube iframe
        if (!empty($propertyModel->video)) {
            $originalIframe = $propertyModel->video;


            $adaptedIframe = preg_replace('/width="([^"]+)"/', 'width="100%"', $originalIframe);
            $adaptedIframe = preg_replace('/height="([^"]+)"/', 'height="100%"', $adaptedIframe);

            $propertyModel->video = $adaptedIframe;

        }

        $propertyModel->slug = Str::slug($propertyModel->title);
        $propertyModel->save();
        $propertyId = $propertyModel->id;


        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imageOrder = $request->input('image_order'); 
            foreach ($images as $index => $image) {                
                $order = array_search($index, $imageOrder);
                
                $uniqueTag = substr(md5($propertyModel->slug . rand(0, 9999)), 0, 6);
                $truncatedSlugifiedTitle = Str::limit($propertyModel->slug, 40, ''); // falta converitr a slug

                $mainFileName = $propertyId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
                $mediumImageFileName = $propertyId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-medium.webp';
                $thumbnailImageFileName = $propertyId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';
                

                $propertyImage = New PropertyImage();
                $propertyImage->property_id = $propertyId;
                $propertyImage->image = $mainFileName;
                $propertyImage->medium_image = $mediumImageFileName;
                $propertyImage->thumbnail_image = $thumbnailImageFileName;
                $propertyImage->img_alt = Str::limit($propertyModel->title, 60, '');
                $propertyImage->img_class = 'gallery-image';
                $propertyImage->order = $order;
                $propertyImage->save();

                $destinationPath = public_path('/files/img/properties');
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
            'message' => 'Se registró la propiedad con éxito'
        ]);
    }

    public function edit($id) {
        $scripts = ['property.js?v=1.01'];
        return view('admin.properties.edit', compact('scripts'));
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        $scripts = ['property.js?v=1.01'];
        return view('admin.properties.index', compact('scripts'));
    }
}
