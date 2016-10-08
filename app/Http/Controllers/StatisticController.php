<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\User;
use DB;
use App\Models\Game;

class StatisticController extends Controller
{
    private $moduleRoute = 'statistics';             //路由URL
    private $moduleView = 'statistic';    //视图路径
    private $moduleTable = 'statistics';
    private $moduleName = '统计';

    private $searchPlaceholder = '游戏名称';

    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName);
            $view->with('searchPlaceholder', $this->searchPlaceholder);
        }); 
    }


    public function channel()
    {
        return view($this->moduleView.'/channel', [ 'title'=>'渠道统计']);
    }

    public function channel_ajax(Request $request)
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
            $fromStr        = trim($dateRange[0]);
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
            $toStr          = trim($dateRange[1]);
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
            0=>'id', 
            1=>'Adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_info ";
        $conditions [] = "status = 1";
        if($searchValue)
        {
            $conditions[] = " Gamename like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " Adddate >= {$fromStr} AND Adddate <= {$toStr}";
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
                $gid    = $result->id;
                $start  = '2016-01-01';
                $end    = '2017-01-01';
                $statistic = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_paltform_dataall_sel(?,?,?)",array($gid, $start, $end));

                $object = array();
                $object[] = $result->id;
                $object[] = '2016-8-11';
                $object[] = '三国志';
                $object[] = '666';
                $object[] = '666';
                $object[] = '66';
                $object[] = '6666';
                $object[] = '666';
                $objects['data'][] = $object;
            }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ', ' ');
        }
        return json_encode($objects);
    }

    public function game_income()
    {
        return view($this->moduleView.'/game_income', [ 'title'=>'游戏收入统计']);
    }

    public function game_income_ajax(Request $request)
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
            $fromStr        = trim($dateRange[0]);
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
            $toStr          = trim($dateRange[1]);
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
            0=>'id', 
            1=>'Adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_info ";
        $conditions [] = "status = 1";
        if($searchValue)
        {
            $conditions[] = " Gamename like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " Adddate >= {$fromStr} AND Adddate <= {$toStr}";
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
                $gid    = $result->id;
                $start  = '2016-01-01';
                $end    = '2017-01-01';
                $statistic = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_paltform_dataall_sel(?,?,?)",array($gid, $start, $end));

                $object = array();
                $object[] = $result->id;
                $object[] = '2016-8-11';
                $object[] = '三国志';
                $object[] = 'App/VR';
                $object[] = '666';
                $object[] = '66';
                $object[] = '6666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $objects['data'][] = $object;
            }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ', ' ', ' ', ' ');
        }
        return json_encode($objects);
    }

    public function game_user()
    {
        return view($this->moduleView.'/game_user', [ 'title'=>'游戏用户统计']);
    }

    public function game_user_ajax(Request $request)
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
            $fromStr        = trim($dateRange[0]);
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
            $toStr          = trim($dateRange[1]);
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
            0=>'id', 
            1=>'Adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_info ";
        $conditions [] = "status = 1";
        if($searchValue)
        {
            $conditions[] = " Gamename like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " Adddate >= {$fromStr} AND Adddate <= {$toStr}";
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
                $gid    = $result->id;
                $start  = '2016-01-01';
                $end    = '2017-01-01';
                $statistic = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_paltform_dataall_sel(?,?,?)",array($gid, $start, $end));

                $object = array();
                $object[] = $result->id;
                $object[] = '2016-8-11';
                $object[] = '三国志';
                $object[] = 'App/VR';
                $object[] = '666';
                $object[] = '66';
                $object[] = '6666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $objects['data'][] = $object;
            }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ', ' ',' ',' ',' ',' ',' ',' ',' ', ' ');
        }
        return json_encode($objects);
    }

   public function point()
    {
        return view($this->moduleView.'/point', [ 'title'=>'8豆监控']);
    }

    public function point_ajax(Request $request)
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
            $fromStr        = trim($dateRange[0]);
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
            $toStr          = trim($dateRange[1]);
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
            0=>'id', 
            1=>'Adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from game_info ";
        $conditions [] = "status = 1";
        if($searchValue)
        {
            $conditions[] = " Gamename like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " Adddate >= {$fromStr} AND Adddate <= {$toStr}";
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
                $gid    = $result->id;
                $start  = '2016-01-01';
                $end    = '2017-01-01';
                $statistic = DB::connection('statistics')->select("call db_xfplatformcenter_statistics.pro_paltform_dataall_sel(?,?,?)",array($gid, $start, $end));

                $object = array();
                $object[] = $result->id;
                $object[] = '2016-8-11';
                $object[] = '三国志';
                $object[] = 'App/VR';
                $object[] = '666';
                $object[] = '66';
                $object[] = '6666';
                $object[] = '666';
                $object[] = '666';
                $object[] = '666';
                $objects['data'][] = $object;
            }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ',' ',' ',' ');
        }
        return json_encode($objects);
    }

}
