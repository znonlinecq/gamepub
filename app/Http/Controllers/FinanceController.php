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
use App\Models\Log;

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
        return view($this->moduleView.'/index', [ 'title'=>'充值记录']);
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
        $type           = $requests['type']; 
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

        $sql = " select * from ad_finances ";
        if($searchValue)
        {
            $conditions[] = " Name like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " CreateDate >= {$fromTimestamp} AND CreateDate <= {$toTimestamp}";
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
                $guild  = Guild::find($result->guildid);
                $user   = User::find($result->operator);

                $object = array();
                $object[] = $result->id;
                $object[] = $guild->Name;
                $object[] = $result->type;
                $object[] = $result->money;
                $object[] = $result->points;
                $object[] = $result->percent;
                $object[] = $result->orderid;
                $object[] = $user->name;
                $object[] = date('Y-m-d H:i:s', $result->CreateDate);
                $object[] = '<a href="'.url('finances/'.$result->id).'">详情</a>';
                
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
        $sql = "SELECT * FROM dt_guild_list WHERE GuildType IN (1,2) ORDER BY GuildType ASC";
        $guilds  = DB::select($sql);
        return view($this->moduleView.'/recharge_form', ['title'=>'公会充值', 'guilds'=>$guilds]);    
    }

    public function recharge_form_submit(Request $requests)
    {
        $request        = $requests->all();
        $gid            = $request['gid'];
        $money          = $request['money'];
        $user           = Auth::User();
        $description    = $request['description'];
        $guild          = Guild::find($gid);

        //TODO
        $orderId = '';
        $type = 0;
        $status = 0;
        $percent    = 0;
        $points     = 0;
        $pointsVr   = 0;
        $operator   = $user->id;

        $object = new FinanceRecord();
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
                
        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = '公会充值';
        $params['object'] = $gid;
        $params['content'] = "{$guild->Name} 充值金额 {$money}.";
        Log::record($params);
        
        return redirect($this->moduleRoute)->with('message', '充值成功!');
    }
    
}
