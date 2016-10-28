<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use Auth;
use App\Models\Log;
use App\Models\Game;
use App\Models\Statistic\OrderRecharge;
use App\Models\Statistic\OrderPay;
use App\Models\Statistic\OrderGive;

class OrderController extends Controller
{
    protected $moduleRoute    = 'orders';      //路由URL
    protected $moduleView     = 'order';       //视图路径
    protected $moduleTable    = '';
    protected $moduleName     = '订单';
    protected $moduleIndexAjax = '/orders/index_ajax';
    protected $searchPlaceholder = '订单号';   

    public function index()
    {
        $games = Game::all();
        if(count($games))
        {
            DB::delete("truncate ad_statistic_order_recharges");
            foreach($games as $game)
            {
                $gameId         = $game->Gameid;        //ID
                $gameType       = 0;                    //游戏类型 game_info里的typeid
                $payType        = 0;                    //支付类型
                $orderStatus    = 0;                    //订单状态 9=成功, 其它失败
                $userType       = 1;                    //推广员类型, 1=公会
                $start          = '1970-01-01';         // 1970-01-01
                $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
                $searchType     = 1;                    //1=充值，2=消费
                $orders = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_ordermanager_sel(?,?,?,?,?,?,?,?)",array($gameId, $gameType, $payType, $orderStatus, $userType, $start, $end,$searchType));
                
                if(count($orders))
                {
                    //DB::table('statistic_order_recharges')->delete();
                    foreach($orders as $order)
                    {
                        $object = new OrderRecharge();
                        $object->order_id       = $order->systemorder;
                        $object->createdate     = $order->CreateDate;
                        $object->gamename       = $order->Gamename;
                        $object->type           = $order->usertype;
                        $object->level          = $order->userlevel;
                        $object->username       = $order->UserName ? $order->UserName : '未知';
                        $object->payaccount     = $order->payaccount;
                        $object->paymethod      = $order->paymethod? $order->paymethod : '未知';
                        $object->rmb            = $order->rmb;
                        $object->status         = $order->stat;
                        $object->isrebate       = $order->isRebate;
                        $object->rebate         = $order->Rebate;
                        $object->remark         = $order->remark;
                        $object->created        = time();
                        $object->save();
                    }
                }
            }
        }
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
            $from           = str_replace('/', '-', $from);
            $to             = trim($dateRange[1]);
            $to             = str_replace('/', '-', $to);
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

 
        $orderColumns = array(
            0=>'id', 
            7=>'createdate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM ad_statistic_order_recharges ";
        if($searchValue)
        {
            $conditions[] = " order_id like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " (createdate BETWEEN  '{$from}' AND '{$to}') ";
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

                if($result->status == 9)
                {
                    $status = '成功';
                }else
                {
                    $status = '失败';
                }
                
                $object = array();
                $object[] = $result->id;
                $object[] = $result->order_id;
                $object[] = $result->gamename;
                $object[] = $result->type;
                $object[] = $result->level;
                $object[] = $result->username;
                $object[] = $result->rmb;
                $object[] = $result->createdate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/recharge/'.$result->id).'">详情</a>';

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


    public function recharge_show($id)
    {
        $object  = OrderRecharge::find($id); 
        if($object->status == 9)
        {
            $object->status = '成功';
        }else
        {
            $object->status = '失败';
        }
        if($object->isrebate == 1)
        {
            $object->isrebate = '是';
        }else
        {
            $object->isrebate = '否';
        }


        return view($this->moduleView.'/show', ['object'=>$object, 'title'=>'充值订单详情']);
    
    }
    
    public function payment_orders()
    {    
        $games = Game::all();
        if(count($games))
        {
            DB::delete("truncate ad_statistic_order_pays");
            foreach($games as $game)
            {
                $gameId         = $game->Gameid;        //ID
                $gameType       = 0;                    //游戏类型 game_info里的typeid
                $payType        = 0;                    //支付类型
                $orderStatus    = 0;                    //订单状态 9=成功, 其它失败
                $userType       = 1;                    //推广员类型, 1=公会
                $start          = '1970-01-01';         // 1970-01-01
                $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
                $searchType     = 2;                    //1=充值，2=消费
                $orders = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_ordermanager_sel(?,?,?,?,?,?,?,?)",array($gameId, $gameType, $payType, $orderStatus, $userType, $start, $end,$searchType));
                
                if(count($orders))
                {
                    //DB::table('statistic_order_recharges')->delete();
                    foreach($orders as $order)
                    {
                        $object = new OrderPay();
                        $object->order_id       = $order->systemorder;
                        $object->createdate     = $order->CreateDate;
                        $object->gamename       = $order->Gamename;
                        $object->username       = $order->UserName ? $order->UserName : '未知';
                        $object->payaccount     = $order->payaccount;
                        $object->goodsname      = $order->goodsname;
                        $object->goodsnum       = $order->goodsnum;
                        $object->paymethod      = $order->paymethod? $order->paymethod : '未知';
                        $object->paynum         = $order->paynum;
                        $object->status         = $order->stat;
                        $object->remark         = $order->remark;
                        $object->created        = time();
                        $object->save();
                    }
                }
            }
        }
     
        return view($this->moduleView.'/payment_orders', ['title'=>'消费订单总汇']);
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
            $from           = str_replace('/', '-', $from);
            $to             = trim($dateRange[1]);
            $to             = str_replace('/', '-', $to);
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

 
        $orderColumns = array(
            0=>'id', 
            7=>'createdate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM ad_statistic_order_pays ";
        if($searchValue)
        {
            $conditions[] = " order_id like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " (createdate BETWEEN  '{$from}' AND '{$to}') ";
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

                if($result->status == 9)
                {
                    $status = '成功';
                }else
                {
                    $status = '失败';
                }
                
                $object = array();
                $object[] = $result->id;
                $object[] = $result->order_id;
                $object[] = $result->gamename;
                $object[] = $result->goodsname;
                $object[] = $result->goodsnum;
                $object[] = $result->username;
                $object[] = $result->paynum;
                $object[] = $result->createdate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/payments/'.$result->id).'">详情</a>';

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


    public function payment_show($id)
    {
        $object  = OrderPay::find($id); 
        if($object->status == 9)
        {
            $object->status = '成功';
        }else
        {
            $object->status = '失败';
        }
        if($object->isrebate == 1)
        {
            $object->isrebate = '是';
        }else
        {
            $object->isrebate = '否';
        }


        return view($this->moduleView.'/payment_order_show', ['object'=>$object, 'title'=>'消费订单详情']);
    
   
    }

    public function give_orders()
    {    
        DB::delete("truncate ad_statistic_order_gives");
        for($level = 1; $level<4; $level++)
        {
            $type       = 1;                                //发起方类型
            $start      = '1970-01-01';                     // 1970-01-01
            $end        = date('Y-m-d', time()+24*3600);    // 2016-10-10
            
            $orders = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_platform_transorder_sel(?,?,?,?)",array($type, $level, $start, $end));

            if(count($orders))
            {
                foreach($orders as $order)
                {
                    if(trim($order->stat) == '成功')
                    {
                        $status = 9;
                    }
                    else
                    {
                        $status = 0;
                    }
                    $object = new OrderGive();
                    $object->order_id       = $order->Systemorder;
                    $object->createdate     = $order->createdate;
                    $object->type           = $order->usertype;
                    $object->level          = $order->userlevel;
                    $object->username       = $order->username ? $order->username : '未知';
                    $object->tousername     = $order->tousername ? $order->tousername : '未知';
                    $object->badou          = $order->badou;
                    $object->afterbadou     = $order->afterbadou;
                    $object->status         = $status;
                    $object->remark         = $order->remark ? $order->remark : '';
                    $object->created        = time();
                    $object->save();
                }
            }
        }
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
            $from           = str_replace('/', '-', $from);
            $to             = trim($dateRange[1]);
            $to             = str_replace('/', '-', $to);
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

 
        $orderColumns = array(
            0=>'id', 
            7=>'createdate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM ad_statistic_order_gives ";
        if($searchValue)
        {
            $conditions[] = " order_id like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " (createdate BETWEEN  '{$from}' AND '{$to}') ";
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

                if($result->status == 9)
                {
                    $status = '成功';
                }else
                {
                    $status = '失败';
                }
                
                $object = array();
                $object[] = $result->id;
                $object[] = $result->order_id;
                $object[] = $result->type;
                $object[] = $result->level;
                $object[] = $result->username;
                $object[] = $result->tousername;
                $object[] = $result->badou;
                $object[] = $result->createdate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/gives/'.$result->id).'">详情</a>';

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


    public function give_show($id)
    {
        $object  = OrderGive::find($id); 
        if($object->status == 9)
        {
            $object->status = '成功';
        }else
        {
            $object->status = '失败';
        }

        return view($this->moduleView.'/give_order_show', ['object'=>$object, 'title'=>'转增订单详情']);
    }


}
