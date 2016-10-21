<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Functions;
use App\Role;
use App\Models\Permission;
use DB;
use App\Http\Requests;

class PermissionController extends Controller
{    
    protected $moduleRoute = 'permissions';             //路由URL
    protected $moduleView = 'permission/permission';    //视图路径
    protected $moduleTable = 'ad_permissions';
    protected $moduleName = '权限';
 
    public function create()
    {
        $permissionsHandle = array();
        $modules    = Module::All();
        $functions  = Functions::All();
        $roles      = Role::All();

        if(!count($roles) || !count($modules) || !count($functions)){
            $this->page_empty();    
        }
        
        foreach($modules as $module)
        {
            $functions = Functions::where('cid', $module->id)
                ->orderBy('weight', 'ASC')
                ->get();
            $module->functions = $functions;
            $modulesHandle[] = $module;
        }
            
        return view($this->moduleView.'/create', ['title'=>'角色授权', 'objects'=>$modulesHandle, 'roles'=>$roles, 'moduleRoute'=>$this->moduleRoute]) ;    
    }

    public function store(Request $request)
    {
        $deleted = DB::delete('delete from '.$this->moduleTable);
        
        $input = $request->all();
        $permissions = $input['permissions'];
        if(count($permissions))
        {
            foreach($permissions as $permission)
            {
                $permissionArray = explode('-', $permission);
                $rid = $permissionArray[0];
                $mid = $permissionArray[1];
                $fid = $permissionArray[2];

                $object = new Permission();
                $object->rid = $rid;
                $object->mid = $mid;
                $object->fid = $fid;
                $object->save();
            }
            return redirect($this->moduleRoute.'/create')->with('message', '授权成功!');
 
        }
    }
}
