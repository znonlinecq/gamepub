<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::all();
        if($users)
        {
            foreach($users as $user)
            {
                if($user->status == 1)
                {
                    $user->status = '正常';
                }elseif($user->status == 2)
                {
                    $user->status = '停止';
                }else{
                    $user->status = '正常';
                }

                if($user->rid)
                {
                    $role = Role::find($user->rid);
                    $user->role = $role->name;
                }
                else
                {
                    $user->role = '未分配';
                }
            }
        }
        return view('user/index', ['users'=>$users, 'title'=>'用户列表']);
    }

    public function create(){
        $roles = Role::All();
        return view('user/create', ['roles'=>$roles, 'title'=>'添加用户']);
    } 

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'confirmed',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->status   = $request->status;
        $user->rid      = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect('users/create')->with('message', '创建成功!');
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
