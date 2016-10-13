<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use DB;
use App\Models\Apk;
use App\Models\Game;
use App\Models\Developer;
use Auth;
use App\Models\Log;


class ApkController extends Controller
{
    private $moduleRoute    = 'apks';      //路由URL
    private $moduleView     = 'apk';       //视图路径
    private $moduleTable    = 'game_apkinfo';
    private $moduleName     = '游戏包';
    private $moduleIndexAjax = '/apks/index_ajax';
    private $searchPlaceholder = '游戏包名';   
    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName); 
            $view->with('moduleIndexAjax', $this->moduleIndexAjax);
            $view->with('searchPlaceholder', $this->searchPlaceholder);

        }); 
    }

    public function index()
    {
        return view($this->moduleView.'/index', ['title'=>$this->moduleName.'审核']);
    }

    public function index_ajax(Request $request)
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
            0=>'apkid', 
            7=>'Uploaddate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " SELECT * FROM {$this->moduleTable} ";
        if($searchValue)
        {
            $conditions[] = " Apkname like '%{$searchValue}%' ";
        }
 
        if($from && $to)
        {
            $conditions[] = " (Uploaddate BETWEEN  '{$from}' AND '{$to}') ";
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
                $game       = Game::where('Gameid', $result->Gameid)->get();
                if(count($game))
                {
                    $gameName = $game[0]->Gamename;
                }
                else
                {
                    $gameName = '未定义';
                }
            
                $developer  = Developer::where('cpid', $result->Cpid)->get();
                if(count($developer))
                {
                    $developerName = $developer[0]->username;
                }
                else
                {
                    $developerName = '未定义';
                }

                if($result->status == 0)
                {
                    $status = '待审核';
                }elseif($result->status == 1)
                {
                    $status = '通过';
                }elseif($result->status == 2)
                {
                    $status = '驳回';
                }

                if($result->Apktypeid == 0)
                {
                    $type = '新品';
                }elseif($result->Apktypeid == 1){
                    $type = '更新';
                }

                $object = array();
                $object[] = $result->apkid;
                $object[] = $result->Apkname;
                $object[] = $gameName;
                $object[] = $developerName;
                $object[] = $type;
                $object[] = $result->Opendowndate;
                $object[] = $result->OpeServndate;
                $object[] = $result->Uploaddate;
                $object[] = $status;
                $object[] = '<a href="'.url($this->moduleRoute.'/audit_form/'.$result->apkid).'">审核</a>';

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


    public function audit_form($id)
    {
        
        $object     = Apk::find($id); 
        $game       = Game::where('Gameid', $object->Gameid)->get();
        if(count($game))
        {
            $object->gameName = $game[0]->Gamename;
        }
        else
        {
            $object->gameName = '未定义';
        }

        $developer  = Developer::where('cpid', $object->Cpid)->get();
        if(count($developer))
        {
            $object->developerName = $developer[0]->username;
        }
        else
        {
            $object->developerName = '未定义';
        }


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

        if($object->Isself == 0)
        {
            $object->Isself = '独家代理';
        }else{
            $object->Isself = '自主研发';
        }
        if($object->Apktypeid == 0)
        {
            $object->type = '新品';
        }elseif($object->Apktypeid == 1){
            $object->type = '更新';
        }

        return view($this->moduleView.'/audit_form', ['object'=>$object, 'title'=>$this->moduleName.'审核']);
    
    }

    public function audit_form_submit(Request $request)
    {
        $user = Auth::user();

        $id = $request->id;
        $gid = $request->gid;
        $submit = $request->submit;
        $description = $request->description;
        
        if($submit == 'yes')
        {
            $status = 1;
        }else{
            $status = 2;
        }
        $summary = $description;
        $updated = date('Y-m-d H:i:s', time());

        DB::update("UPDATE {$this->moduleTable} set Checkuserid={$user->id}, status={$status}, checkdate='{$updated}'  where apkid = {$id}");
        
        //更新游戏表
        if($status == 1)
        {
            DB::update("UPDATE game_info set Apkid={$id}, isnewapk=0, checkdate='{$updated}'  where Gameid = {$gid}");
        }

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = $id;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }


}
