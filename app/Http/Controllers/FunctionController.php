<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Functions as ModelClass;
use View;
use App\Models\Module;

class FunctionController extends Controller
{
    private $moduleRoute = 'functions';             //路由URL
    private $moduleView = 'permission/function';    //视图路径
    private $moduleTable = 'ad_functions';
    private $moduleName = '功能';
    private $searchPlaceholder = '功能名';
   
    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName);
            $view->with('searchPlaceholder', $this->searchPlaceholder);

        }); 
    }

    /**
     * 首页
     */
    public function index($cid=NULL)
    {
        if($cid)
        {
            $objects = ModelClass::where('cid',$cid)->orderBy('created', 'desc')->get();
        }
        else    
        {
            $objects = ModelClass::all();
        }
        foreach($objects as $object)
        {
            $controller = Module::find($object->cid);
            $object->controller = $controller->name;

            if($object->menu)
            {
                $object->menu = '是';
            }else{
                $object->menu = '否';
            }
        }
        return view($this->moduleView.'/index', ['objects'=>$objects, 'title'=>$this->moduleName.'列表', 'cid'=>$cid]);
    }
    
    /**
     * 添加
     */
    public function create($cid=NULL){
        $controllers = Module::All();
        return view($this->moduleView.'/create', [
            'title'=>'添加'.$this->moduleName, 
            'cid'=>$cid,
            'controllers' => $controllers,]
        );
    } 

    /**
     * 添加回调
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'cid' => 'required',
            'method' => 'required|max:255',
        ]);

        $object = new ModelClass();
        $object->name          = $request->name;
        $object->cid           = $request->cid;
        $object->method        = $request->method;
        $object->description   = $request->description;
        $object->weight        = $request->weight;
        $object->menu          = $request->menu;
        $object->save();
        return redirect($this->moduleRoute.'/create/'.$request->cid)->with('message', '创建成功!');
    }
    
    /**
     * 显示
     */
    public function show(){
        return view($this->moduleView.'/show', ['title'=>$this->moduleName.'查看']);
    } 

    /**
     * 编辑
     */
    public function edit($id)
    {
        $object = ModelClass::find($id);
        $controllers = Module::ALL();
        return view($this->moduleView.'/edit', [
            'object'=>$object, 
            'title'=>$this->moduleName.'编辑', 
            'controllers'=>$controllers,
        ]);
    }

    /**
     * 编辑回调
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cid' => 'required',
            'method' => 'required|max:255',
        ]);

        ModelClass::where('id', $id)->update([
            'name'=>$request->name,
            'cid'=>$request->cid,
            'method'=>$request->method,
            'description'=>$request->description,
            'weight'=>$request->weight,
            'menu'=>$request->menu,
        ]);
        return redirect($this->moduleRoute.'/'.$id.'/edit')->with('message', '编辑成功');
    }

    /**
     * 删除
     */
    public function destroy($id){
        $Model = ModelClass::find($id);
        $cid = $Model->cid;
        ModelClass::destroy($id);
        return redirect($this->moduleRoute.'/'.$cid)->with('message', '删除成功!');
    }


}
