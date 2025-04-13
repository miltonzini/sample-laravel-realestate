<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\Post;
use App\Models\User; 
use App\Models\PostImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;

class BlogController extends Controller
{
    public function index() {
        $scripts = ['blog.js?v=1.01'];
        return view('admin.blog.index', compact('scripts'));
    }

    public function create() {
        $scripts = ['blog.js?v=1.01'];
        return view('admin.blog.create', compact('scripts'));
    }

    public function store(Request $request) {
        $messages = [
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 15 caracteres.',
            'title.max' => 'El título no puede tener más de 200 caracteres.',
            'title.unique' => 'Este título ya ha sido registrado.',
        
            'slug.required' => 'El slug es obligatorio.',
            'slug.min' => 'El slug debe tener al menos 8 caracteres.',
            'slug.max' => 'El slug no puede tener más de 200 caracteres.',
            'slug.unique' => 'Este slug ya está en uso.',
        
            'short_description.required' => 'La descripción corta es obligatoria.',
            'short_description.string' => 'La descripción corta debe ser un texto válido.',
            'short_description.min' => 'La descripción corta debe tener al menos 100 caracteres.',
            'short_description.max' => 'La descripción corta no puede superar los 3000 caracteres.',
        
            'body.required' => 'El cuerpo del contenido es obligatorio.',
            'body.string' => 'El cuerpo del contenido debe ser un texto válido.',
            'body.min' => 'El cuerpo del contenido debe tener al menos 300 caracteres.',
        
            'button_text.nullable' => 'El texto del botón es opcional.',
            'button_text.string' => 'El texto del botón debe ser un texto válido.',
            'button_text.required_with' => 'Si defines una URL para el botón, debes proporcionar un texto.',
        
            'button_url.nullable' => 'La URL del botón es opcional.',
            'button_url.string' => 'La URL del botón debe ser un texto válido.',
            'button_url.required_with' => 'Si defines un texto para el botón, debes proporcionar una URL.',
                
            'status.required' => 'El estado es obligatorio.',
            'status.string' => 'El estado debe ser un texto válido.',
        
            'image.required' => 'La imagen es obligatoria.',
            'image.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg o webp.',
            'image.max' => 'La imagen no debe superar los 5MB.',
        
            'img_alt.required' => 'El texto alternativo de la imagen es obligatorio.',
            'img_alt.string' => 'El texto alternativo de la imagen debe ser un texto válido.',
        ];

        $validations = $request->validate([
            'title' => 'required|min:15|max:200|unique:posts',
            'slug' => 'required|min:8|max:200|unique:posts',
            'short_description' => 'required|string|min:100|max:3000',
            'body' => 'required|string|min:300',
            'button_text' => 'nullable|string|required_with:button_url',
            'button_url' => 'nullable|string|required_with:button_text',
            'status' => 'required|string',
            'image' => 'required|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'img_alt' => 'required|string', 
        ], $messages);

        $title = $request->input('title');
        $slug = $request->input('slug');
        $status = $request->input('status');
        $shortDescription = $request->input('short_description');
        $body = $request->input('body');
        $buttonText = $request->input('button_text');
        $buttonUrl = $request->input('button_url');
        $author = Session('user')['id']; // author id

        $postModel = new Post();
        $postModel->title = $title;
        $postModel->slug = $slug;
        $postModel->status = $status;
        $postModel->short_description = $shortDescription;
        $postModel->body = $body;
        $postModel->button_text = $buttonText;
        $postModel->button_url = $buttonUrl;
        $postModel->author = $author;
        $postModel->save();
        $postId = $postModel->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $uniqueTag = substr(md5($postModel->slug . rand(0, 9999)), 0, 6);
            $truncatedSlugifiedTitle = Str::limit($postModel->slug, 40, ''); 

            $mainFileName = $postId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '.webp';
            $thumbnailImageFileName = $postId . '-' . $truncatedSlugifiedTitle . '-' . $uniqueTag . '-thumb.webp';

            $postImage = New PostImage();
            $postImage->post_id = $postId;
            $postImage->image = $mainFileName;
            $postImage->thumbnail_image = $thumbnailImageFileName;
            $postImage->img_alt = $request->input('img_alt');
            $postImage->save();

            $destinationPath = public_path('/files/img/posts');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Resize img, convert to webp and save file in different versions
            $isWebp = $image->getMimeType() === 'image/webp';
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $optimizedImage = optimizeImage($image, $isWebp);
            $optimizedImage->save($destinationPath . '/' . $mainFileName);

            $thumbnailImage = $manager->read($image);
            $optimizedThumbnailImage = optimizeImageThumb($thumbnailImage, $isWebp);
            $optimizedThumbnailImage->save($destinationPath . '/' . $thumbnailImageFileName);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post creado con éxito'
        ]);    
    }

    public function edit($id) {
        $scripts = ['blog.js'];
        return view('admin.blog.edit', compact('scripts'));
    }

    public function update($id, Request $request) {
        // ...
    }

    public function delete($id) {
        // ...
    }

    public function search(Request $request) {
        $scripts = ['blog.js'];
        return view('admin.blog.index', compact('scripts'));
    }
}
