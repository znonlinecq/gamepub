<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Menu;
use View;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $breadcrumbs;

    public function __construct()
    {
        if(Auth::check())
        {
            View::composer('*', function ($view) {
                $breadcrumbs = array(
                    '/' => 'Dashboard'
                );
                $view->with('breadcrumbs', $breadcrumbs);
            });
            View::composer('layouts/sidebar', function($view){
                $menus = Menu::menuLoad();
                $view->with('menus', $menus);
            });
        
        }
    }

    public function set_breadcrumbs($data)
    {
        foreach($data AS $key => $value)
        {
            $this->breadcrumbs[$key] = $value;
        }
    }
    
    public function get_breadcrumbs()
    {
        return $this->breadcrumbs;
    }

    public function page_empty()
    {
        abort(503);
    }
}
