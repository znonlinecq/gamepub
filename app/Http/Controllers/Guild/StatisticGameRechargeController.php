<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use DB;

class StatisticGameRechargeController extends Controller
{    
    protected $modelName            = 'App\Models\Guild\StatisticGameRecharge';
    protected $table                = 'ad_statistic_guild_games_recharges';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_guild_gamerecharge_byguildid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $moduleRoute          = 'chairmans/statistic_game_recharges';
    protected $moduleAjax           = '/chairmans/list_ajax/statistic_game_recharges';
    protected $searchPlaceholder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false';
    protected $listTitle            = '游戏充值统计';    
    protected $showTitle            = '游戏充值统计详情';    
    protected $dateFilter           = false;

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'game_name'         => 'gamename',
            'guild_recharge'    => 'guildrecharge',
            'other_recharge'    => 'otherrecharge',
            'all_recharge'      => 'allrecharge',
        );  
        $object['list_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称', 
            'guild_recharge'    => '公会充值金额',
            'other_recharge'    => '非公会充值金额',
            'all_recharge'      => '平台充值金额',
            'op'                => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称',    
            'guild_recharge'    => '公会充值金额',
            'other_recharge'    => '非公会充值金额',
            'all_recharge'      => '平台充值金额',
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
        $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?)", array($start, $end, $gameId));

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
    
    public function setOp(){
        $op = array(
            array(
                'name' => '详情',
                'url'   => '/',
                'field' => 'id',
            ),
        );
        return $op;       
    }
    
    public function dataFilter($field, $data, $object=NULL)
    {
        switch($field)
        {
            case 'op':
                    $value = '<a href="'.url($this->moduleRoute.'/show/'.$object->id).'">详情</a>';
                break;
            default:
                $value = $data;
                break;
        }
        return $value;
    } 
   
}
