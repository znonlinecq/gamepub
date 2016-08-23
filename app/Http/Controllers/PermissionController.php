<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PermissionController extends Controller
{
    public function permissionList(){
        return view('permission/permissionList', ['title'=>'角色授权']);
    } 
   
}
