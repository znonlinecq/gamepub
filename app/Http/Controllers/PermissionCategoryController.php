<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\PermissionCategory as Category;

class PermissionCategoryController extends Controller
{
    public function index()
    {
        $categorys = Category::all();
        return view('permission/category/index', ['categorys'=>$categorys, 'title'=>'模块列表']);
    }

    public function create(){
        return view('permission/category/create', ['title'=>'添加模块']);
    } 

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:permission_categorys|max:255',
            'controller' => 'required|unique:permission_categorys|max:255',
        ]);

        $category = new Category();
        $category->name          = $request->name;
        $category->controller    = $request->controller;
        $category->description   = $request->description;
        $category->weight        = $request->weight;
        $category->menu          = $request->menu;
        $category->save();
        return redirect('permission_categorys/create')->with('message', '创建成功!');
    }

    public function show(){
        return view('user/show', ['title'=>'用户查看']);
    } 

    public function edit($id)
    {

        $user = User::find($id);
        $roles = Role::All();
        return view('user/edit', ['user'=>$user, 'roles'=>$roles,'title'=>'用户编辑']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:users,name,'.$id.'|max:255',
            'email' => 'required|unique:users,email, '.$id.'|max:255',
            'password' => 'confirmed',
        ]);

        User::where('id', $id)->update([
            'name'=>$request->name,
            'rid'=>$request->role,
            'status'=>$request->status,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        return redirect('users/'.$id.'/edit')->with('message', '编辑成功');
    }

    public function destroy($id){
        User::destroy($id);
        return redirect('users')->with('message', '删除成功!');
    }


}
