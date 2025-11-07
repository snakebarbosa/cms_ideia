<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Breadcrumbs Facade for backward compatibility
 * 
 * @method static \App\Helpers\BreadcrumbsCompat addCrumb(string $title, string $url = '')
 * @method static \App\Helpers\BreadcrumbsCompat setDivider(string $divider)
 * @method static \App\Helpers\BreadcrumbsCompat setCssClasses(string $classes)
 * @method static string render()
 */
class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'breadcrumbs.compat';
    }
}
