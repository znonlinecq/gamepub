<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\Models\Guild\Guild;
use App\User;
use DB;
use Auth;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceRecord;
use App\Models\Finance\FinanceOrder;
use App\Models\Log;
use App\Models\Variable;

class FinanceController extends Controller
{
    private $moduleRoute = 'finances';     //路由URL
    private $moduleView = 'finance';       //视图路径
    private $moduleTable = 'finances';
    private $moduleName = '财务';
    private $moduleIndexAjax = '/finances/index_ajax';

    private $searchPlaceholder = '公会名称';

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
        return view($this->moduleView.'/index', [ 'title'=>'充值结算']);
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
            $from           = explode(' ', $from);
            $fromYmd        = explode('/', $from[0]);   
            $fromHis        = explode(':', $from[1]);   
            $fromHour       = $fromHis[0];
            $fromMinute     = $fromHis[1];
            $fromSecond     = $fromHis[2];
            $fromYear       = $fromYmd[0];
            $fromMonth      = $fromYmd[1];
            $fromDay        = $fromYmd[2];
            $fromTimestamp  = mktime($fromHour, $fromMinute, $fromSecond, $fromMonth, $fromDay, $fromYear);
            $to             = trim($dateRange[1]);
            $to             = explode(' ', $to);
            $toYmd          = explode('/', $to[0]);
            $toHis          = explode(':', $to[1]);
            $toHour         = $toHis[0];
            $toMinute       = $toHis[1];
            $toSecond       = $toHis[2];
            $toYear         = $toYmd[0];
            $toMonth        = $toYmd[1];
            $toDay          = $toYmd[2];
            $toTimestamp    = mktime($toHour, $toMinute, $toSecond, $toMonth, $toDay, $toYear);
        }
        else
        {
            $fromTimestamp  = NULL;
            $toTimestamp    = NULL;
        }

        $orderColumns = array(
            0=>'Id', 
            7=>'CreateDate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from ad_finances_orders ";
        if($searchValue)
        {
            $conditions[] = " money like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " created >= {$fromTimestamp} AND created <= {$toTimestamp}";
        }

        if(count($conditions) == 1)
        {
            $sql .= " WHERE {$conditions[0]}";
        }
        elseif(count($conditions) > 1)
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
                $guild  = Guild::where('UserId', $result->gid)->get();
                if(count($guild))
                {
                    $guildName = $guild[0]->Name;
                }
                else
                {
                    $guildName = '未定义';
                }
                $user   = User::find($result->operator);
                
                if($result->status == 0)
                {
                    $status ='待支付';
                    $op = '<a href="'.url('finances/order_pay_form/'.$result->id).'">支付</a>';
                }
                elseif($result->status == 1)
                {
                    $status = '支付完成';
                    $op = ' ';
                }
                elseif($result->status == 2)
                {
                    $status = '支付失败';
                    $op = ' ';
                }

                if($result->type == 1)
                {
                    $type = '手工充值';
                }elseif($result->type == 0)
                {
                    $type = '未知';
                }
                $object = array();
                $object[] = $result->id;
                $object[] = $guildName;
                $object[] = $type;
                $object[] = $result->money;
                $object[] = $result->points;
                $object[] = $result->orderid;
                $object[] = $user->name;
                $object[] = date('Y-m-d H:i:s', $result->created);
                $object[] = $status;
                $object[] = $op;
                
                $objects['data'][] = $object;
            }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ',' ',' ', ' ');
        }
        return json_encode($objects);
    }

    public function recharge_form()
    {
        $permissionsHandle = array();
        $sql = "SELECT * FROM dt_guild_list WHERE GuildType IN (1,2) AND GuilderId = 0 ORDER BY GuildType ASC";
        $guilds  = DB::select($sql);
        return view($this->moduleView.'/recharge_form', ['title'=>'公会充值下单', 'guilds'=>$guilds]);    
    }

    public function recharge_form_submit(Request $requests)
    {

        $this->validate($requests, [
            'gid'           => 'required',
            'money'         => 'required|integer|min:0|max:1000000',
            'description'   => 'required',
        ]);

        $request        = $requests->all();
        $gid            = $request['gid'];
        $money          = $request['money'];
        $user           = Auth::User();
        $description    = $request['description'];
        $guild          = Guild::where('UserId', $gid)->get();
        $guild          = $guild[0];

        //调用订单API
        $apiUrl = 'https://passport2016.87870.com/pay/AdminCreateOrder.ashx';
        $params['appid'] = '16090104';
        $params['actionid'] = 94;
        $params['timestamp'] = time();
        $params['extracommonparam'] = '';
        $params['touserid'] = $gid;
        $params['adminid'] = $user->id;
        $params['tobadou'] = ceil($money + ($money/100 * 20));
        $params['adminremark'] = 'test';
        $params['rmb'] = $money;
        ksort($params);
        foreach($params as $key => $value)
        {
            $paramsArray[] = $key.'='.$value;
        }
        $signStr = implode('&', $paramsArray).'&B6C00B3137ACB99526DCA3FA5BCFD596';
        //print_r($signStr);
        $sign = md5($signStr);
        $params['sign'] = $sign;
        $result = $this->curl_get($apiUrl, $params);  //调用API
        $result = json_decode($result);
        $orderId = $result->Data->GameOrder;
        
        $type       = 1; //手工充值
        $status     = 0; //等待支付
        $percent    = 0;
        $points     = ceil($money + ($money * 0.2));
        $pointsVr   = 0;
        $operator   = $user->id;

        $object = new FinanceOrder();
        $object->gid            = $gid;
        $object->orderid        = $orderId;
        $object->type           = $type;
        $object->status         = $status;
        $object->percent        = $percent;
        $object->money          = $money;
        $object->points         = $points;
        $object->pointsvr       = $pointsVr;
        $object->operator       = $operator;
        $object->description    = $description;
        $object->created        = time();
        $object->save();

        $finance = Finance::where('gid', $gid)->get();
        if(count($finance))
        {
            Finance::where('gid', $gid)->update(['sum'=>$finance[0]->sum+$money]);
        }
        else{
            $finance = new Finance();
            $finance->gid = $gid;
            $finance->sum = $money;
            $finance->points = 0;
            $finance->pointsvr = 0;
            $finance->save();
        }

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = '公会充值下单';
        $params['object'] = $gid;
        $params['content'] = "{$guild->Name} 充值下单金额 {$money}.";
        Log::record($params);
        
        return redirect($this->moduleRoute)->with('message', '下单成功!');
    }
    
    public function order_pay_form($oid)
    {
        $order = FinanceOrder::find($oid);
        $guild = Guild::where('UserId', $order->gid)->get();
        $guild = $guild[0];
        return view($this->moduleView.'/order_pay_form', ['title'=>'公会充值支付', 'order'=>$order, 'guild'=>$guild]);    
    }

    public function order_pay_form_submit(Request $requests)
    {
        $this->validate($requests, [
            'oid'           => 'required',
            'description'   => 'required',
            'password'      => 'required|numeric|payment_password',
            'captcha'      => 'required|captcha',
        ]);

        $request        = $requests->all();
        $oid            = $request['oid'];
        $user           = Auth::User();
        $description    = $request['description'];
        $order          = FinanceOrder::find($oid);
        $gid            = $order->gid;
        $money          = $order->money;

        //调用支付API
        $apiUrl = 'https://passport2016.87870.com/pay/AdminAddGuildBadou.ashx';
        $params['appid'] = '16090104';
        $params['actionid'] = 94;
        $params['timestamp'] = time();
        $params['extracommonparam'] = '';
        $params['touserid'] = $gid;
        $params['adminid'] = $user->id;
        $params['adminremark'] = $description;
        $params['systemorder'] = $order->orderid;
        ksort($params);
        foreach($params as $key => $value)
        {
            $paramsArray[] = $key.'='.$value;
        }
        $signStr = implode('&', $paramsArray).'&B6C00B3137ACB99526DCA3FA5BCFD596';
        $sign = md5($signStr);
        $params['sign'] = $sign;
        $result = $this->curl_get($apiUrl, $params);  //调用API
        $result = json_decode($result);
        
        if($result->Result->Ret == 0)
        {
            //更新订单状态
            $object = FinanceOrder::find($oid);
            $object->status             = 1;
            $object->closed             = time();
            $object->pay_operator       = $user->id;
            $object->pay_description    = $description;
            $object->save();

            //更新财务数据
            $finance = Finance::where('gid', $gid)->get();
            if(count($finance))
            {
                Finance::where('gid', $gid)->update(['sum'=>$finance[0]->sum+$money]);
            }
            else{
                $finance = new Finance();
                $finance->gid = $gid;
                $finance->sum = $money;
                $finance->points = 0;
                $finance->pointsvr = 0;
                $finance->save();
            }

            //日志
            $params['module'] = __CLASS__;
            $params['function'] = __FUNCTION__;
            $params['operation'] = '公会充值支付';
            $params['object'] = $oid;
            $params['content'] = "订单号: {$order->orderid} | 支付金额 {$money},支付成功!";
            Log::record($params);

            return redirect($this->moduleRoute)->with('message', '支付成功!');

        }
        else
        {
            //更新订单状态
            $object = FinanceOrder::find($oid);
            $object->status     = 2;
            $object->closed     = time();
            $object->save();

            //日志
            $params['module'] = __CLASS__;
            $params['function'] = __FUNCTION__;
            $params['operation'] = '公会充值支付';
            $params['object'] = $oid;
            $params['content'] = "{$order->orderid} 支付金额 {$money},支付失败.";
            Log::record($params);

            return redirect($this->moduleRoute)->with('message', '支付成功!');
        }
    }
    
    public function discount_form()
    {
        $months[] = '一月';
        $months[] = '二月';
        $months[] = '三月';
        $months[] = '四月';
        $months[] = '五月';
        $months[] = '六月';
        $months[] = '七月';
        $months[] = '八月';
        $months[] = '九月';
        $months[] = '十月';
        $months[] = '十一月';
        $months[] = '十二月';

        $a = Variable::where('key', 'guild_a_discount')->get();
        if(count($a))
        {
            $guild_a_discount = unserialize($a[0]->value);
        }
        else
        {
            for($i=0;$i<12;$i++)
            {
                $guild_a_discount[] = 0;
            }
        } 
        
        $b = Variable::where('key', 'guild_b_discount')->get();
        if(count($b))
        {
            $guild_b_discount = unserialize($b[0]->value);
        }
        else
        {
            for($i=0;$i<12;$i++)
            {
                $guild_b_discount[] = 0;
            }
        }
        return view($this->moduleView.'/discount_form', ['title'=>'公会充值折扣设置', 'months'=>$months, 'guild_a_discount'=>$guild_a_discount, 'guild_b_discount'=>$guild_b_discount]);    
    }

    public function discount_form_submit(Request $requests)
    {

      //  $this->validate($requests, [
      //      'gid'           => 'required',
      //      'money'         => 'required|integer|min:0|max:1000000',
      //      'description'   => 'required',
      //  ]);

        $request        = $requests->all();
        $a              = serialize($request['a']);
        $b              = serialize($request['b']);

        foreach($request['a'] as $value)
        {
            if($value > 100 || $value < 0)
            {
                return redirect($this->moduleRoute.'/discount_form')->with('message', '折扣错误，不能大于100，小于0');
            }
        }
        
        foreach($request['b'] as $value)
        {
            if($value > 100 || $value < 0)
            {
                return redirect($this->moduleRoute.'/discount_form')->with('message', '折扣错误，不能大于100，小于0');
            }
        }


        $guild_a_discount = Variable::where('key', 'guild_a_discount')->get();
        if(count($guild_a_discount))
        {
            Variable::where('key', 'guild_a_discount')->update(['value'=>$a]);
        }
        else{
            $variable = new Variable();
            $variable->key = 'guild_a_discount';
            $variable->value = $a;
            $variable->save();
        }

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = '公会折扣设置';
        $params['object'] = 'a';
        $params['content'] = $a;
        Log::record($params);
        
        $guild_b_discount = Variable::where('key', 'guild_b_discount')->get();
        if(count($guild_b_discount))
        {
            Variable::where('key', 'guild_b_discount')->update(['value'=>$b]);
        }
        else{
            $variable = new Variable();
            $variable->key = 'guild_b_discount';
            $variable->value = $b;
            $variable->save();
        }

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = '公会折扣设置';
        $params['object'] = 'b';
        $params['content'] = $b;
        Log::record($params);
        
        return redirect($this->moduleRoute.'/discount_form')->with('message', '设置成功!');
    }
    
}
