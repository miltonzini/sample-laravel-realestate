<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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

        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::default');
    }
}
