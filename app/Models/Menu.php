<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;
use App\Models\Functions;
use Request;

class Menu extends Model
{

    static public function menuLoad(){
        $requestPath = Request::path();
        $requestPathArray = explode('/', $requestPath);
        $requestPathFirst = $requestPathArray[0];
        $menus = array(); 

        $modules = Module::where('menu', '1')->orderBy('weight', 'ASC')->get();
        
        if(count($modules))
        {
            foreach($modules as $module)
            {
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
                    $functionsHandle[] = $function;
                }
                $module->functions = $functionsHandle;
                $menus[] = $module; 
            }
        }else{
            $modules = new \stdClass();
            $module->name = '模块管理';
            $mdoule->path = 'modules';
            $module->active = false;

            $functions = array();
            $function = new \stdClass();
            $function->name = '模块列表';
            $function->path = 'modules';
            $function->active = false;
            $functions[] = $function;
            $function->name = '模块添加';
            $function->path = 'modules/create';
            $function->active = false;
            $functions[] = $function;

            $module->function = $functions;

            $menus[] = $modules;
        }
        return $menus;
    }
}
