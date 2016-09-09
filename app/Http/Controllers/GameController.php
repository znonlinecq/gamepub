<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Game;
use Auth;
use App\Models\Log;


class GameController extends Controller
{
    private $moduleRoute = 'games';  //路由URL
    private $moduleView = 'game';    //视图路径
    private $moduleTable = 'game_info';
    private $moduleName = '游戏';
    
    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName);
        }); 
    }

    public function index()
    {
        return view($this->moduleView.'/index', ['title'=>'游戏审核']);
    }

    public function index_ajax(Request $request)
    {
        $requests = $request->all();
        $draw       = $requests['draw'];
        $columns    = $requests['columns'];
        $start      = $requests['start'];
        $length     = $requests['length'];
        $search     = $requests['search'];
        $searchValue     = $search['value'];
        
        $order      = $requests['order'];
        $orderNumber = $order[0]['column'];
        $orderDir    = $order[0]['dir'];

        $orderColumns = array(
            0=>'id', 
            7=>'adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from {$this->moduleTable} ";
        if($searchValue)
        {
            $sql .= " WHERE Gamename like '%{$searchValue}%' ";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $sqlCount .= " WHERE Gamename like '%{$searchValue}%' ";
        }
        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;

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


}
