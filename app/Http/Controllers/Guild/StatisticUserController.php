<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;
use View;
use DB;

class StatisticUserController extends Controller
{    
    protected $modelName            = 'App\Models\Guild\StatisticUser';
    protected $table                = 'ad_statistic_guild_users';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_guild_userinfos_byappid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $search_datetime      = 'create_date';
    protected $moduleRoute          = 'chairmans/statistic/users';
    protected $moduleAjax           = '/chairmans/statistic/users_ajax';
    protected $searchPlaceholder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,true,false';
    protected $listTitle            = '用户数据统计_按游戏';    
    protected $showTitle            = '用户数据统计详情_按游戏';    

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'create_date'       => 'logdate',
            'game_name'         => 'Gamename',
            'increase_users'    => 'adduser',
            'recharge_users'    => 'rechargeuser',
            'recharge_times'    => 'rechargetimes',
            'money'             => 'recharge',
            'arpu'              => 'arpu',
        );  
        $object['list_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称',
            'increase_users'    => '新增用户',
            'recharge_users'    => '充值用户',
            'recharge_times'    => '充值笔数',
            'money'             => '充值金额',
            'arpu'              => 'ARPU',
            'create_date'       => '统计日期',
            'op'                => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称',
            'increase_users'    => '新增用户',
            'recharge_users'    => '充值用户',
            'recharge_times'    => '充值笔数',
            'money'             => '充值金额',
            'arpu'              => 'ARPU',
            'create_date'       => '统计日期',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        $games = Game::all();
        if(count($games))
        {
            DB::delete("truncate {$this->table}");
            foreach($games as $game)
            {
                $gameId         = $game->Gameid;                        //ID
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
                            $object->$key = $result->$value;
                        }
                        $object->created        = time();
                        $object->save();
                    }
                }
            }
        }
    
    }
}
