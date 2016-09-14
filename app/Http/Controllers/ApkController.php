<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Apk;
use Auth;
use App\Models\Log;


class ApkController extends Controller
{
    private $moduleRoute    = 'apks';      //路由URL
    private $moduleView     = 'apk';       //视图路径
    private $moduleTable    = 'game_apkinfo';
    private $moduleName     = '游戏包';
    
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
        return view($this->moduleView.'/index', ['title'=>$this->moduleName.'审核']);
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
            0=>'apkid', 
            7=>'Uploaddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $sql .= " WHERE Apkname like '%{$searchValue}%' ";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $sqlCount .= " WHERE Apkname like '%{$searchValue}%' ";
        }

        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;

        foreach($results as $result)
        {
            $game       = Apk::find($result->Gameid)->game;
            $developer  = Apk::find($result->Cpid)->developer;

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

            if($result->Apktypeid == 0)
            {
                $type = '新品';
            }elseif($result->Apktypeid == 1){
                $type = '更新';
            }

            $object = array();
            $object[] = $result->apkid;
            $object[] = $result->Apkname;
            $object[] = $game->Gamename;
            $object[] = $developer->username;
            $object[] = $type;
            $object[] = $result->Opendowndate;
            $object[] = $result->OpeServndate;
            $object[] = $result->Uploaddate;
            $object[] = $status;
            $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->apkid).'">审核</a>';

            $objects['data'][] = $object;
        }

        return json_encode($objects);
    }


    public function audit_form($id)
    {
        
        $object     = Apk::find($id); 
        $developer  = Apk::find($object->Cpid)->developer;
        $game       = Apk::find($object->Gameid)->game;
        $object->developer  = $developer;
        $object->game       = $game;

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
        if($object->Apktypeid == 0)
        {
            $object->type = '新品';
        }elseif($result->Apktypeid == 1){
            $object->type = '更新';
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

        DB::update("UPDATE {$this->moduleTable} set Checkuserid={$user->id}, status={$status}, checkdate='{$updated}'  where apkid = {$id}");

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
