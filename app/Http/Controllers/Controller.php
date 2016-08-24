<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $breadcrumbs;

    function __construct()
    {
        $this->breadcrumbs = array(
            '/' => 'Dashboard',
        );
    }

    function set_breadcrumbs($data)
    {
        foreach($data AS $key => $value)
        {
            $this->breadcrumbs[$key] = $value;
        }
    }
    
    function get_breadcrumbs()
    {
        return $this->breadcrumbs;
    }
}
