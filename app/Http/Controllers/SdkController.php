<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Sdk;
use Auth;
use App\Models\Log;


class SdkController extends Controller
{
    protected $moduleRoute    = 'apks/sdks';      //路由URL
    protected $moduleView     = 'sdk';       //视图路径
    protected $moduleTable    = 'game_sdkinfo';
    protected $moduleName     = 'SDK包';
    protected $moduleIndexAjax = '/apks/sdks/index_ajax';
    protected $searchPlaceholder = '游戏名称';   

    public function index()
    {
        return view($this->moduleView.'/index', ['title'=>$this->moduleName.'审核']);
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
            0=>'s.id', 
            7=>'s.Adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT s.*, g.Gamename, c.username FROM {$this->moduleTable} as s LEFT JOIN game_info as g ON s.gameid = g.id LEFT JOIN game_cpinfo as c ON s.cpID = c.cpid ";
        if($searchValue)
        {
            $conditions[] = " g.Gamename like '%{$searchValue}%' ";
        }
 
        if($from && $to)
        {
            $conditions[] = " (s.Adddate BETWEEN  '{$from}' AND '{$to}') ";
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
                $object[] = $result->username;
                $object[] = $result->Gamename;
                $object[] = $result->Apikey;
                $object[] = $result->Testpayturl;
                $object[] = $result->Payturl;
                $object[] = $result->Adddate;
                $object[] = $result->Lastupdate;
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
        $sql = " SELECT s.*, g.Gamename, c.username FROM {$this->moduleTable} as s LEFT JOIN game_info as g ON s.gameid = g.id LEFT JOIN game_cpinfo as c ON s.cpID = c.cpid WHERE s.id = {$id}";
        $object = DB::select($sql);
        $object = $object[0];
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

        DB::update("UPDATE {$this->moduleTable} set Checkuserid={$user->id}, status={$status}, Lastupdate='{$updated}'  where id = {$id}");

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = 'SDK包审核';
        $params['object'] = $id;
        $params['content'] = $description.' - '.$submit;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }

}
