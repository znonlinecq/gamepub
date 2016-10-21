<?php

namespace App\Http\Controllers\Guild;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use DB;

class StatisticBaseController extends Controller
{
    protected $modelName;                   //模型名称
    protected $table;                       //数据表名
    protected $database;                    //数据库名
    protected $proceduce;                   //存储过程名
    protected $connection;                  //数据库连接
    protected $search_keyword;              //搜索关键词字段
    protected $search_datetime;             //搜索日期字段
    protected $moduleRoute;                 //url路径
    protected $moduleAjax;                  //列表页ajax路径
    protected $searchPlaceHolder;           //搜索占位符
    protected $listTitle;                   //列表页标题
    protected $showTitle;                   //详情页标题   
    
    protected $dataFormat   = 1;            //1='Y-m-d' 2='Y-m-d H:i:s'
    protected $languageUrl  = '/chinese.json';
    protected $localUrl     = 'http://localhost/gamepub/public';
    protected $tableOrder   = '0, asc';
    protected $tableColumns = 'true,false,false,true,false,false,false,false,true,false';
    protected $dateFilter   = true;

    public function __construct()
    {
        parent::__construct();
        View::composer('pages/*', function ($view) {
            $view->with('languageUrl',          $this->languageUrl);
            $view->with('localUrl',             $this->localUrl);
            $view->with('moduleRoute',          $this->moduleRoute);
            $view->with('moduleAjax',           $this->moduleAjax);
            $view->with('searchPlaceholder',    $this->searchPlaceHolder);
            $view->with('tableOrder',           $this->tableOrder);
            $view->with('tableColumns',         $this->tableColumns);
            $view->with('dateFilter',           $this->dateFilter);
        }); 
    }

    protected function dataObject()
    {
    }

    public function source_data()
    {
    }

    public function index()
    {
        $this->source_data();
        $dataObject = $this->dataObject();
        $list_fields = $dataObject['list_fields'];
        foreach($list_fields as $key => $value)
        {
            $titles[] = $value;
        }
        return view('pages/list', ['title'=>$this->listTitle, 'tableTitles'=>$titles]);
    }

    public function index_ajax(Request $request)
    {
        $requests       = $request->all();
        $draw           = $requests['draw'];
        $columns        = $requests['columns'];
        $start          = $requests['start'];
        $length         = $requests['length'];
        $search         = $requests['search'];
        $searchValue    = trim($search['value']);
        $order          = $requests['order'];
        $orderNumber    = $order[0]['column'];
        $orderDir       = $order[0]['dir'];
        $conditions     = array();

        if(!empty($requests['dateRange']))
        {
            $dateRange      = $requests['dateRange'];
            $dateRange      = explode('-', $dateRange);
            if($this->dataFormat == 1)
            {
                $from           = trim($dateRange[0]);
                $from           = str_replace('/', '-', $from);
                $from           = explode(' ', $from);
                $from           = $from[0];
                $to             = trim($dateRange[1]);
                $to             = str_replace('/', '-', $to); 
                $to             = explode(' ', $to);
                $to             = $to[0];

            }
            if($this->dataFormat == 2)
            {
                $from           = trim($dateRange[0]);
                $from           = str_replace('/', '-', $from);
                $to             = trim($dateRange[1]);
                $to             = str_replace('/', '-', $to);
            }
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

        $dataObject = $this->dataObject();
        $list_fields = $dataObject['list_fields'];
        foreach($list_fields as $key => $value)
        {
            $orderColumns[] = $key;
        }
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->table} ";
        if($searchValue)
        {
            $conditions[] = " {$this->search_keyword} like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " ({$this->search_datetime} BETWEEN  '{$from}' AND '{$to}') ";
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
                $object = array();
                foreach($list_fields as $key => $value)
                {
                    if($key != 'op')
                    {
                        $object[] = $result->$key;
                    }
                }
                $object[] = '<a href="'.url($this->moduleRoute.'/'.$result->id).'">详情</a>';

                $objects['data'][] = $object;
            }    
        }
        else
        {
            for($i=0; $i<10; $i++)
            {
                if($i == 0)
                {
                    $array[] = '空';
                }
                else
                {
                    $array[] = '';
                }
            }
            $objects['data'][] = $array;
        }

        return json_encode($objects);
    }

    public function show($id)
    {
        $model  = new $this->modelName();
        $object  = $model::find($id); 
        $dataObject = $this->dataObject();
        $list_fields = $dataObject['show_fields'];
        return view('pages/show', ['object'=>$object, 'title'=>$this->showTitle, 'fields'=>$list_fields]);
    }
}
