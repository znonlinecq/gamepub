<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\Models\Guild\Guild;
use App\Models\Guild\GuildToGuild;
use App\Models\Guild\GuildToGame;
use App\Models\Game;
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


    public function index()
    {
        return view($this->moduleView.'/list_not_audit', [ 'title'=>'会长审核', 'type'=>'index']);
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
        if($type == 1)
        {
            DB::update("update dt_guild_toguild set GuildType={$type} , Guild_A={$gid}, Guild_B=0  where GuildId = {$gid}");
        }
        else
        {
            DB::update("update dt_guild_toguild set GuildType={$type} , Guild_A=0, Guild_B={$gid} where GuildId = {$gid}");
        }

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $submit;
        $params['object'] = $gid;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
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
        $type           = $requests['type']; 
        $order          = $requests['order'];
        $orderNumber    = $order[0]['column'];
        $orderDir       = $order[0]['dir'];

        if(!empty($requests['dateRange']))
        {
            $dateRange      = $requests['dateRange'];
            $dateRange      = explode('-', $dateRange);
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
            $fromTimestamp  = mktime($fromHour, $fromMinute, $fromSecond, $fromMonth, $fromDay, $fromYear);
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
            $toTimestamp    = mktime($toHour, $toMinute, $toSecond, $toMonth, $toDay, $toYear);
        }
        else
        {
            $fromTimestamp  = NULL;
            $toTimestamp    = NULL;
        }

        $orderColumns = array(
            0=>'Id', 
            7=>'CreateDate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from dt_guild_list ";
        $sql .= " WHERE GuilderId = 0 ";
        if($type == 'game')
        {
            $sql .= " AND AuditStatus = 1 ";
        }
        if($searchValue)
        {
            $sql .= " AND Name like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $sql .= " AND CreateDate >= {$fromTimestamp} AND CreateDate <= {$toTimestamp}";
        }
        $sql .= " ORDER BY {$orderColumnsStr} {$orderDir}";
        $sql .= " LIMIT {$start}, {$length} ";

        $results = DB::select($sql);
        //Count
        $sqlCount = "SELECT COUNT(*) as total FROM dt_guild_list ";
        $sqlCount .= "WHERE GuilderId = 0 ";
        if($type == 'game')
        {
            $sqlCount .= ' AND AuditStatus = 1 ';
        }
        if($searchValue)
        {
            $sqlCount .= " AND Name like '%{$searchValue}%' ";
        }  
        if($fromTimestamp && $toTimestamp)
        {
            $sqlCount .= " AND CreateDate >= {$fromTimestamp} AND CreateDate <= {$toTimestamp}";
        }
       
        $count = DB::select($sqlCount);
        $total = $count[0]->total;

        $objects = array();
        $objects['draw'] = $draw;
        $objects['recordsTotal'] = $total;
        $objects['recordsFiltered'] = $total;
        if(count($results))
        {
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
            if($type == 'game')
            {
                $object[] = '<a href="'.url('chairmans/game_authorization_form/'.$result->Id).'">授权</a>';
            }else{
                $object[] = '<a href="'.url('chairmans/audit_form/'.$result->Id).'">审核</a>';
            }
            $objects['data'][] = $object;
        }
        }
        else
        {
            $objects['data'][] = array('空',' ',' ',' ',' ',' ',' ',' ',' ', ' ');
        }
        return json_encode($objects);
    }

    public function game_authorization_form($id)
    {
        $permissionsHandle = array();
        $games    = Game::All();
        $chairman  = Guild::find($id);

        return view($this->moduleView.'/game_authorization_form', ['title'=>'游戏授权', 'objects'=>$games, 'chairman'=>$chairman]);    
 
    }

    public function game_authorization_form_submit(Request $requests)
    {
        $request = $requests->all();
        $id = $request['id'];
        $gids = $request['gids'];
        $gidsOld = DB::select("SELECT AppId FROM dt_guild_togames WHERE GuilderId = {$id} AND AuditStatus = 1");
        if(count($gidsOld))
        {
            foreach($gidsOld as $gidOld)
            {
                $gidsOldNew[] = $gidOld->AppId;
            }
        }
        $gidsUpdate = array_diff($gidsOldNew, $gids);
        $gidsInsert = array_diff($gids, $gidsOldNew);

        foreach($gidsUpdate as $gidUpdate)
        {
            GuildToGame::where('GuilderId', $id)->where('AppId', $gidUpdate)->update(['AuditStatus'=>0]); 

            $game = Game::find($gidUpdate);
            //日志
            $params['module'] = __CLASS__;
            $params['function'] = __FUNCTION__;
            $params['operation'] = '取消授权';
            $params['object'] = $id;
            $params['content'] = "取消工会对<<{$game->Gamename}>>的授权.";
            Log::record($params);
        }

        foreach($gidsInsert as $gidInsert)
        {
            $model = GuildToGame::where('GuilderId', $id)->where('AppId', $gidInsert)->get();
            if(count($model))
            {
                GuildToGame::where('GuilderId', $id)->where('AppId', $gidInsert)->update(['AuditStatus'=>1]);    
                $game = Game::find($gidInsert);
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '恢复授权';
                $params['object'] = $id;
                $params['content'] = "恢复工会对<<{$game->Gamename}>>的授权.";
                Log::record($params);
            }
            else
            {
                $object = new GuildToGame();
                $object->GuilderId = $id;
                $object->Appid = $gidInsert;
                $object->AuditStatus = 1;
                $object->CreateDate = time();
                $object->UpdateDate = time();
                $object->save();
                
                $game = Game::find($gidInsert);
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '新添授权';
                $params['object'] = $id;
                $params['content'] = "新添工会对<<{$game->Gamename}>>的授权.";
                Log::record($params);

            }
        }
        return redirect($this->moduleRoute.'/game_authorization_form/'.$id)->with('message', '审核完成!');
    }

    public function game_authorization()
    {
         return view($this->moduleView.'/list_not_audit', [ 'title'=>'游戏授权', 'type'=>'game']);   
    }
}
