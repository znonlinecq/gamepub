<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\Models\Guild\Guild;
use App\Models\Guild\GuildToGuild;
use App\User;
use DB;

class ChairmanController extends Controller
{    
    private $moduleRoute = 'chairmans';             //路由URL
    private $moduleView = 'chairman';    //视图路径
    private $moduleTable = '';
    private $moduleName = '会长';
    
    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleView.'/*', function ($view) {
            $view->with('moduleRoute', $this->moduleRoute);
            $view->with('moduleName', $this->moduleName);
        }); 
    }


    public function list_not_audit()
    {
      //  $guilds = Guild::all();
      //  foreach($guilds as $guild)
      //  {
      //      $guild->account = '13511112222';
      //      $guild->games = '诛仙';
      //      $guild->namecard = '11010211111111';
      //      $guild->qq = '72781827';
      //      $guild->created = date('Y-m-d H:i:s', $guild->CreateDate);
      //      
      //      if($guild->AuditStatus == 0)
      //      {
      //          $guild->status = '待审核';
      //      }elseif($guild->AuditStatus == 1)
      //      {
      //          $guild->status = '通过';
      //      }elseif($guild->AuditStatus == 2)
      //      {
      //          $guild->status = '驳回';
      //      }
      //      $objects[] = $guild;
      //  }
        return view($this->moduleView.'/list_not_audit', [ 'title'=>'会长审核']);
    }

    public function audit_form($id)
    {
        
        $object = Guild::find($id);
        $object->acount = '13511112222';
        $object->games = '诛仙';
        $object->namecard = '11010211111111111';
        $object->qq = '768767282';
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        $object->status = '待审';
        if($object->AuditStatus == 0)
        {
            $object->status = '待审核';
        }elseif($object->AuditStatus == 1)
        {
            $object->status = '通过';
        }elseif($object->AuditStatus == 2)
        {
            $object->status = '驳回';
        }

        return view('chairman/audit_form', ['object'=>$object, 'title'=>'会长审核', 'moduleRoute'=>$this->moduleRoute]);
    
    }

    public function audit_form_submit(Request $request)
    {
        $gid = $request->gid;
        $type = $request->type;
        $submit = $request->submit;
        $description = $request->description;
        
        if($submit == 'yes')
        {
            $auditStatus = 1;
        }else{
            $auditStatus = 2;
        }
        $summary = $description;
        $updated = time();

        DB::update("update dt_guild_list set GuildType={$type}, AuditStatus={$auditStatus}, Summary='{$summary}', UpdateDate={$updated}  where id = {$gid}");
        DB::update("update dt_guild_toguild set GuildType={$type} where GuildId = {$gid}");

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = '刘明';
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute.'/list_not_audit')->with('message', '审核完成!');
    }

    public function list_audit()
    {
    
    }

    public function game_authorization_form()
    {
    
    }

    public function game_authorization_form_submit()
    {
    
    }

    public function list_not_audit_ajax(Request $request)
    {
        $requests = $request->all();
        $draw       = $requests['draw'];
        $columns    = $requests['columns'];
        $start      = $requests['start'];
        $length     = $requests['length'];
        $search     = $requests['search'];
        $searchValue     = $search['value'];
        
        $order      = $requests['order'];
        $orderNumber = $order[0]['column'];
        $orderDir    = $order[0]['dir'];

        $orderColumns = array(
            0=>'Id', 
            7=>'CreateDate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from dt_guild_list ";
        if($searchValue)
        {
            $sql .= " WHERE Name like '%{$searchValue}%' ";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);

        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM dt_guild_list ";
        if($searchValue)
        {
            $sqlCount .= " WHERE Name like '%{$searchValue}%' ";
        }
        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;

        foreach($results as $result)
        {
            $object = array();
            $object[] = $result->Id;
            $object[] = '13511112222';
            $object[] = $result->UserId;
            $object[] = '诛仙';
            $object[] = $result->Name;
            $object[] = '11010218761211222236';
            $object[] = '8172287282';
            $object[] = date('Y-m-d H:i:s', $result->CreateDate);
            if($result->AuditStatus == 0)
            {
                $object[] = '待审核';
            }elseif($result->AuditStatus == 1)
            {
                $object[] = '通过';
            }elseif($result->AuditStatus == 2)
            {
                $object[] = '驳回';
            }
            $object[] = '<a href="'.url('chairmans/audit_form/'.$result->Id).'">审核</a>';

            $objects['data'][] = $object;
        }

        return json_encode($objects);
    }
}
