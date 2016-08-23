<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GroupController extends Controller
{
    public function groupList(){
        return view('group/groupList', ['title'=>'公会审核']);
    } 
   
    public function groupFounderList(){
        return view('group/groupFounderList', ['title'=>'会长审核']);
    }  
}
