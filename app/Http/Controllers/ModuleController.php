<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Module as ModelClass;
use View;
use App\Models\Functions;

class ModuleController extends Controller
{
    protected $moduleRoute = 'modules';             //路由URL
    protected $moduleView = 'permission/module';    //视图路径
    protected $moduleTable = 'modules';
    protected $moduleName = '模块';
    protected $searchPlaceholder = '模块名';
    
    /**
     * 首页
     */
    public function index()
    {
        $objects = ModelClass::all();
        foreach($objects as $object)
        {
           $object->functions = Functions::where('cid', $object->id)->count(); 
           if($object->menu == 1)
           {
                $object->menu = '是';
           }
           else
           {
                $object->menu = '否';
           }
        } 
        return view($this->moduleView.'/index', ['objects'=>$objects, 'title'=>'模块列表']);
    }
    
    /**
     * 添加
     */
    public function create(){
        return view($this->moduleView.'/create', ['title'=>'添加模块']);
    } 

    /**
     * 添加回调
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:'.$this->moduleTable.'|max:255',
            'controller' => 'required|unique:'.$this->moduleTable.'|max:255',
        ]);

        $object = new ModelClass();
        $object->name          = $request->name;
        $object->controller    = $request->controller;
        $object->description   = $request->description;
        $object->font          = $request->font;
        $object->weight        = $request->weight;
        $object->menu          = $request->menu;
        $object->save();
        return redirect($this->moduleRoute.'/create')->with('message', '创建成功!');
    }
    
    /**
     * 显示
     */
    public function show($id=NULL){
        return view($this->route.'/show', ['title'=>'用户查看']);
    } 

    /**
     * 编辑
     */
    public function edit($id)
    {

        $object = ModelClass::find($id);
        return view($this->moduleView.'/edit', ['object'=>$object, 'title'=>$this->moduleName.'编辑']);
    }

    /**
     * 编辑回调
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:'.$this->moduleTable.',name,'.$id.'|max:255',
            'controller' => 'required|unique:'.$this->moduleTable.',controller, '.$id.'|max:255',
        ]);

        ModelClass::where('id', $id)->update([
            'name'=>$request->name,
            'controller'=>$request->controller,
            'description'=>$request->description,
            'font'=>$request->font,
            'weight'=>$request->weight,
            'menu'=>$request->menu,
        ]);
        return redirect($this->moduleRoute.'/'.$id.'/edit')->with('message', '编辑成功');
    }

    /**
     * 删除
     */
    public function destroy($id){
        $functions = Functions::where('cid', $id)->get();
        if(count($functions))
        {
            return redirect($this->moduleRoute)->with('message', '删除失败, 模块包含下属功能，无法删除!');
        }else{
            ModelClass::destroy($id);
            return redirect($this->moduleRoute)->with('message', '删除成功!');
        }
    }



}
