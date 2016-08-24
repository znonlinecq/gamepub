<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth; 

class IndexController extends Controller
{
    public function index()
    {
        if(Auth::check())
        {
            $data['tasks'] = [
                [
                    'name' => 'Design New Dashboard',
                    'progress' => '87',
                    'color' => 'danger'
                ],
                [
                    'name' => 'Create Home Page',
                    'progress' => '76',
                    'color' => 'warning'
                ],
                [
                    'name' => 'Some Other Task',
                    'progress' => '32',
                    'color' => 'success'
                ],
                [
                    'name' => 'Start Building Website',
                    'progress' => '56',
                    'color' => 'info'
                ],
                [
                    'name' => 'Develop an Awesome Algorithm',
                    'progress' => '10',
                    'color' => 'success'
                ]
            ];
            return view('dashboard')->with($data);
         //   $breadcrumbs = $this->breadcrumbs;
         //   View::share('dashboard',$breadcumbs);
         //   return view('dashboard', ["breadcrumbs"=>$breadcrumbs]);
        }
        else
        {
            return view('auth/login');
        } 
    }
}
