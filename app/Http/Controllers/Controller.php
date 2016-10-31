<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Menu;
use App\Models\Guild\Guild;
use View;
use Auth;
use Illuminate\Http\Request as RequestNew;
use DB;
use Request;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $breadcrumbs;
    protected $modelName;                   //模型名称
    protected $table;                       //数据表名
    protected $database;                    //数据库名
    protected $proceduce;                   //存储过程名
    protected $connection;                  //数据库连接
    protected $search_keyword;              //搜索关键词字段
    protected $search_datetime;             //搜索日期字段
    protected $moduleRoute;                 //url路径
    protected $moduleAjax;                  //列表页ajax路径
    protected $searchPlaceholder;           //搜索占位符
    protected $listTitle;                   //列表页标题
    protected $showTitle;                   //详情页标题   
    protected $moduleIndexAjax;             //index列表 回调url
    protected $moduleView='pages';                  //模板路径
    protected $showType = false;            //详情是否传递类型参数
    protected $advanceSearchFields;             //高级搜索字段
    protected $searchBox;                       //搜索框
    protected $advanceSearchBox     = NULL;     //高级搜索框    
    protected $isAdvanceSearch      = false;    //是否开启高级搜索
    protected $op;                          //操作
    protected $isSourceData = true;        //是否调用统计存储过程
    protected $type;                        

    protected $dataFormat   = 1;            //1='Y-m-d' 2='Y-m-d H:i:s'
    protected $languageUrl  = '/chinese.json';
    protected $localUrl     = 'http://localhost/gamepub/public';
    protected $tableOrder   = '0, desc';
    protected $tableColumns = 'true,false,false,true,false,false,false,false,true,false';
    protected $dateFilter   = true;

    public function __construct()
    {
        $this->middleware('auth');
   //     if(!Auth::check())
   //     {
   //         return redirect('auth/login')->with('message', '没有登录!');
   //         print_r('aaa'); die();
   //     }
        //View::composer('*', function ($view) {
        //    $breadcrumbs = array(
        //        '/' => 'Dashboard'
        //    );
        //    $view->with('breadcrumbs', $breadcrumbs);
        //});
        View::composer('layouts/sidebar', function($view){
            $guild = count(Guild::where('GuildType', 0)->where('AuditStatus',2)->get());

            $menuCount['ChairmanController'] = $guild;
            $menus = Menu::menuLoad();
            $view->with('menus', $menus);
            $view->with('menuCount', $menuCount);
        });
        $this->advanceSearchFields = $this->setAdvanceSearchFields();
        $uri = Request::path();
        if(preg_match('/game_authorization/', $uri))
        {
            $this->showTitle    = '游戏授权';
            $this->listTitle    = '游戏授权';
            $this->type = 'game_authorization';
        }
        elseif(preg_match('/blacklist/', $uri))
        {
            $this->showTitle    = '公会黑名单';
            $this->listTitle    = '公会黑名单';
            $this->type = 'blacklist';
        }
        else
        {
            $this->type = 'default';
        }
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('languageUrl',          $this->languageUrl);
            $view->with('localUrl',             $this->localUrl);
            $view->with('moduleRoute',          $this->moduleRoute);
            $view->with('moduleAjax',           $this->moduleAjax);
            $view->with('searchPlaceholder',    $this->searchPlaceholder);
            $view->with('tableOrder',           $this->tableOrder);
            $view->with('tableColumns',         $this->tableColumns);
            $view->with('dateFilter',           $this->dateFilter);
            $view->with('moduleIndexAjax',      $this->moduleIndexAjax);
            $view->with('moduleView',           $this->moduleView);
            $view->with('searchBox',            $this->setSearchBox());
            $view->with('advanceSearchBox',     $this->setAdvanceSearchBox());
            $view->with('advanceSearchFields',  $this->advanceSearchFields);
            $view->with('isAdvanceSearch',      $this->isAdvanceSearch);
            $view->with('type',                 $this->type);
        }); 

        //设置操作
        $this->op = $this->setOp();
        
 
    }

    public function set_breadcrumbs($data)
    {
        foreach($data AS $key => $value)
        {
            $this->breadcrumbs[$key] = $value;
        }
    }
    
    public function get_breadcrumbs()
    {
        return $this->breadcrumbs;
    }

    public function page_empty()
    {
        abort(503);
    }
    
    /**
     * 提交GET请求，curl方法
     * @param string  $url	   请求url地址
     * @param mixed   $data	  GET数据,数组或类似id=1&k1=v1
     * @param array   $header	头信息
     * @param int	 $timeout   超时时间
     * @param int	 $port	  端口号
     * @return array			 请求结果,
     *							如果出错,返回结果为array('error'=>'','result'=>''),
     *							未出错，返回结果为array('result'=>''),
     */
    function curl_get($url, $data = array(), $header = array(), $timeout = 5, $port = 80)
    {
        $ch = curl_init();
        if (!empty($data)) {
            $data = is_array($data)?http_build_query($data): $data;
            $url .= (strpos($url,'?')?  '&': "?") . $data;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 0);
        //curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1); //是否抓取跳转后的页面
        $result = curl_exec($ch);
        if (0 != curl_errno($ch)) {
            $result = "Error:\n" . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    /**
     * 提交POST请求，curl方法
     * @param string  $url	   请求url地址
     * @param mixed   $data	  POST数据,数组或类似id=1&k1=v1
     * @param array   $header	头信息
     * @param int	 $timeout   超时时间
     * @param int	 $port	  端口号
     * @return string			请求结果,
     *							如果出错,返回结果为array('error'=>'','result'=>''),
     *							未出错，返回结果为array('result'=>''),
     */
    function curl_post($url, $data = array(), $header = array(), $timeout = 5, $port = 80)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        //curl_setopt($ch, CURLOPT_PORT, $port);
        !empty ($header) && curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (0 != curl_errno($ch)) {
            $result  = "Error:\n" . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    
    protected function dataObject()
    {
    }

    public function source_data()
    {
    }

    public function index()
    {
        if($this->isSourceData)
        {
            $this->source_data();
        }

        $dataObject = $this->dataObject();
        $list_fields = $dataObject['list_fields'];
        foreach($list_fields as $key => $value)
        {
            $titles[] = $value;
        }
        return view('pages/list', ['title'=>$this->listTitle, 'tableTitles'=>$titles]);
    }

    public function index_ajax(RequestNew $request)
    {
        $requests       = $request->all();
        $draw           = $requests['draw'];
        $columns        = $requests['columns'];
        $start          = $requests['start'];
        $length         = $requests['length'];
        $type           = $requests['type'];
        $this->type     = $type;
        if(isset($requests['searchKeyword']))
        {
            $searchValue    = trim($requests['searchKeyword']);
        }
        else
        {
            $searchValue    = '';
        }    
        if(isset($requests['searchFields']))
        {
            $searchFields    = $requests['searchFields'];
        }
        else
        {
            $searchFields    = NUll;
        }    


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
            if($this->dataFormat == 3)
            {
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
                $from           = mktime($fromHour, $fromMinute, $fromSecond, $fromMonth, $fromDay, $fromYear);
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
                $to             = mktime($toHour, $toMinute, $toSecond, $toMonth, $toDay, $toYear);
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
        $searchConditions = $this->setSearchConditions($type);
        if(count($searchConditions))
        {
            $conditions = $searchConditions;
        }
        if($searchValue)
        {
            $conditions[] = " {$this->search_keyword} like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " ({$this->search_datetime} BETWEEN  '{$from}' AND '{$to}') ";
        }
        if($searchFields)
        {
            $searchFieldsOp = $this->advanceSearchFields;
            $searchFieldsOp = json_decode($searchFieldsOp);
            foreach($searchFields as $searchField)
            {
                $key = key($searchField);

                $value = trim(current($searchField));
                if($searchFieldsOp->$key == 'like' && !empty($value))
                {
                    $conditions[] = " {$key} like '%{$value}%' ";
                }
                if($searchFieldsOp->$key == '=string' && !empty($value))
                {
                    $conditions[] = " {$key} = '{$value}' ";
                } 
                if($searchFieldsOp->$key == '=int' && $value != 99)
                {
                    $conditions[] = " {$key} = {$value} ";
                }
            } 
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
                        if(preg_match('/^empty/', $key))
                        {
                            $object[] = '暂无';
                        }
                        else
                        {
                            $object[] = $this->dataFilter($key, $result->$key);
                        }
                    }
                }

                $op = $this->op;
                if(count($op) > 1)
                {
                    $opStrs = array();
                    foreach($op as $key => $value)
                    {   
                        $url = $value['url'];
                        $field = $value['field'];
                        $name = $value['name'];
                        $path = url($this->moduleRoute.$url.$result->$field);
                        $opStrs[] = '<a href="'.$path.'">'.$name.'</a>';
                    }
                    $opStr = implode(' | ', $opStrs);
                }
                else
                {
                    $url = $op[0]['url'];
                    $field = $op[0]['field'];
                    $name = $op[0]['name'];
                    $path = url($this->moduleRoute.$url.$result->$field);
                    $opStr = '<a href="'.$path.'">'.$name.'</a>'; 
                }
                $object[] = $this->dataFilter('op', $opStr, $result);
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
        $show_fields = $dataObject['show_fields'];
        foreach($show_fields as $key => $value)
        {
            if(preg_match('/^empty/', $key))
            {
                $object->$key = '暂无';
            }
            else
            {
                $object->$key = $this->dataFilter($key, $object->$key, $object);
            }

        }
        return view('pages/show', ['object'=>$object, 'title'=>$this->showTitle, 'fields'=>$show_fields]);
    }
    
    public function setSearchBox()
    {
        $str = '<div class="search_wrapper" style="text-align:right;">';
        $str .='<input type="text" placeholder="'.$this->searchPlaceholder.'" id="searchKeyword" class="form-control">';
        $str .=' <button type="button" class="btn btn-default" id="searchSubmit">搜索</button>';
        if($this->isAdvanceSearch)
        {
            $str .=' <button type="button" class="btn btn-default" title="高级查询" id="advanceSearchButton">高级</button>'; 
        }
        $str .='</div>';
        return $str; 
    }
    
    public function setAdvanceSearchBox(){}  
    public function setAdvanceSearchFields(){}
    public function setOp(){
        $op = array(
            array(
                'name' => '详情',
                'url'   => '/',
                'field' => 'Id',
            ),
        );
        return $op;       
    }

    public function dataFilter($field, $data, $object=NULL){return $data;}
    public function setSearchConditions($type){return array();}
}
