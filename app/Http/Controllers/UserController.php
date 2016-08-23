<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    //
    
    public function index()
    {
        $users = User::all();
        return view('user/list', ['users'=>$users]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user/edit', ['user'=>$user]);
    }

    public function update(Request $request)
    {
        User::where('id', $request->id)->update(['name'=>$request->name]);
        $user = User::find($request->id);
        return view('user/edit', ['user'=>$user]);
    }
}
