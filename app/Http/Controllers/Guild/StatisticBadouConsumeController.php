<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use DB;

class StatisticBadouConsumeController extends StatisticBaseController
{    
    protected $modelName            = 'App\Models\Guild\StatisticBadouConsume';
    protected $table                = 'ad_statistic_guild_badous_consumes';
    protected $database             = 'db_xfplatformcenter_statistics';
    protected $proceduce            = 'pro_guild_badouconsume_byguild_sel';
    protected $connection           = 'statistics';
    protected $search_keyword       = 'guild_name';
    protected $moduleRoute          = 'chairmans/statistic/badous_consumes';
    protected $moduleAjax           = '/chairmans/statistic/badous_consumes_ajax';
    protected $searchPlaceHolder    = '公会名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,false,false';
    protected $listTitle            = '8豆消耗统计';    
    protected $showTitle            = '8豆消耗统计详情';    
    protected $dateFilter           = false;

    protected function dataObject()
    {
        $object['handle_fields'] = array(
            'guild_name'        => 'name',
            'username'          => 'UserName',
            'level'             => 'guildtype',
            'sum'               => 'sumbadou',
            'direct_badou'      => 'directbadou',
            'give_badou'        => 'transbadou',
            'down_users'        => 'nextlines',
        );  
        $object['list_fields'] = array(
            'id'                => 'ID', 
            'guild_name'        => '公会名称',
            'username'          => '登录账号',
            'level'             => '公会等级',
            'sum'               => '充值8豆',
            'direct_badou'      => '直冲直销8豆',
            'give_badou'        => '转赠8豆',
            'down_users'        => '下行用户数',
            'op'                => '操作',
        ); 
        $object['show_fields'] = array(
            'id'                => 'ID',        
            'guild_name'        => '公会名称',
            'username'          => '登录账号',
            'level'             => '公会等级',
            'sum'               => '充值8豆',
            'direct_badou'      => '直冲直销8豆',
            'give_badou'        => '转赠8豆',
            'down_users'        => '下行用户数',
        );
        return $object;
    }

    public function source_data()
    {
        $dataObject = $this->dataObject();
        $handle_fields = $dataObject['handle_fields'];

        DB::delete("truncate {$this->table}");
        for($i=1;$i<3;$i++)
        {
            $level          = $i;                                   //公会等级 1,2
            $start          = '1970-01-01';                         //1970-01-01
            $end            = date('Y-m-d', time()+24*3600);        //2016-10-10
            $results        = DB::connection($this->connection)->select("call {$this->database}.{$this->proceduce}(?,?,?)", array($level, $start, $end));

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
}
