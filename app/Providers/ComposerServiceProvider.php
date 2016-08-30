<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Menu;

class ComposerServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $breadcrumbs = array(
                '/' => 'Dashboard'
            );
            $menus = Menu::menuLoad();
            $view->with('menus', $menus);
            $view->with('breadcrumbs', $breadcrumbs);
        }); 
        
 
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
