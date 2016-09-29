<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Game;
use Auth;
use App\Models\Log;
use App\Models\GameType;
use App\Models\GameClass;

class GameController extends Controller
{
    private $moduleRoute = 'games';  //路由URL
    private $moduleView = 'game';    //视图路径
    private $moduleTable = 'game_info';
    private $moduleName = '游戏';
    private $moduleIndexAjax = '/games/index_ajax';
    private $searchPlaceholder = '游戏名';

    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName); 
            $view->with('moduleIndexAjax', $this->moduleIndexAjax);
            $view->with('searchPlaceholder', $this->searchPlaceholder);
        }); 
    }

    public function index()
    {
        return view($this->moduleView.'/index', ['title'=>'游戏审核']);
    }

    public function index_ajax(Request $request)
    {
        $requests       = $request->all();
        $draw           = $requests['draw'];
        $columns        = $requests['columns'];
        $start          = $requests['start'];
        $length         = $requests['length'];
        $search         = $requests['search'];
        $searchValue    = $search['value']; 
        $order          = $requests['order'];
        $orderNumber    = $order[0]['column'];
        $orderDir       = $order[0]['dir'];
        $conditions     = array();

        if(!empty($requests['dateRange']))
        {
            $dateRange      = $requests['dateRange'];
            $dateRange      = explode('-', $dateRange);
            $from           = trim($dateRange[0]);
            $to             = trim($dateRange[1]);
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

        $orderColumns = array(
            0=>'id', 
            7=>'adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from {$this->moduleTable} ";

        if($searchValue)
        {
            $conditions[] .= "Gamename like '%{$searchValue}%' ";
        }        
        if($from && $to)
        {
            $conditions[] = " (Adddate BETWEEN  '{$from}' AND '{$to}') ";
        }
        if(count($conditions))
        {
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $conditions);
        }

        $countResult = DB::select($sql);
        $total  = count($countResult);

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count($results))
        {

            foreach($results as $result)
            {
                $developer = Game::find($result->Cpid)->developer;

                if($result->status == 0)
                {
                    $status = '待审核';
                }elseif($result->status == 1)
                {
                    $status = '通过';
                }elseif($result->status == 2)
                {
                    $status = '驳回';
                }
                $object = array();
                $object[] = $result->id;
                $object[] = $result->Gamename;
                $object[] = $developer->username;
                $object[] = $result->Typeid;
                $object[] = $result->Version;
                $object[] = $result->Casenumber;
                $object[] = $result->Onlinedate;
                $object[] = $result->Adddate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->id).'">审核</a>';

                $objects['data'][] = $object;
            }
        }
        else
        {
            for($i=0; $i<10; $i++)
            {
                if($i == 0)
                {
                    $array[] = '空';
                }
                else
                {
                    $array[] = '';
                }
            }
            $objects['data'][] = $array;
        }

        return json_encode($objects);
    }


    public function audit_form($id)
    {
        
        $object = Game::find($id); 
        $developer = Game::find($object->Cpid)->developer;
        $object->developer = $developer;

        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        if($object->status == 0)
        {
            $object->status = '待审核';
        }elseif($object->status == 1)
        {
            $object->status = '通过';
        }elseif($object->status == 2)
        {
            $object->status = '驳回';
        }

        if($object->Isself == 0)
        {
            $object->Isself = '独家代理';
        }else{
            $object->Isself = '自主研发';
        }

        return view($this->moduleView.'/audit_form', ['object'=>$object, 'title'=>$this->moduleName.'审核']);
    
    }

    public function audit_form_submit(Request $request)
    {
        $user = Auth::user();

        $id = $request->id;
        $submit = $request->submit;
        $description = $request->description;
        
        if($submit == 'yes')
        {
            $status = 1;
        }else{
            $status = 2;
        }
        $summary = $description;
        $updated = date('Y-m-d H:i:s', time());

        DB::update("UPDATE {$this->moduleTable} set Checkuserid={$user->id}, status={$status}, checkdate='{$updated}'  where id = {$id}");

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = $id;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }

    public function types()
    {        
        return view($this->moduleView.'/type', ['title'=>$this->moduleName.'类型']);
    }

    public function types_ajax(Request $request)
    {
        $requests       = $request->all();
        $draw           = $requests['draw'];
        $columns        = $requests['columns'];
        $start          = $requests['start'];
        $length         = $requests['length'];
        $search         = $requests['search'];
        $searchValue    = $search['value'];
        $order          = $requests['order'];
        $orderNumber    = $order[0]['column'];
        $orderDir       = $order[0]['dir'];
        $conditions     = array();


        $orderColumns = array(
            3=>'ordernum',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_type ";

        $countResult = DB::select($sql);
        $total  = count($countResult);

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
       // $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count($results))
        {
            foreach($results as $result)
            {
                $ordernum = $result->ordernum;
                $op = '<a href="'.url('games/types_edit/'.$result->Typeid).'">编辑</a>';               
                $op .= '&nbsp;&nbsp; |&nbsp;&nbsp; ';
                $op .= '<a href="'.url('games/types_delete/'.$result->Typeid).'" >删除</a>';
                
                $subOp = '<a href="'.url('games/types/classes/'.$result->Typeid).'" >查看</a>';
                $subOp .= '&nbsp;&nbsp; | &nbsp;&nbsp;';
                $subOp .= '<a href="'.url('games/types/classes_add/'.$result->Typeid).'">添加分类</a>';
                $count = GameClass::where('Typeid', $result->Typeid)->count(); 
                $object = array();
                $object[] = $result->Typeid;
                $object[] = $result->Typename;
                $object[] = $count;
                $object[] = $ordernum;
                $object[] = $op;
                $object[] = $subOp;
                
                $objects['data'][] = $object;
            }
        }
        else
        {
            for($i=0; $i<4; $i++)
            {
                if($i == 0)
                {
                    $array[] = '空';
                }
                else
                {
                    $array[] = '';
                }
            }
            $objects['data'][] = $array;
        }
        return json_encode($objects);
    }

    public function types_add()
    {
        return View($this->moduleView.'/type_create')->with(['title'=>'添加游戏类型']);
    }
    public function types_add_submit(Request $request)
    {
        $user       = Auth::User();
        $requests   = $request->all();
        $name       = $requests['name'];
        $ordernum   = $requests['weight'];
        $object     = new GameType();
        $object->Typename = $name;
        $object->Adminid = $user->id;
        $object->ordernum = $ordernum;
        $object->save();
        return redirect($this->moduleRoute.'/types_add')->with('message', '添加成功!');
    }

    public function types_edit($id)
    {
        $object = GameType::find($id);
        return View($this->moduleView.'/type_edit')->with(['title'=>'编辑游戏类型', 'object'=>$object]);
    }

    public function types_edit_submit(Request $request)
    {
        $requests   = $request->all();
        $tid        = $requests['tid'];
        $name       = $requests['name'];
        $weight     = $requests['weight'];
        $object     = GameType::find($tid);
        $object->Typename   = $name;
        $object->ordernum   = $weight;
        $object->save();        
        return redirect($this->moduleRoute.'/types_edit/'.$tid)->with('message', '编辑成功!');
    }

    public function types_delete($id)
    {
        $class = GameClass::where('Typeid', $id)->get();
        if(count($class))
        {
            return redirect($this->moduleRoute.'/types')->with('message', '有子类，无法删除!');
        }
        else
        {
            GameType::destroy($id); 
            return redirect($this->moduleRoute.'/types')->with('message', '删除成功!');
        }
    }
   
    public function types_classes($id=NULL)
    {   
        if(empty($id))
        {
            $id = 0;
        } 
        return view($this->moduleView.'/class', ['title'=>$this->moduleName.'分类', 'tid'=>$id]);
    }

    public function types_classes_ajax(Request $request)
    {
        $requests       = $request->all();
        $draw           = $requests['draw'];
        $columns        = $requests['columns'];
        $start          = $requests['start'];
        $length         = $requests['length'];
        $search         = $requests['search'];
        $searchValue    = $search['value'];
        $order          = $requests['order'];
        $orderNumber    = $order[0]['column'];
        $orderDir       = $order[0]['dir'];
        $tid            = $requests['tid'];
        $conditions     = array();

        $orderColumns = array(
            3=>'ordernum',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_class ";
        if($tid)
        {
            $sql .= " WHERE Typeid = {$tid} ";
        }
        $countResult = DB::select($sql);
        $total  = count($countResult);

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
       // $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count($results))
        {
            foreach($results as $result)
            {
                $type = GameType::find($result->Typeid);
                if(count($type))
                {
                    $typeName = $type->Typename;
                }
                else
                {
                    $typeName = '';
                }
                $object = array();
                $object[] = $result->Classid;
                $object[] = $result->Classname;
                $object[] = $typeName;
                $ordernum = $result->ordernum;
                $object[] = $ordernum;
                $op = '<a href="'.url('games/types/classes_edit/'.$result->Classid).'">编辑</a>';               
                $op .= '&nbsp;&nbsp; |&nbsp;&nbsp; ';
                $op .= '<a href="'.url('games/types/classes_delete/'.$result->Classid).'" >删除</a>';
                $object[] = $op;

                $objects['data'][] = $object;
            }
        }
        else
        {
            for($i=0; $i<5; $i++)
            {
                if($i == 0)
                {
                    $array[] = '空';
                }
                else
                {
                    $array[] = '';
                }
            }
            $objects['data'][] = $array;
        }
        return json_encode($objects);
    }

    public function types_classes_add($id=NULL)
    {
        $types = GameType::all();
        return View($this->moduleView.'/class_create')->with(['title'=>'添加游戏分类', 'tid'=>$id, 'types'=>$types]);
    }

    public function types_classes_add_submit(Request $request)
    {
        $user       = Auth::User();
        $requests   = $request->all();
        $name       = $requests['name'];
        $tid        = $requests['tid'];
        $ordernum   = $requests['weight'];
        $object     = new GameClass();
        $object->Classname = $name;
        $object->Adminid  = $user->id;
        $object->Typeid   = $tid;
        $object->ordernum = $ordernum;
        $object->save();
        return redirect($this->moduleRoute.'/types/classes_add/'.$tid)->with('message', '添加成功!');
    }

    public function types_classes_edit($id)
    {
        $object = GameClass::find($id);
        $type   = GameType::all();
        return View($this->moduleView.'/class_edit')->with(['title'=>'编辑游戏分类', 'object'=>$object, 'types'=>$type, 'tid'=>$object->Typeid]);
    }

    public function types_classes_edit_submit(Request $request)
    {
        $requests   = $request->all();
        $tid        = $requests['tid'];
        $cid        = $requests['cid'];
        $name       = $requests['name'];
        $weight     = $requests['weight'];
        $object     = GameClass::find($cid);
        $object->Classname   = $name;
        $object->ordernum   = $weight;
        $object->Typeid     = $tid;
        $object->save();        
        return redirect($this->moduleRoute.'/types/classes_edit/'.$cid)->with('message', '编辑成功!');
    }

    public function types_classes_delete($id)
    {
        $class = GameClass::find($id);
        $tid = $class->Typeid;
        GameClass::destroy($id); 
        return redirect($this->moduleRoute.'/types/classes/'.$tid)->with('message', '删除成功!');
    }

}