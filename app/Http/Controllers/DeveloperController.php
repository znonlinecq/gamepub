<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Developer;
use Auth;
use App\Models\Log;

class DeveloperController extends Controller
{
    private $moduleRoute = 'developers';  //路由URL
    private $moduleView = 'developer';    //视图路径
    private $moduleTable = 'game_cpinfo';
    private $moduleName = '开发者';
    
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
        return view($this->moduleView.'/index', ['title'=>'开发者审核']);
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
            0=>'cpid', 
            7=>'adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from {$this->moduleTable} ";
        if($searchValue)
        {
            $sql .= " WHERE compname like '%{$searchValue}%' ";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $sqlCount .= " WHERE compname like '%{$searchValue}%' ";
        }
        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;

        foreach($results as $result)
        {
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
            $object[] = $result->cpid;
            $object[] = $result->username;
            $object[] = $result->compname;
            $object[] = $result->compweb;
            $object[] = $result->compaddr;
            $object[] = $result->certificateno;
            $object[] = $result->taxno;
            $object[] = $result->adddate;
            $object[] = $status;
            $object[] = '<a href="'.url('developers/audit_form/'.$result->cpid).'">审核</a>';

            $objects['data'][] = $object;
        }

        return json_encode($objects);
    }


    public function audit_form($id)
    {
        
        $object = Developer::find($id);
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

        return view($this->moduleView.'/audit_form', ['object'=>$object, 'title'=>'开发者审核']);
    
    }

    public function audit_form_submit(Request $request)
    {
        $user = Auth::user();

        $cpid = $request->cpid;
        $submit = $request->submit;
        $description = $request->description;
        
        if($submit == 'yes')
        {
            $auditStatus = 1;
        }else{
            $auditStatus = 2;
        }
        $summary = $description;
        $updated = date('Y-m-d H:i:s', time());

        DB::update("update {$this->moduleTable} set checkuserid={$user->id}, status={$auditStatus}, checkdate='{$updated}'  where cpid = {$cpid}");

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = $cpid;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }

}
