<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use View;
use DB;
use Auth;

class UserController extends Controller
{
    private $moduleRoute = 'users';             //路由URL
    private $moduleView = 'user';    //视图路径
    private $moduleTable = 'users';
    private $moduleName = '用户';
    private $searchPlaceholder = '用户名';       

    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName); 
            $view->with('searchPlaceholder', $this->searchPlaceholder);

        }); 
    }


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
        $currentUser = Auth::User();
        $rid = $currentUser->rid;
        $roles = Role::All();
        if($rid != 1)
        {
            unset($roles[0]);
        }
        
        return view('user/create', ['roles'=>$roles, 'title'=>'添加用户']);
    } 

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:'.$this->moduleTable.'|max:255',
            'email' => 'required|unique:'.$this->moduleTable.'|max:255',
            'password' => 'confirmed',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->status   = $request->status;
        $user->rid      = $request->role;
        $user->password = bcrypt($request->password);
        $user->created = time();
        $user->updated = time();
        $user->save();
        return redirect('users/create')->with('message', '创建成功!');
    }

    public function show(){
        return view('user/show', ['title'=>'用户查看']);
    } 

    public function edit($id)
    {
        $currentUser = Auth::User();
        $rid = $currentUser->rid;
        $user = User::find($id);
        $roles = Role::All();
        if($rid != 1)
        {
            unset($roles[0]);
        }
        return view('user/edit', ['user'=>$user, 'roles'=>$roles,'title'=>'用户编辑']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:'.$this->moduleTable.',name,'.$id.'|max:255',
            'email' => 'required|unique:'.$this->moduleTable.',email, '.$id.'|max:255',
            'password' => 'confirmed',
        ]);
        if(!empty($request->password))
        {
            $update = array(
                'name'=>$request->name,
                'rid'=>$request->role,
                'status'=>$request->status,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                'updated' => time(),
            ); 
        }else{
             $update = array(
                'name'=>$request->name,
                'rid'=>$request->role,
                'status'=>$request->status,
                'email'=>$request->email,
                'updated' => time(),
            ); 
        }
        User::where('id', $id)->update($update);
        return redirect('users/'.$id.'/edit')->with('message', '编辑成功');
    }

    public function destroy($id){
        if($id == 1)
        {
            return redirect('users')->with('message', '超级管理员无法删除!');
        }
        User::destroy($id);
        return redirect('users')->with('message', '删除成功!');
    }

}
