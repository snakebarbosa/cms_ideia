<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            [
                'Pages.home',
                'Pages.artigo2',
                'Pages.nav',
                'Pages.navtag',
                'Pages.search',
                'Pages.org',
                'Pages.documento',
                'Pages.faq',
                'Pages.navartigo',
                'Pages.navfaq',
                'Pages.navdoc',
                'Pages.artigomenu',
                'Pages.artigo',
                'Pages.contact',
                'Pages.dash',
            ],
            'App\Http\View\Composers\CMSViewComposer'
        );
    }
}
