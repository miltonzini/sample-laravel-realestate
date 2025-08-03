<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\View\Composers\PublicViewComposer; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('assetVersionQueryString', '?v=' . env('ASSET_VERSION', '1'));

        View::composer([
            // Vistas principales
            'home',
            'faq',
            'properties',
            'property-details',
            'property-search-results',
            'developments',
            'development-details',
            'development-search-results',
            'join-our-team',
            
            // Vistas del blog
            'blog.index',
            'blog.post',
            'blog-search-results',
            
            // Vistas de autenticación (públicas pero no admin)
            'auth.login',
            
            // Vistas temporales/mantenimiento
            'under_construction',
            'landing_pages.initial',
            'maintenance.maintenance',
            
        ], PublicViewComposer::class);

        // Configuración de paginación
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::default');
    }
}