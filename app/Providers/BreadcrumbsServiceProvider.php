<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BreadcrumbsCompat;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('breadcrumbs.compat', function ($app) {
            return new BreadcrumbsCompat();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
