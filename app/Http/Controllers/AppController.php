<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AppController extends Controller
{
    public function appList(){
        return view('app/appList', ['title'=>'应用审核']);
    } 
   
    public function appBlackList(){
        return view('app/appBlackList', ['title'=>'应用黑名单']);
    } 
}
