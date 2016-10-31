<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Developer;
use Auth;
use App\Models\Log;
use App\User;

class DeveloperController extends Controller
{
    protected $moduleRoute = 'developers';  //路由URL
    protected $moduleAjax = '/developers/index_ajax';
    protected $listTitle            = '开发者审核';    
    protected $showTitle            = '开发者审核';    
 
    protected $modelName            = 'App\Models\Developer';
    protected $table                = 'game_cpinfo';
    protected $search_keyword       = 'username';
    protected $searchPlaceholder    = '开发者名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,true,false,false';
    protected $isDataObject         = false;
    protected $dataFormat           = 2;
    protected $search_datetime      = 'adddate';  
    protected $isAdvanceSearch      = true;
    protected $moduleViewChild      = 'develop';

    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleViewChild.'/*', function ($view) {
            $view->with('moduleRoute',          $this->moduleRoute);
        }); 
    }
    
    protected function dataObject()
    {
        $object['list_fields'] = array(
            'cpid'                  => 'ID',
            'username'              => '开发者姓名',
            'compname'              => '公司名称',
            'compweb'               => '公司网址',
            'compaddr'              => '公司地址',
            'certificateno'         => '营业执照号',
            'taxno'                 => '税号',
            'adddate'               => '注册时间',
            'status'                => '状态',
            'op'                    => '操作',
        ); 
        $object['show_fields'] = array(
            'cpid'                  => 'ID',
            'username'              => '开发者姓名',
            'compname'              => '公司名称',
            'compweb'               => '公司网址',
            'compaddr'              => '公司地址',
            'certificateno'         => '营业执照号',
            'certificateimg'        => '营业执照', 
            'taxno'                 => '税号',
            'taximg'                => '税务登记证件',
            'conname'               => '联系人姓名',
            'conposition'           => '联系人职务',
            'conmobile'             => '联系人手机号',
            'conqq'                 => '联机人QQ',
            'conemail'              => '联机人邮箱',
            'status'                => '状态',
            'adddate'               => '注册时间',
            'lastupdate'            => '最后更新时间',
            'checkuserid'           => '审核人',
            'checkdate'             => '审核时间',
        );
        return $object;
    } 
    public function index_backup()
    {
        return view($this->moduleView.'/index', ['title'=>'开发者审核']);
    }

    public function index_ajax_backup(Request $request)
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
            $to             = trim($dateRange[1]);
        }
        else
        {
            $from  = NULL;
            $to    = NULL;
        }

        $orderColumns = array(
            0=>'cpid', 
            7=>'adddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from {$this->moduleTable} ";
        if($searchValue)
        {
            $conditions[] = " compname like '%{$searchValue}%' ";
        }
        if($from && $to)
        {
            $conditions[] = " (adddate BETWEEN  '{$from}' AND '{$to}') ";
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
                if($result->status == 0)
                {
                    $status = '待审核';
                    $op = '<a href="'.url('developers/audit_form/'.$result->cpid).'">审核</a>';
                }elseif($result->status == 1)
                {
                    $status = '通过';
                    $op = '';
                }elseif($result->status == 2)
                {
                    $status = '驳回';
                    $op = '';
                }
                $object = array();
                $object[] = $result->cpid;
                $object[] = $result->username;
                $object[] = $result->compname;
                $object[] = $result->compweb;
                $object[] = $result->compaddr;
                $object[] = $result->certificateno;
                $object[] = $result->taxno;
                $object[] = $result->adddate;
                $object[] = $status;
                $object[] = $op;

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

    public function dataFilter($field, $data, $object=NULL)
    {
        switch($field)
        {
            case 'status':
                if($data == 0)
                {
                    $value =  '驳回';
                }
                elseif($data == 1)
                {
                    $value = '通过';
                }
                elseif($data == 2)
                {
                    $value = '待审核';
                }
                elseif($data == 3)
                {
                    $value = '黑名单';
                }
                
                break; 
            case 'op':
                if($object->status == 2)
                {
                    $value = '<a href="'.url($this->moduleRoute.'/audit_form/'.$object->cpid).'" class="btn btn-success btn-xs">审核</a>';
                } 
                else
                {
                    $value = '<a href="'.url($this->moduleRoute.'/show/'.$object->cpid).'">详情</a>';
                }
                break;
            case 'certificateimg':
                $value = '<img src="'.$data.'">';
                break;
            case 'taximg':
                $value = '<img src="'.$data.'">';
                break;
            case 'checkuserid':
                $user = User::find($data);
                $value = $user->name;
                break;
            default:
                $value = $data;
                break;
        }

        return $value;
    } 
    
    public function setAdvanceSearchBox()
    {
        $str = '<p><div class="advance_search_wrapper " style="display:none; height:50px; width:100%;" id="advance_search_wrapper"><pre>';
        $str .='开发者名称: <input type="text" id="username" class="form-control">&nbsp;&nbsp;';
        $str .='公司名称: <input type="text" id="compname" class="form-control">&nbsp;&nbsp;';
        $str .='营业执照号: <input type="text" id="certificateno" class="form-control">&nbsp;&nbsp;';
        $str .='税号: <input type="text" id="taxno" class="form-control">&nbsp;&nbsp;';
        $str .='状态: <select id="status" class="form-control"><option value="99"> - All - </option><option value="1">通过</option><option value="0">驳回</option><option value="2">待审核</option></select>&nbsp;&nbsp;';
        $str .=' <button type="button" class="btn btn-default" id="advanceSearchSubmit">搜索</button>';
        $str .='</pre></div></p>';
        return $str; 
    }

    public function setAdvanceSearchFields()
    {
        return json_encode(
            array(
                'username'=> 'like', 
                'compname'=>'like', 
                'certificateno'=>'like',
                'taxno'=>'like',
                'status'=>'=int' 
            )
        );
    }
    
    public function setOp(){
        $op = array(
            array(
                'name' => '详情',
                'url'   => '/',
                'field' => 'cpid',
            ),
        );
        return $op;       
    }


    public function audit_form($id)
    {
        
        $object = Developer::find($id);
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        if($object->status == 0)
        {
            $object->status = '待审核';
        }elseif($object->status == 1)
        {
            $object->status = '通过';
        }elseif($object->status == 2)
        {
            $object->status = '驳回';
        }

        return view($this->moduleView.'/audit_form', ['object'=>$object, 'title'=>'开发者审核']);
    
    }

    public function audit_form_submit(Request $request)
    {
        $user = Auth::user();

        $cpid = $request->cpid;
        $submit = $request->submit;
        $description = $request->description;
        
        if($submit == 'yes')
        {
            $auditStatus = 1;
        }else{
            $auditStatus = 2;
        }
        $summary = $description;
        $updated = date('Y-m-d H:i:s', time());

        DB::update("update {$this->moduleTable} set checkuserid={$user->id}, status={$auditStatus}, checkdate='{$updated}'  where cpid = {$cpid}");

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = $cpid;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }

}
