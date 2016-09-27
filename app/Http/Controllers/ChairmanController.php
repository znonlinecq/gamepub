<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use App\Models\Guild\Guild;
use App\Models\Guild\GuildToGuild;
use App\Models\Guild\GuildToGame;
use App\Models\Guild\BlackList;
use App\Models\Game;
use App\User;
use DB;
use App\Models\GameType;
use App\Models\GameClass;

class ChairmanController extends Controller
{    
    private $moduleRoute = 'chairmans';             //路由URL
    private $moduleView = 'chairman';    //视图路径
    private $moduleTable = '';
    private $moduleName = '会长';
    private $moduleIndexAjax = '/chairmans/index_ajax';

    private $searchPlaceholder = '会长姓名';

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
        return view($this->moduleView.'/index', [ 'title'=>'会长审核', 'type'=>'index']);
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
        $conditions     = array();

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
        $conditions [] = "GuilderId = 0";
        if($type == 'game')
        {
            $conditions[] = " AuditStatus = 1 ";
        }
        if($searchValue)
        {
            $conditions[] = " Name like '%{$searchValue}%' ";
        }
        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " CreateDate >= {$fromTimestamp} AND CreateDate <= {$toTimestamp}";
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
        if(count($games))
        {
            foreach($games as $game)
            {
                $type   = GameType::find($game->Typeid);
                $class  = GameClass::find($game->Classid);
                $game->typeName     = $type->Typename;
                $game->className    = $class->Classname;
                $objects[] = $game;
            }
        }
        return view($this->moduleView.'/game_authorization_form', ['title'=>'游戏授权', 'objects'=>$objects, 'chairman'=>$chairman]);    
 
    }

    public function game_authorization_form_submit(Request $requests)
    {
        $request = $requests->all();
        $id = $request['id'];
        if(!isset($request['gids'])) 
        {
              return redirect($this->moduleRoute.'/game_authorization_form/'.$id)->with('message', '没有选择游戏!');
        }
        $gids = $request['gids'];
        $gidsOldNew = array();
        $gidsOld = DB::select("SELECT AppId FROM dt_guild_togames WHERE GuildId = {$id} AND AuditStatus = 1");
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
            GuildToGame::where('GuildId', $id)->where('AppId', $gidUpdate)->update(['AuditStatus'=>0]); 

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
            $model = GuildToGame::where('GuildId', $id)->where('AppId', $gidInsert)->get();
            if(count($model))
            {
                GuildToGame::where('GuildId', $id)->where('AppId', $gidInsert)->update(['AuditStatus'=>1]);    
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
                $object->GuildId = $id;
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
         return view($this->moduleView.'/index', [ 'title'=>'游戏授权', 'type'=>'game']);   
    }
    
    public function blacklist()
    {
         return view($this->moduleView.'/blacklist', [ 'title'=>'公会黑名单']);   
    }
    
    public function blacklist_ajax(Request $request)
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
            8=>'CreateDate',
        );
        $orderColumnsStr = $orderColumns[$orderNumber];

        $sql = " select * from dt_guild_list ";
        $conditions[] = "GuilderId = 0";
        $conditions[] = "GuildType = 1";
        $conditions[] = " AuditStatus IN(1,3)";

        if($searchValue)
        {
            $conditions[] = " Name like '%{$searchValue}%' ";
        }

        if($fromTimestamp && $toTimestamp)
        {
            $conditions[] = " CreateDate >= {$fromTimestamp} AND CreateDate <= {$toTimestamp}";
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
                $object[] = $result->Id;        
                $object[] = '13511112222';
                $object[] = $result->UserId;
                $object[] = '诛仙';
                $object[] = $result->Name;
                $object[] = '11010218761211222236';
                $object[] = '8172287282';
                $object[] = date('Y-m-d H:i:s', $result->CreateDate);
                if($result->AuditStatus == 3)
                {
                    $object[] = '黑名单';
                }elseif($result->AuditStatus == 1)
                {
                    $object[] = '正常';
                }elseif($result->AuditStatus == 2)
                {
                    $object[] = '驳回';
                }
                if($result->AuditStatus == 1)
                {
                    $object[] = '<a href="'.url('chairmans/blacklist_join_form/'.$result->Id).'">加入</a>';
                }elseif($result->AuditStatus == 3){
                    $object[] = '<a href="'.url('chairmans/blacklist_out_form/'.$result->Id).'">解除</a>';
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
    
    public function blacklist_join_form($id)
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
            $object->status = '正常';
        }elseif($object->AuditStatus == 2)
        {
            $object->status = '驳回';
        }

        return view('chairman/blacklist_join_form', ['object'=>$object, 'title'=>'工会黑名单', 'moduleRoute'=>$this->moduleRoute]);
    
    }
    
    public function blacklist_out_form($id)
    {
        $object = Guild::find($id);
        $object->acount = '13511112222';
        $object->games = '诛仙';
        $object->namecard = '11010211111111111';
        $object->qq = '768767282';
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        $object->status = '黑名单';

        return view('chairman/blacklist_out_form', ['object'=>$object, 'title'=>'公会黑名单', 'moduleRoute'=>$this->moduleRoute]);
    
    }

    public function blacklist_join_form_submit(Request $request)
    {
        $gid = $request->gid;
        $childs = GuildToGuild::getChilds($gid);
        $description = $request->description;
        $auditStatus = 3;
        $summary = $description;
        $updated = time();
    
        DB::update("update dt_guild_list set AuditStatus={$auditStatus}, UpdateDate={$updated}  where id = {$gid}");
        
        //记录黑名单
        if(count($childs))
        {
            foreach($childs as $child)
            {
                DB::update("update dt_guild_list set AuditStatus=3, UpdateDate={$updated}  where id = {$child}");
            }
            $childsStr = serialize($childs);
        }
        else
        {
            $childsStr = NULL;
        }
        $blacklist = new BlackList();
        $blacklist->guildid = $gid;
        $blacklist->guildidlist = $childsStr;
        $blacklist->createdate = time();
        $blacklist->save();

        //日志
        $params['module']       = __CLASS__;
        $params['function']     = __FUNCTION__;
        $params['operation']    = '加入';
        $params['object']       = $gid;
        $params['content']      = $description;
        Log::record($params);
        return redirect($this->moduleRoute.'/blacklist')->with('message', '加入黑名单!');
    }

    public function blacklist_out_form_submit(Request $request)
    {
        $gid = $request->gid;
        $description = $request->description;
        $auditStatus = 1;
        $summary = $description;
        $updated = time();

        DB::update("update dt_guild_list set AuditStatus={$auditStatus}, UpdateDate={$updated}  where id = {$gid}");

        $blacklist  = BlackList::where('guildid', $gid)->get();
        if(!empty($blacklist[0]->guildidlist))
        {
            $childs = unserialize($blacklist[0]->guildidlist); 
            foreach($childs as $child)
            {
                DB::update("update dt_guild_list set AuditStatus=1, UpdateDate={$updated}  where id = {$child}");
            }
        }    
        BlackList::where('guildid', $gid)->delete();

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = '解除';
        $params['object'] = $gid;
        $params['content'] = $description;
        Log::record($params);
        return redirect($this->moduleRoute.'/blacklist')->with('message', '解除黑名单!');
    }

}
