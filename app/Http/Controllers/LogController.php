<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Log;
use App\User;
use DB;
use App\Models\Guild\Guild;
use App\Models\Finance\FinanceOrder;

class LogController extends Controller
{
    protected $moduleRoute    = 'logs';      //路由URL
    protected $moduleView     = 'log';       //视图路径
    protected $moduleTable    = 'logs';
    protected $moduleName     = '日志';

    public function index($controllerType=NULL, $methodType=NULL)
    {       
        $logType = ucfirst($controllerType).'Log';
        $method = $methodType.'_title'; 
        $controller = 'App\\Http\\Controllers\\'.$logType;
        $title = $controller::$method(); 
        $searchPlaceholder = '描述';

        return view('log/log', ['controllerType'=>$controllerType, 'methodType'=>$methodType, 'title'=>$title, 'searchPlaceholder'=>$searchPlaceholder]);
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
        $controllerType = $requests['controllerType'];
        $controllerType = ucfirst($controllerType);
        $methodType     = $requests['methodType'];

        $orderColumns = array(
            0=>'created', 
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from ad_logs ";
        $sql .= " WHERE module like '%{$controllerType}%' AND function = '{$methodType}' ";
        if($searchValue)
        {
            $sql .= " AND content like '%{$searchValue}%' ";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM ad_logs ";
        $sqlCount .= "WHERE module = '{$controllerType}' AND function = '{$methodType}' ";
        if($searchValue)
        {
            $sqlCount .= " AND content like '%{$searchValue}%' ";
        }
        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;

        $logType = ucfirst($requests['controllerType']).'Log';

        $controllerType = 'App\\Http\\Controllers\\'.$logType;
        $controllerType::$methodType($results, $objects);

        return json_encode($objects);

    }
}

class ChairmanLog extends LogController
{
    private static $audit_form_submit_title = '会长审核';
    private static $game_authorization_form_submit_title = '游戏授权';
    private static $blacklist_form_submit_title = '公会黑名单';


    public static function audit_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM dt_guild_list WHERE Id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Name;
            }
            else
            {
                $operator_object = '未知';
            }
            if($result->operation == 'yes')
            {
                $operation = '通过';
            }elseif($result->operation == 'no'){
                $operation = '驳回';
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function audit_form_submit_title()
    {
        return self::$audit_form_submit_title;
    }    
    
    public static function game_authorization_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM dt_guild_list WHERE Id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Name;
            }
            else
            {
                $operator_object = '未知';
            }


            $operation = $result->operation;

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function game_authorization_form_submit_title()
    {
        return self::$game_authorization_form_submit_title;
    }    
    
    public static function blacklist_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM dt_guild_list WHERE Id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Name;
            }
            else
            {
                $operator_object = '未知';
            }


            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $result->operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function blacklist_form_submit_title()
    {
        return self::$blacklist_form_submit_title;
    }    
 
}


class DeveloperLog extends LogController
{
    private static $title = '开发者审核';

    public static function audit_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_cpinfo WHERE cpid={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->username;
            }
            else
            {
                $operator_object = '未知';
            }
            if($result->operation == 'yes')
            {
                $operation = '通过';
            }elseif($result->operation == 'no'){
                $operation = '驳回';
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function audit_form_submit_title()
    {
        return self::$title;
    }
}

class GameLog extends LogController
{
    private static $title           = '游戏审核';
    private static $rebateTitle     = '返点设置';
    private static $onlineTitle     = '游戏上下线';
    private static $blacklistTitle  = '游戏黑名单';

    public static function audit_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_info WHERE id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Gamename;
            }
            else
            {
                $operator_object = '未知';
            }

            if($result->operation == 'yes')
            {
                $operation = '通过';
            }elseif($result->operation == 'no'){
                $operation = '驳回';
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function audit_form_submit_title()
    {
        return self::$title;
    }    
    
    public static function rebate_setup_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_info WHERE id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Gamename;
            }
            else
            {
                $operator_object = '未知';
            }


            $operation = '返点设置';

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function rebate_setup_form_submit_title()
    {
        return self::$rebateTitle;
    }

    public static function online_handle_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_info WHERE id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Gamename;
            }
            else
            {
                $operator_object = '未知';
            }


            $operation = $result->operation;

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function online_handle_submit_title()
    {
        return self::$onlineTitle;
    }

    public static function blacklist_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_info WHERE id={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Gamename;
            }
            else
            {
                $operator_object = '未知';
            }


            $operation = $result->operation;

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function blacklist_form_submit_title()
    {
        return self::$blacklistTitle;
    }



}

class ApkLog extends LogController
{
    private static $title = '游戏包审核';

    public static function audit_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $operator_object = DB::select("SELECT * FROM game_apkinfo WHERE apkid={$result->object}");
            if(count($operator_object))
            {
                $operator_object = $operator_object[0]->Apkname;
            }
            else
            {
                $operator_object = '未知';
            }


            if($result->operation == 'yes')
            {
                $operation = '通过';
            }elseif($result->operation == 'no'){
                $operation = '驳回';
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $operation;
            $object[] = $operator_object;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function audit_form_submit_title()
    {
        return self::$title;
    }

}

class FinanceLog extends LogController
{
    private static $title       = '公会折扣设置';
    private static $titleOrder  = '公会充值下单';
    private static $titlePay    = '公会充值支付';

    public static function discount_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;

            if($result->object == 'a')
            {
                $operator_object = 'A级';
            }elseif($result->object == 'b'){
                $operator_object = 'B级';
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $result->operation;
            $object[] = $operator_object;
            $object[] = unserialize($result->content);
            $objects['data'][] = $object;
        }
    }

    public static function discount_form_submit_title()
    {
        return self::$title;
    }
    
    public static function recharge_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;

            $guild = Guild::where('Userid', $result->object)->get();
            if(count($guild))
            {
                $guildName = $guild[0]->Name;
            }
            else
            {
                $guildName = '未知';
            }
            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $result->operation;
            $object[] = $guildName;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function recharge_form_submit_title()
    {
        return self::$title;
    }

    public static function order_pay_form_submit($results, &$objects)
    {   
        foreach($results as $result)
        {        
            $operator = User::find($result->uid);
            $operator = $operator->name;
            $order  = FinanceOrder::find($result->object);
            if(count($order))
            {
                $guild = Guild::where('Userid', $order->gid)->get();
                if(count($guild))
                {
                    $guildName = $guild[0]->Name;
                }
                else
                {
                    $guildName = '未知';
                }
            }

            $object = array();
            $object[] = date('Y-m-d H:i:s', $result->created);
            $object[] = $operator;
            $object[] = $result->operation;
            $object[] = $guildName;
            $object[] = $result->content;
            $objects['data'][] = $object;
        }
    }

    public static function order_pay_form_submit_title()
    {
        return self::$titlePay;
    }


}

