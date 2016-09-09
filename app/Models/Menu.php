<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\Functions;
use Request;
use Auth;
use App\Models\Permission;

class Menu extends Model
{

    static public function menuLoad(){
        $requestPath = Request::path();
        $requestPathArray = explode('/', $requestPath);
        $requestPathFirst = $requestPathArray[0];
        $menus = array(); 
        $user = Auth::user();
        $rid = $user->rid;
        $modules = Module::where('menu', '1')->orderBy('weight', 'ASC')->get();
        if(count($modules))
        {
            foreach($modules as $module)
            {
                $mid = $module->id;
                $functions = Functions::where('cid', $module->id)
                    ->where('menu', '1')
                    ->orderBy('weight', 'ASC')
                    ->get();
                
                $modulePath = str_replace('Controller', '', $module->controller);
                $modulePath = strtolower($modulePath).'s';
                $module->path = $modulePath;

                if($modulePath == $requestPathFirst)
                {
                    $module->active = true;
                }
                else{
                    $module->active = false;
                }
                
                $functionsHandle = array();
                foreach($functions as $function)
                {
                    $fid = $function->id;

                    if($function->method == 'index')
                    {
                        $function->path = $modulePath;
                    }    
                    else{
                        $function->path = $modulePath.'/'.$function->method;
                    }

                    if($requestPath == $function->path)
                    {
                        $function->active = true;
                    }
                    else{
                        $function->active = false;
                    }
                    if(Permission::check_exist($rid, $mid, $fid))
                    {
                        $functionsHandle[] = $function;
                    }
                }
                $module->functions = $functionsHandle;
                if(Permission::check_module($rid, $mid))
                {
                    $menus[] = $module; 
                }
            }
        }else{
            $mods = new \stdClass();
            $mods->name = '模块管理';
            $mods->path = 'modules';
            $mods->active = false;

            $functions = array();
            $function = new \stdClass();
            $function->name = '模块列表';
            $function->path = 'modules';
            $function->active = false;
            $functions[1] = $function;
            $function = new \stdClass();
            $function->name = '模块添加';
            $function->path = 'modules/create';
            $function->active = false;
            $functions[2] = $function;

            $mods->functions = $functions;

            $menus[] = $mods;
        }
        return $menus;
    }
}
