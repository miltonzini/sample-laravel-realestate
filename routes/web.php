<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\Misc\UserController;
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

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login-user');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout-user');


// Public Forms Routes
Route::post('/contact', [ContactFormController::class, 'submit'])->name('contact.submit');

// Admin Routes
Route::middleware([AuthMiddleware::class, PreventBackHistoryMiddleware::class])->group(function () {
    Route::prefix('admin')->group(function() {
        Route::get('/', [DashboardController::class, 'showDashboard'])->name('admin.dashboard');
        Route::get('/listado-usuarios', [UserController::class, 'index'])->name('admin.users.index'); // Listado de Usuarios
        Route::get('/crear-usuario', [UserController::class, 'create'])->name('admin.users.create'); // Mostrar formulario para crear usuario
        Route::post('/store-user', [UserController::class, 'store'])->name('admin.users.store'); // Guardar usuario en la base de datos
    });
});


// Utility Routes (temp)
// Route::get('/session-info', [AuthController::class, 'showSessionInfo'])->name('user-session-info'); // Mostrar la información de la sesión activa
// Route::get('/migrate', function () {Artisan::call('migrate');return 'Migraciones ejecutadas';});
