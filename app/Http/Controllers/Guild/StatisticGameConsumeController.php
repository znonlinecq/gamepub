<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use DB;
use App\Models\Guild\StatisticGameConsume;

class StatisticGameConsumeController extends Controller
{    
    protected $modelName            = 'App\Models\Guild\StatisticGameConsume';
    protected $table                = 'ad_statistic_guild_games_consumes';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_guild_gameconsume_byguildid_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'game_name';
    protected $moduleRoute          = 'chairmans/statistic_game_consumes';
    protected $moduleAjax           = '/chairmans/list_ajax/statistic_game_consumes';
    protected $searchPlaceholder    = '游戏名称';
    protected $tableColumns         = 'true,false,false,false,false,false';
    protected $listTitle            = '游戏消耗统计';    
    protected $showTitle            = '游戏消耗统计详情';    
    protected $dateFilter           = false;

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'game_name'         => 'gamename',
            'guild_consume'     => 'guildconsume',
            'other_consume'     => 'otherconsume',
            'alone_user'        => 'aloneuser',
        );  
        $object['list_fields'] = array(
            'id'                => 'ID',
            'game_name'         => '游戏名称', 
            'guild_consume'     => '公会消耗8豆',
            'other_consume'     => '其他消耗8豆',
            'alone_user'        => '独立用户数',
            'op'                => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',    
            'game_name'         => '游戏名称', 
            'guild_consume'     => '公会消耗8豆',
            'other_consume'     => '其他消耗8豆',
            'alone_user'        => '独立用户数',
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
