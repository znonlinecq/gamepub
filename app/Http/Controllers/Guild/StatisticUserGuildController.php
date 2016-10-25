<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;
use View;
use DB;

class StatisticUserGuildController extends StatisticBaseController
{    
    protected $modelName            = 'App\Models\Guild\StatisticUserGuild';
    protected $table                = 'ad_statistic_guild_users_guilds';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_guild_userinfos_byguildid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $search_datetime      = 'create_date';
    protected $moduleRoute          = 'chairmans/statistic/users_guilds';
    protected $moduleAjax           = '/chairmans/statistic/users_guilds_ajax';
    protected $searchPlaceholder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,fals,true,false';
    protected $listTitle            = '用户数据统计_按推广ID';    
    protected $showTitle            = '用户数据统计详情_按推广ID';    

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'create_date'       => 'logdate',
            'game_name'         => 'appname',
            'guild_id'          => 'guildname',
            'user_id'           => 'username',
            'increase_users'    => 'newadd',
            'active_users'      => 'active',
            'recharge_users'    => 'rechargeuser',
            'money'             => 'recharge',
            'arpu'              => 'arpu',
            'remain_1'          => 'o_remain_percent',
            'remain_7'          => 's_remain_percent',
            'remain_15'         => 'f_remain_percent',
        );  
        $object['list_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称',
            'guild_id'          => '推广员ID',
            'increase_users'    => '新增用户',
            'recharge_users'    => '充值用户',
            'money'             => '充值金额',
            'remain_1'          => '次日留存率',
            'remain_7'          => '7日留存率',
            'create_date'       => '统计日期',
            'op'                => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称',
            'guild_id'          => '推广员ID',
            'user_id'           => '推广员账号',
            'increase_users'    => '新增用户',
            'active_users'      => '活跃用户',
            'recharge_users'    => '充值用户',
            'money'             => '充值金额',
            'arpu'              => 'ARPU',
            'remain_1'          => '次日留存率',
            'remain_7'          => '7日留存率',
            'remain_15'         => '15日留存率',
            'create_date'       => '统计日期',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        $gameId         = 0;                        //ID
        $guildId        = 0;                        //ID
        $start          = '1970-01-01';                         // 1970-01-01
        $end            = date('Y-m-d', time()+24*3600);         // 2016-10-10
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?,?)", array($gameId, $guildId, $start, $end));
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
