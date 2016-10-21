<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\User;
use DB;
use App\Models\Game;
use App\Models\Statistic\StatisticPlatformIncome;

class StatisticController extends Controller
{

    public function index_type($type)
    {
        $type = ucfirst($type);
        $controller = 'App\Http\Controllers\StatisticPlatform'.$type.'Controller';
        $object = new $controller();
        return $object->index();
        //$object = new StatisticPlatformChannelController();
    }

    public function index_ajax_type(Request $requests,$type)
    {
        $controller = 'App\Http\Controllers\StatisticPlatform'.$type.'Controller';
        $object = new $controller();
        $test = $object->index_ajax($requests);
        return $test;
    } 
    
    public function show_type($type,$id)
    {
        $type = ucfirst($type);
        $controller = 'App\Http\Controllers\StatisticPlatform'.$type.'Controller';
        $object = new $controller();
        return $object->show($id);
    }
}

class StatisticPlatformChannelController extends Controller
{    
    protected $modelName            = 'App\Models\Statistic\StatisticPlatformChannel';
    protected $table                = 'ad_statistic_platform_channels';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_paltform_dataall_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $moduleRoute          = 'statistics/channel';
    protected $moduleAjax           = '/statistics/ajax/channel';
    protected $searchPlaceHolder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,true,false';
    protected $listTitle            = '渠道统计';    
    protected $showTitle            = '渠道统计详情';    
    protected $showType             = 'channel';

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'game_name'             => 'appname',
            'increase_users'        => 'newadd',
            'increase_recharge'     => 'newrecharge',
            'today_recharge'        => 'today_recharge',
            'all_users'             => 'alluser',
            'all_recharge_users'    => 'allrechargeuser',
            'log_date'              => 'logdate',
        );  
        $object['list_fields'] = array(
            'id'                    => 'ID',
            'game_name'             => '游戏名称',
            'increase_users'        => '新增用户',
            'increase_recharge'     => '新增充值',
            'today_recharge'        => '今日充值',
            'all_users'             => '累计用户',
            'all_recharge_users'    => '累计充值用户',
            'log_date'              => '日志时间',
            'op'                    => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',
            'game_name'             => '游戏名称',
            'increase_users'        => '新增用户',
            'increase_recharge'     => '新增充值',
            'today_recharge'        => '今日充值',
            'all_users'             => '累计用户',
            'all_recharge_users'    => '累计充值用户',
            'log_date'              => '日志时间',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        $gameId         = 0;                        //ID
        $start          = '1970-01-01';                         // 1970-01-01
        $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?)", array($gameId, $start, $end));
        if(count($results))
        {
            foreach($results as $result)
            {
                $object = new $this->modelName();
                foreach($handle_fields as $key => $value)
                {
                    $object->$key = $result->$value ? $result->$value : 0;
                }
                $object->created        = time();
                $object->save();
            }
        }
    }
}

class StatisticPlatformIncomeController extends Controller
{    
    protected $modelName            = 'App\Models\Statistic\StatisticPlatformIncome';
    protected $table                = 'ad_statistic_platform_incomes';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_allrecharge_paltform_byappid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $moduleRoute          = 'statistics/income';
    protected $moduleAjax           = '/statistics/ajax/income';
    protected $searchPlaceHolder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,false,true,false';
    protected $listTitle            = '游戏收入';    
    protected $showTitle            = '游戏收入详情';    
    protected $showType             = 'income';

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'game_name'             => 'appname',
            'type'                  => 'typename',
            'sum_money'             => 'tody_recharge',
            'sum_users'             => 'today_rechargeuser',
            'average'               => 'avg_recharge',
            'register_count'        => 'today_reg',
            'download_count'        => 'today_download',
            'income_count'          => 'today_rechargetimes',
            'log_date'              => 'logdate',
        );  
        $object['list_fields'] = array(
            'id'                    => 'ID', 
            'game_name'             => '游戏名称',
            'type'                  => '类型',
            'sum_money'             => '充值金额',
            'sum_users'             => '充值人数',
            'register_count'        => '累计注册',
            'download_count'        => '累计下载',
            'income_count'          => '累计充值',
            'log_date'              => '累计充值笔数',
            'op'                    => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                    => 'ID', 
            'game_name'             => '游戏名称',
            'type'                  => '类型',
            'sum_money'             => '充值金额',
            'sum_users'             => '充值人数', 
            'average'               => '平均充值',
            'register_count'        => '累计注册',
            'download_count'        => '累计下载',
            'income_count'          => '累计充值',
            'log_date'              => '累计充值笔数',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        $gameId         = 0;                        //ID
        $start          = '1970-01-01';                         // 1970-01-01
        $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
        $type           = 7;                         //类型
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?,?)", array($gameId, $start, $end, $type));
        if(count($results))
        {
            foreach($results as $result)
            {
                $object = new $this->modelName();
                foreach($handle_fields as $key => $value)
                {
                    $object->$key = $result->$value ? $result->$value : 0;
                }
                $object->created        = time();
                $object->save();
            }
        }
    }
}

class StatisticPlatformUserController extends Controller
{    
    protected $modelName            = 'App\Models\Statistic\StatisticPlatformUser';
    protected $table                = 'ad_statistic_platform_users';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_gamesinfos_platform_byappid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $moduleRoute          = 'statistics/user';
    protected $moduleAjax           = '/statistics/ajax/user';
    protected $searchPlaceHolder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,false,false,false';
    protected $listTitle            = '游戏用户';    
    protected $showTitle            = '游戏用户详情';    
    protected $showType             = 'user';

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'game_name'                 => 'appname',
            'day_active'                => 'today_active',
            'increase_users'            => 'today_newadd',
            'all_users'                 => 'alluser',
            'increase_recharge_users'   => 'today_addnew_rechargeuser',
            'all_recharge_users'        => 'all_rechargeuser',
            'new_increase_recharge'     => 'today_recharge',
            'all_recharge'              => 'all_recharge',
            'new_recharge_percent'      => 'adduser_recharge_precent',
            'arpu'                      => 'arpu',
            'one_remain'                => 'one_remain',
            'seven_remain'              => 'seven_remain',
            'fifteen_remain'            => 'fifteen_remain',
            'log_date'                  => 'logdate',
        );  
        $object['list_fields'] = array(
            'id'                        => 'ID', 
            'game_name'                 => '游戏名称',
            'day_active'                => '日活跃用户',
            'increase_users'            => '新增用户',
            'all_users'                 => '累计新增',
            'increase_recharge_users'   => '新增付费用户',
            'all_recharge_users'        => '累计付费用户',
            'new_increase_recharge'     => '新增付费',
            'all_recharge'              => '新增用户费率',
            'op'                        => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                        => 'ID',    
            'game_name'                 => '游戏名称',
            'day_active'                => '新增用户',
            'increase_users'            => '日活跃用户',
            'all_users'                 => '新增用户',
            'increase_recharge_users'   => '累计新增',
            'all_recharge_users'        => '新增付费用户',
            'new_increase_recharge'     => '累计付费用户',
            'all_recharge'              => '新增付费',
            'new_recharge_percent'      => '新增用户费率',
            'arpu'                      => 'ARPU值',
            'one_remain'                => '次日留存',
            'seven_remain'              => '七日留存',
            'fifteen_remain'            => '15日留存',
            'log_date'                  => '日志时间',
       
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        $gameId         = 0;                        //ID
        $start          = '1970-01-01';                         // 1970-01-01
        $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
        $type           = 7;
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?,?)", array($gameId,$start, $end, $type));

        if(count($results))
        {
            foreach($results as $result)
            {
                $object = new $this->modelName();
                foreach($handle_fields as $key => $value)
                {
                    $object->$key = $result->$value ? $result->$value : 0;
                }
                $object->created        = time();
                $object->save();
            }
        }
    }
}

class StatisticPlatformBadouController extends Controller
{    
    protected $modelName            = 'App\Models\Statistic\StatisticPlatformBadou';
    protected $table                = 'ad_statistic_platform_badous';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_Monitor_badou';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'badou';
    protected $moduleRoute          = 'statistics/badou';
    protected $moduleAjax           = '/statistics/ajax/badou';
    protected $searchPlaceHolder    = '8豆';
    protected $tableColumns         = 'true,false,false,false,false,false,false,false,true,false';
    protected $listTitle            = '8豆监控';    
    protected $showTitle            = '8豆监控详情';    
    protected $showType             = 'badou';

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'recharge'              => 'Recharge',
            'badou'                 => 'badou',
            'return_badou'          => 'returnbadou',
            'add_Vdou'              => 'addVdou',
            'ratio'                 => 'ratio',
            'consume'               => 'consume',
            'stock'                 => 'stock',
            'log_date'              => 'logdate',
        );  
        $object['list_fields'] = array(
            'id'                    => 'ID',  
            'recharge'              => '充值金额',
            'badou'                 => '充值对应8豆',
            'return_badou'          => '返值8豆',
            'add_Vdou'              => '手动添加豆',
            'ratio'                 => '8豆比值',
            'consume'               => '消耗8豆',
            'stock'                 => '库存8豆',
            'log_date'              => '日志时间',
            'op'                    => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                    => 'ID',
            'recharge'              => '充值金额',
            'badou'                 => '充值对应8豆',
            'return_badou'          => '返值8豆',
            'add_Vdou'              => '手动添加豆',
            'ratio'                 => '8豆比值',
            'consume'               => '消耗8豆',
            'stock'                 => '库存8豆',
            'log_date'              => '日志时间',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        $start          = '1970-01-01';                         // 1970-01-01
        $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?)", array($start, $end));

        if(count($results))
        {
            foreach($results as $result)
            {
                $object = new $this->modelName();
                foreach($handle_fields as $key => $value)
                {
                    $object->$key = $result->$value ? $result->$value : 0;
                }
                $object->created        = time();
                $object->save();
            }
        }
    }
}

