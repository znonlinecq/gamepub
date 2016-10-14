<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Auth;
use App\Models\Log;

class OrderController extends Controller
{
    private $moduleRoute    = 'orders';      //路由URL
    private $moduleView     = 'order';       //视图路径
    private $moduleTable    = '';
    private $moduleName     = '订单';
    private $moduleIndexAjax = '/orders/index_ajax';
    private $searchPlaceholder = '订单号';   
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
        return view($this->moduleView.'/index', ['title'=>'充值订单总会']);
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
            0=>'apkid', 
            7=>'Uploaddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $conditions[] = " Apkname like '%{$searchValue}%' ";
        }
 
        if($from && $to)
        {
            $conditions[] = " (Uploaddate BETWEEN  '{$from}' AND '{$to}') ";
        }
        if(count($conditions))
        {
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $conditions);
        }

        //$countResult = DB::select($sql);
        //$total  = count($countResult);
        $total = 0;

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        //$results = DB::select($sql);
        //$statistic = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_paltform_dataall_sel(?,?,?)",array($gid, $start, $end));
//        $test = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_guild_gamerecharge_byguildid_sel(?,?,?)",array('1970-01-01', '2016-10-10', 0));


        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count(array()))
        {

            foreach($results as $result)
            {
                $game       = Game::where('Gameid', $result->Gameid)->get();
                if(count($game))
                {
                    $gameName = $game[0]->Gamename;
                }
                else
                {
                    $gameName = '未定义';
                }
            
                $developer  = Developer::where('cpid', $result->Cpid)->get();
                if(count($developer))
                {
                    $developerName = $developer[0]->username;
                }
                else
                {
                    $developerName = '未定义';
                }

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
                $object[] = $gameName;
                $object[] = $developerName;
                $object[] = $type;
                $object[] = $result->Opendowndate;
                $object[] = $result->OpeServndate;
                $object[] = $result->Uploaddate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->apkid).'">审核</a>';

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


    public function show($id)
    {
        
//        $object     = Apk::find($id); 
//        $game       = Game::where('Gameid', $object->Gameid)->get();
//        if(count($game))
//        {
//            $object->gameName = $game[0]->Gamename;
//        }
//        else
//        {
//            $object->gameName = '未定义';
//        }
//
//        $developer  = Developer::where('cpid', $object->Cpid)->get();
//        if(count($developer))
//        {
//            $object->developerName = $developer[0]->username;
//        }
//        else
//        {
//            $object->developerName = '未定义';
//        }
//
//
//        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
//        if($object->status == 0)
//        {
//            $object->status = '待审核';
//        }elseif($object->status == 1)
//        {
//            $object->status = '通过';
//        }elseif($object->status == 2)
//        {
//            $object->status = '驳回';
//        }
//
//        if($object->Isself == 0)
//        {
//            $object->Isself = '独家代理';
//        }else{
//            $object->Isself = '自主研发';
//        }
//        if($object->Apktypeid == 0)
//        {
//            $object->type = '新品';
//        }elseif($object->Apktypeid == 1){
//            $object->type = '更新';
//        }
        $object = array();

        return view($this->moduleView.'/show', ['object'=>$object, 'title'=>'充值订单汇总详情']);
    
    }
    
    public function payment_orders()
    {
        return view($this->moduleView.'/payment_orders', ['title'=>'消费订单总会']);
    }

    public function payment_orders_ajax(Request $request)
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
            0=>'apkid', 
            7=>'Uploaddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $conditions[] = " Apkname like '%{$searchValue}%' ";
        }
 
        if($from && $to)
        {
            $conditions[] = " (Uploaddate BETWEEN  '{$from}' AND '{$to}') ";
        }
        if(count($conditions))
        {
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $conditions);
        }

        //$countResult = DB::select($sql);
        //$total  = count($countResult);
        $total = 0;

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        //$results = DB::select($sql);

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count(array()))
        {

            foreach($results as $result)
            {
                $game       = Game::where('Gameid', $result->Gameid)->get();
                if(count($game))
                {
                    $gameName = $game[0]->Gamename;
                }
                else
                {
                    $gameName = '未定义';
                }
            
                $developer  = Developer::where('cpid', $result->Cpid)->get();
                if(count($developer))
                {
                    $developerName = $developer[0]->username;
                }
                else
                {
                    $developerName = '未定义';
                }

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
                $object[] = $gameName;
                $object[] = $developerName;
                $object[] = $type;
                $object[] = $result->Opendowndate;
                $object[] = $result->OpeServndate;
                $object[] = $result->Uploaddate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->apkid).'">审核</a>';

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


    public function payment_order_show($id)
    {
        
    }

    public function give_orders()
    {
        return view($this->moduleView.'/give_orders', ['title'=>'转增订单总会']);
    }

    public function give_orders_ajax(Request $request)
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
            0=>'apkid', 
            7=>'Uploaddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $conditions[] = " Apkname like '%{$searchValue}%' ";
        }
 
        if($from && $to)
        {
            $conditions[] = " (Uploaddate BETWEEN  '{$from}' AND '{$to}') ";
        }
        if(count($conditions))
        {
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $conditions);
        }

        //$countResult = DB::select($sql);
        //$total  = count($countResult);
        $total = 0;

        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        //$results = DB::select($sql);

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count(array()))
        {

            foreach($results as $result)
            {
                $game       = Game::where('Gameid', $result->Gameid)->get();
                if(count($game))
                {
                    $gameName = $game[0]->Gamename;
                }
                else
                {
                    $gameName = '未定义';
                }
            
                $developer  = Developer::where('cpid', $result->Cpid)->get();
                if(count($developer))
                {
                    $developerName = $developer[0]->username;
                }
                else
                {
                    $developerName = '未定义';
                }

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
                $object[] = $gameName;
                $object[] = $developerName;
                $object[] = $type;
                $object[] = $result->Opendowndate;
                $object[] = $result->OpeServndate;
                $object[] = $result->Uploaddate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->apkid).'">审核</a>';

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


    public function give_order_show($id)
    {
        
    }


}
