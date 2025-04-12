<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Misc\UserController;
use App\Http\Controllers\Admin\Misc\PropertyController;
use App\Http\Controllers\Admin\Misc\DevelopmentController;
use App\Http\Controllers\Admin\Misc\BlogController;
use App\Http\Controllers\Public\FrontPageController; 
use App\Http\Controllers\Admin\Views\DashboardController;
use App\Http\Controllers\Public\Misc\ContactFormController; 
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;



// Temp Public Routes
Route::get('/under-construction', function () {return view('under_construction');})->name('under_construction');
Route::get('/initial-landing', function () {return view('landing_pages.initial');})->name('initial-landing'); // temporarily named 'home'
Route::get('/maintenance', function () {return view('maintenance.maintenance');})->name('maintenance'); // maintenance page

// Public Routes
Route::get('/', [FrontPageController::class, 'home'])->name('home');
Route::get('/preguntas-frecuentes', [FrontPageController::class, 'faq'])->name('faq');

Route::get('/comprar', [FrontPageController::class, 'buy'])->name('buy');
Route::get('/alquilar', [FrontPageController::class, 'rent'])->name('rent');
Route::get('/propiedades/{slug}', [FrontPageController::class, 'propertyDetails'])->name('propertyDetails');
Route::get('/filtrar-propiedades', [FrontPageController::class, 'filterProperties'])->name('filterProperties');

Route::get('/emprendimientos/{slug}', [FrontPageController::class, 'developmentDetails'])->name('developmentDetails');
Route::get('/emprendimientos', [FrontPageController::class, 'developments'])->name('developments');
Route::get('/filtrar-emprendimientos', [FrontPageController::class, 'filterDevelopments'])->name('filterDevelopments');


Route::get('/sumate-al-equipo', [FrontPageController::class, 'joinOurTeam'])->name('joinOurTeam');


Route::get('/blog', [FrontPageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [FrontPageController::class, 'post'])->name('blog.post');
Route::get('/filtrar-posts', [FrontPageController::class, 'filterPosts'])->name('filterPosts');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-user');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout-user');


// Public Forms Routes
Route::post('/contact', [ContactFormController::class, 'submit'])->name('contact.submit');

// Admin Routes
Route::middleware([AuthMiddleware::class, PreventBackHistoryMiddleware::class])->group(function () {
    Route::prefix('admin')->group(function() {
        
        Route::get('/', [DashboardController::class, 'showDashboard'])->name('admin.dashboard');
        
        // Users
        Route::get('/listado-usuarios', [UserController::class, 'index'])->name('admin.users.index'); // Listado de Usuarios
        Route::get('/crear-usuario', [UserController::class, 'create'])->name('admin.users.create'); // Mostrar formulario para crear usuario
        Route::post('/store-user', [UserController::class, 'store'])->name('admin.users.store'); // Guardar usuario en la base de datos

        // Properties
        Route::get('/listado-propiedades', [PropertyController::class, 'index'])->name('admin.properties.index');
        Route::post('/listado-propiedades', [PropertyController::class, 'search'])->name('admin.properties.search');
        Route::get('/crear-propiedad', [PropertyController::class, 'create'])->name('admin.properties.create');
        Route::post('/guardar-propiedad', [PropertyController::class, 'store'])->name('admin.properties.store');
        Route::get('/editar-propiedad/{id}', [PropertyController::class, 'edit'])->name('admin.properties.edit');
        Route::post('/actualizar-propiedad/{id}', [PropertyController::class, 'update'])->name('admin.properties.update'); 
        Route::delete('/eliminar-propiedad/{id}', [PropertyController::class, 'delete'])->name('admin.properties.delete'); 

        // Developments
        Route::get('/listado-emprendimientos', [DevelopmentController::class, 'index'])->name('admin.developments.index');
        Route::post('/listado-emprendimientos', [DevelopmentController::class, 'search'])->name('admin.developments.search');
        Route::get('/crear-emprendimiento', [DevelopmentController::class, 'create'])->name('admin.developments.create');
        Route::post('/guardar-emprendimiento', [DevelopmentController::class, 'store'])->name('admin.developments.store');
        Route::get('/editar-emprendimiento/{id}', [DevelopmentController::class, 'edit'])->name('admin.developments.edit');
        Route::post('/actualizar-emprendimiento/{id}', [DevelopmentController::class, 'update'])->name('admin.developments.update'); 
        Route::delete('/eliminar-emprendimiento/{id}', [DevelopmentController::class, 'delete'])->name('admin.developments.delete'); 
        
        // Blog
        Route::get('/listado-posts', [BlogController::class, 'index'])->name('admin.blog.index');
        Route::post('/listado-posts', [BlogController::class, 'search'])->name('admin.blog.search');
        Route::get('/crear-post', [BlogController::class, 'create'])->name('admin.blog.create');
        Route::post('/guardar-post', [BlogController::class, 'store'])->name('admin.blog.store');
        Route::get('/editar-post/{id}', [BlogController::class, 'edit'])->name('admin.blog.edit');
        Route::post('/actualizar-post/{id}', [BlogController::class, 'update'])->name('admin.blog.update');
        Route::delete('/eliminar-post/{id}', [BlogController::class, 'delete'])->name('admin.blog.delete');
        
    });
});


// Utility Routes (temp)
// Route::get('/session-info', [AuthController::class, 'showSessionInfo'])->name('user-session-info'); // Mostrar la información de la sesión activa
// Route::get('/migrate', function () {Artisan::call('migrate');return 'Migraciones ejecutadas';});
