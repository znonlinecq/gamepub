<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use View;

class RoleController extends Controller
{ 
    protected $searchPlaceHolder = '角色名';       
    protected $moduleView = 'role';

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
        $role->created = time();
        $role->updated = time();
        $role->save();
        return redirect('roles/create')->with('message', '创建成功!');
    }

    public function show($id=NULL){
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
            'updated' => time(),
        ]);
        return redirect('roles/'.$id.'/edit')->with('message', '编辑成功!');

    } 

    public function destroy($id){
        if($id == 1)
        {
            return redirect('roles')->with('message', '超级管理员角色无法删除!');
        }
        if($id == 2)
        {
            return redirect('roles')->with('message', '管理员角色无法删除!');
        }
        if($id == 3)
        {
            return redirect('roles')->with('message', '财务角色无法删除!');
        }
 
        Role::destroy($id);
        return redirect('roles')->with('message', '删除成功!');
    } 


}
