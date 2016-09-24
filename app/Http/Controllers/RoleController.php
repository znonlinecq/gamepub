<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use View;

class RoleController extends Controller
{ 
    private $searchPlaceholder = '角色名';       

    public function __construct()
    {
        parent::__construct();
        View::composer('role/*', function ($view) {
            $view->with('searchPlaceholder', $this->searchPlaceholder);

        }); 
    }


    public function index(){
        $roles = Role::All();
        return view('role/index', ['roles'=>$roles, 'title'=>'角色列表']);
    } 
    
    public function create(){
        return view('role/create', ['title'=>'添加角色']);
    } 

    public function store(Request $request){
        $role = new Role();
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return redirect('roles/create')->with('message', '创建成功!');
    }

    public function show(){
        return view('role/show', ['title'=>'角色查看']);
    } 

    public function edit($id){
        $role = Role::find($id);
        return view('role/edit', ['title'=>'角色编辑', 'role'=>$role]);
    } 

    public function update(Request $request, $id){
        Role::where('id', $id)->update([
            'name'=>$request->name,
            'description' => $request->description,
        ]);
        return redirect('roles/'.$id.'/edit')->with('message', '编辑成功!');

    } 

    public function destroy($id){
             Role::destroy($id);
        return redirect('roles')->with('message', '删除成功!');
    } 


}
