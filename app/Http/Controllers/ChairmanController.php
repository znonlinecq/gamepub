<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request as RequestNew;

use View;
use App\Models\Guild\Guild;
use App\Models\Guild\GuildToGuild;
use App\Models\Guild\GuildToGame;
use App\Models\Guild\GuildToGameBlacklist;
use App\Models\Guild\BlackList;
use App\Models\Game;
use App\User;
use DB;
use App\Models\GameType;
use App\Models\GameClass;
use Request;

class ChairmanController extends Controller
{    
    protected $modelName            = 'App\Models\Chairman';
    protected $table                = 'dt_guild_list';
    protected $search_keyword       = 'Name';
    protected $searchPlaceholder    = '公会名称';
    protected $tableColumns         = 'true,false,false,false,false,false,false,true,false,false';
    protected $isDataObject         = false;
    protected $dataFormat           = 3;
    protected $search_datetime      = 'CreateDate';  
    protected $isAdvanceSearch      = true;
    protected $moduleViewChild      = 'chairman';

    public function __construct()
    {
        parent::__construct();
        View::composer($this->moduleViewChild.'/*', function ($view) {
            $view->with('moduleRoute',          $this->moduleRoute);
        }); 
    }
    
    public function type_list($type)
    {
        $object = ChairmanFactory::createObject($type);
        return $object->index();
    }

    public function type_list_ajax(RequestNew $requests,$type)
    {
        $object  = ChairmanFactory::createObject($type);
        $content = $object->index_ajax($requests);
        return $content;
    } 
    
    public function type_page_show($type,$page,$id)
    {
        $object = ChairmanFactory::createObject($type);
        return $object->$page($id);
    }
    
    public function type_page_submit($type, $page_submit)
    {
        $object = ChairmanFactory::createObject($type);
        $request = Request::all();
        return $object->$page_submit($request);
    }


    protected function dataObject()
    {
        $object['list_fields'] = array(
            'Id'                    => 'ID',
            'Name'                  => '公会名称',
            'UserName'              => '登录账号',
            'UserId'                => '登录账号ID',
            'empty'                 => '身份证',
            'empty1'                => 'QQ',
            'empty2'                => '推广游戏',
            'CreateDate'            => '注册时间',
            'AuditStatus'           => '状态',
            'op'                    => '操作',
        ); 
        $object['show_fields'] = array(
            'Id'                    => 'ID',
            'Name'                  => '公会名称',
            'UserName'              => '登录账号',
            'UserId'                => '登录账号ID',
            'empty'                 => '身份证',
            'empty1'                => 'QQ',
            'empty2'                => '推广游戏', 
            'AuditStatus'           => '状态',
            'CreateDate'            => '注册时间',
        );
        return $object;
    } 
}

class ChairmanAuditController extends ChairmanController
{
    protected $moduleRoute          = 'chairmans/audit';
    protected $moduleAjax           = '/chairmans/list_ajax/audit';
    protected $listTitle            = '公会审核';    
    protected $showTitle            = '公会审核';    
    
    public function dataFilter($field, $data, $object=NULL)
    {
        switch($field)
        {
            case 'AuditStatus':
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
                if($object->AuditStatus == 2)
                {
                    $value = '<a href="'.url($this->moduleRoute.'/audit_form/'.$object->Id).'" class="btn btn-success btn-xs">审核</a>';
                } 
                else
                {
                    $value = '<a href="'.url($this->moduleRoute.'/show/'.$object->Id).'">详情</a>';
                }
                break;
            case 'CreateDate':
                    $value = date('Y-m-d H:i:s', $data);
                break;
            case 'Id':
                $value = $data;
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
        $str .='公会名称: <input type="text" id="Name" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号: <input type="text" id="UserName" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号Id: <input type="text" id="UserId" class="form-control">&nbsp;&nbsp;';
        $str .='状态: <select id="AuditStatus" class="form-control"><option value="99"> - All - </option><option value="1">通过</option><option value="0">驳回</option><option value="2">待审核</option></select>&nbsp;&nbsp;';
        $str .=' <button type="button" class="btn btn-default" id="advanceSearchSubmit">搜索</button>';
        $str .='</pre></div></p>';
        return $str; 
    }

    public function setAdvanceSearchFields()
    {
        return json_encode(
            array(
                'name'=> 'like', 
                'username'=>'like', 
                'userid'=>'like',
                'AuditStatus'=>'=int' 
            )
        );
    }
    
    public function setSearchConditions($type)
    {
        $conditions = array();
        $conditions[] = ' GuildType IN (1,2) ';
        $conditions[] = ' Guilderid = 0 ';
        return $conditions;
    }


    public function audit_form($id)
    {
        
        $object = Guild::find($id);
        $object->games = '暂无';
        $object->namecard = '暂无';
        $object->qq = '暂无';
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        if($object->AuditStatus == 0)
        {
            $object->status = '驳回';
        }elseif($object->AuditStatus == 1)
        {
            $object->status = '通过';
        }elseif($object->AuditStatus == 2)
        {
            $object->status = '待审核';
        }

        return view('chairman/audit_form', ['object'=>$object, 'title'=>'会长审核', 'moduleRoute'=>$this->moduleRoute]);
    }

    public function audit_form_submit($request)
    {
        $gid = $request['gid'];
        $type = $request['type'];
        $submit = $request['submit'];
        $description = $request['description'];
        
        if($submit == 'yes')
        {
            $auditStatus = 1;
            $operation = '通过';
        }else{
            $auditStatus = 0;
            $operation = '驳回';
        }
        $summary = $description;
        $updated = time();
        
        if($type)
        {
            DB::update("update dt_guild_list set GuildType={$type}, AuditStatus={$auditStatus}, Summary='{$summary}', UpdateDate={$updated}  where id = {$gid}");
            if($type == 1)
            {
                DB::update("update dt_guild_toguild set GuildType={$type} , Guild_A={$gid}, Guild_B=0  where GuildId = {$gid}");
            }
            else
            {
                DB::update("update dt_guild_toguild set GuildType={$type} , Guild_A=0, Guild_B={$gid} where GuildId = {$gid}");
            }
        }
        else
        {
            DB::update("update dt_guild_list set AuditStatus={$auditStatus}, Summary='{$summary}', UpdateDate={$updated}  where id = {$gid}");
        }    

        //日志
        $params['module'] = __CLASS__;
        $params['function'] = __FUNCTION__;
        $params['operation'] = $operation;
        $params['object'] = $gid;
        $params['content'] = $description.'-'.$submit;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', '审核完成!');
    }

   
}

class ChairmanGameAuthorizationController extends ChairmanController
{
    protected $moduleRoute          = 'chairmans/game_authorization';
    protected $moduleAjax           = '/chairmans/list_ajax/game_authorization';
    protected $listTitle            = '游戏授权';    
    protected $showTitle            = '游戏授权';    
    
    public function dataFilter($field, $data, $object=NULL)
    {
        switch($field)
        {
            case 'AuditStatus':
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
                $value = '<a href="'.url($this->moduleRoute.'/game_authorization_form/'.$object->Id).'" >授权</a>';
                break;
            case 'CreateDate':
                    $value = date('Y-m-d H:i:s', $data);
                break;
            case 'Id':
                $value = $data;
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
        $str .='公会名称: <input type="text" id="Name" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号: <input type="text" id="UserName" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号Id: <input type="text" id="UserId" class="form-control">&nbsp;&nbsp;';
        $str .=' <button type="button" class="btn btn-default" id="advanceSearchSubmit">搜索</button>';
        $str .='</pre></div></p>';
        return $str; 
    }

    public function setAdvanceSearchFields()
    {
        return json_encode(
            array(
                'name'=> 'like', 
                'username'=>'like', 
                'userid'=>'like',
            )
        );
    }

    public function setSearchConditions($type)
    {
        $conditions = array();
        $conditions[] = ' AuditStatus = 1 ';
        $conditions[] = ' GuildType IN (1,2) ';
        $conditions[] = ' Guilderid = 0 ';
        return $conditions;
    }
   
    public function game_authorization_form($id)
    {
        $permissionsHandle = array();
        $games    = Game::where('status',1)->get();
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
        return view($this->moduleViewChild.'/game_authorization_form', ['title'=>'游戏授权', 'objects'=>$objects, 'chairman'=>$chairman]);    
 
    }

    public function game_authorization_form_submit($request)
    {
        $id = $request['id'];
        if(!isset($request['gids'])) 
        {
            $gids = array();
            //  return redirect($this->moduleRoute.'/game_authorization_form/'.$id)->with('message', '没有选择游戏!');
        }
        else
        {
            $gids = $request['gids'];
        }      
        $childs = GuildToGuild::getChilds($id);

        $gidsOldNew = array();
        $gidsOld = DB::select("SELECT AppId FROM dt_guild_togames WHERE GuildId = {$id} AND AuditStatus <> 0");
        

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

            $game = Game::where('Gameid', $gidUpdate)->get();
            //日志
            $params['module'] = __CLASS__;
            $params['function'] = __FUNCTION__;
            $params['operation'] = '取消授权';
            $params['object'] = $id;
            $params['content'] = "取消工会对<<{$game[0]->Gamename}>>的授权.";
            Log::record($params);
       
            //下属公会取消授权，加入黑名单列表
            if(!count($childs))
            {
                continue;
            }
            $childsExist = array();
            foreach($childs as $childId)
            {
                $hasChild = GuildToGame::where('GuildId', $childId)->where('AppId', $gidUpdate)->get();
                if(!count($hasChild))
                {
                    continue;
                }
                $childsExist[] = $childId;
                GuildToGame::where('GuildId', $childId)->where('AppId', $gidUpdate)->update(['AuditStatus'=>0]);
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '取消授权';
                $params['object'] = $childId;
                $params['content'] = "取消B级或C级工会对<<{$game[0]->Gamename}>>的授权.";
                Log::record($params);
            }

            if(!count($childsExist))
            {
                continue;
            }
            $childsStr = serialize($childsExist);
            $blacklist = new GuildToGameBlacklist();
            $blacklist->guildid = $id;
            $blacklist->guildidlist = $childsStr;
            $blacklist->createdate = time();
            $blacklist->save();
            //日志
            $params['module'] = __CLASS__;
            $params['function'] = __FUNCTION__;
            $params['operation'] = '取消授权-下属公会记录';
            $params['object'] = $id;
            $params['content'] = "下属公会ID {$childsStr}";
            Log::record($params);
        }

        foreach($gidsInsert as $gidInsert)
        {
            $model = GuildToGame::where('GuildId', $id)->where('AppId', $gidInsert)->get();
            if(count($model))
            {
                GuildToGame::where('GuildId', $id)->where('AppId', $gidInsert)->update(['AuditStatus'=>1, 'bagauditstatus'=>2]);    
                $game = Game::where('Gameid', $gidInsert)->get();
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '恢复授权';
                $params['object'] = $id;
                $params['content'] = "恢复工会对<<{$game[0]->Gamename}>>的授权.";
                Log::record($params);    
                
                //下属公会恢复授权，移除黑名单列表
                $childsBlacklist = GuildToGameBlacklist::where('guildid', $id)->get();
                if(!count($childsBlacklist))
                {
                    continue;
                }
                $guildidlist = unserialize($childsBlacklist[0]->guildidlist);
                foreach($guildidlist as $childId)
                {
                    GuildToGame::where('GuildId', $childId)->where('AppId', $gidInsert)->update(['AuditStatus'=>1]);
                    //日志
                    $params['module'] = __CLASS__;
                    $params['function'] = __FUNCTION__;
                    $params['operation'] = '恢复授权';
                    $params['object'] = $childId;
                    $params['content'] = "恢复B级或C级工会对<<{$game[0]->Gamename}>>的授权.";
                    Log::record($params);
                }

                $childsStr = serialize($childs);
                GuildToGameBlackList::where('guildid', $id)->delete();
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '恢复授权-下属公会记录';
                $params['object'] = $id;
                $params['content'] = "下属公会ID {$childsStr}";
                Log::record($params);
            }
            else
            {
                $object = new GuildToGame();
                $object->GuildId = $id;
                $object->Appid = $gidInsert;
                $object->AuditStatus = 1;
                $object->bagauditstatus = 2;
                $object->CreateDate = time();
                $object->UpdateDate = time();
                $object->save();
                
                $game = Game::where('Gameid', $gidInsert)->get();
                //日志
                $params['module'] = __CLASS__;
                $params['function'] = __FUNCTION__;
                $params['operation'] = '新添授权';
                $params['object'] = $id;
                $params['content'] = "新添工会对<<{$game[0]->Gamename}>>的授权.";
                Log::record($params);

            }
        }

        return redirect($this->moduleRoute.'/game_authorization_form/'.$id)->with('message', '授权完成!');
    }

    public function game_authorization()
    {
         return view($this->moduleView.'/index', [ 'title'=>'游戏授权', 'type'=>'game']);   
    }
      
}

class ChairmanBlacklistController extends ChairmanController
{
    protected $moduleRoute          = 'chairmans/blacklist';
    protected $moduleAjax           = '/chairmans/list_ajax/blacklist';
    protected $listTitle            = '公会黑名单';    
    protected $showTitle            = '公会黑名单';    
    
    public function blacklist_form($id)
    {
        
        $object = Guild::find($id);
        $object->games = '暂无';
        $object->namecard = '暂无';
        $object->qq = '暂无';
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
        if($object->AuditStatus == 0)
        {
            $object->status = '待审核';
        }
        elseif($object->AuditStatus == 1)
        {
            $object->status = '正常';
            $object->handleStatus = 3;
            $object->submit = '加入';
        }
        elseif($object->AuditStatus == 2)
        {
            $object->status = '驳回';
        }
        elseif($object->AuditStatus == 3)
        {
            $object->status = '黑名单'; 
            $object->handleStatus = 1;
            $object->submit = '移除';
        }

        return view($this->moduleViewChild.'/blacklist_form', ['object'=>$object, 'title'=>'公会黑名单', 'moduleRoute'=>$this->moduleRoute]);
    }
    
    public function blacklist_form_submit($request)
    {
        $gid = $request['gid'];
        $handleStatus = $request['handleStatus'];
        $childs = GuildToGuild::getChilds($gid);
        $description = $request['description'];
        $auditStatus = $handleStatus;
        $summary = $description;
        $updated = time();
        if($handleStatus == 3)
        {
            $operation = '加入';
        } 
        else
        {
            $operation = '移除';
        }

        DB::update("update dt_guild_list set AuditStatus={$auditStatus}, UpdateDate={$updated}  where id = {$gid}");
        
        //记录黑名单
        if(count($childs))
        {
            foreach($childs as $child)
            {
                DB::update("update dt_guild_list set AuditStatus={$auditStatus}, UpdateDate={$updated}  where id = {$child}");
            }
            $childsStr = serialize($childs);
        }
        else
        {
            $childsStr = serialize(array());
        }
        if($handleStatus == 3)
        {
            $blacklist = new BlackList();
            $blacklist->guildid = $gid;
            $blacklist->guildidlist = $childsStr;
            $blacklist->createdate = time();
            $blacklist->save();
        }
        else
        {
            BlackList::where('guildid', $gid)->delete();
        }

        //日志
        $params['module']       = __CLASS__;
        $params['function']     = __FUNCTION__;
        $params['operation']    = $operation;
        $params['object']       = $gid;
        $params['content']      = $description;
        Log::record($params);
        return redirect($this->moduleRoute)->with('message', $operation.'黑名单完成!');
    }

 
    public function blacklist_join_form($id)
    {
        
        $object = Guild::find($id);
        $object->games = '暂无';
        $object->namecard = '暂无';
        $object->qq = '暂无';
        $object->created = date('Y-m-d H:i:s', $object->CreateDate); 
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
        $object->games = '暂无';
        $object->namecard = '暂无';
        $object->qq = '暂无';
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
            $childsStr = serialize(array());
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
        $childs = unserialize($blacklist[0]->guildidlist); 
        if(count($childs))
        {
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
    
    public function dataFilter($field, $data, $object=NULL)
    {
        switch($field)
        {
            case 'AuditStatus':
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
                if($object->AuditStatus == 3)
                {
                    $value = '<a href="'.url($this->moduleRoute.'/blacklist_form/'.$object->Id).'" >移除</a>';
                }
                else
                {
                    $value = '<a href="'.url($this->moduleRoute.'/blacklist_form/'.$object->Id).'" >加入</a>';
                }
                break;
            case 'CreateDate':
                    $value = date('Y-m-d H:i:s', $data);
                break;
            case 'Id':
                $value = $data;
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
        $str .='公会名称: <input type="text" id="Name" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号: <input type="text" id="UserName" class="form-control">&nbsp;&nbsp;';
        $str .='登录账号Id: <input type="text" id="UserId" class="form-control">&nbsp;&nbsp;';
        $str .='状态: <select id="AuditStatus" class="form-control"><option value="99"> - All - </option><option value="1">通过</option><option value="2">待审核</option></select>&nbsp;&nbsp;';
        $str .=' <button type="button" class="btn btn-default" id="advanceSearchSubmit">搜索</button>';
        $str .='</pre></div></p>';
        return $str; 
    }

    public function setAdvanceSearchFields()
    {
        return json_encode(
            array(
                'name'=> 'like', 
                'username'=>'like', 
                'userid'=>'like',
                'AuditStatus'=>'=int' 
            )
        );
    }

    public function setSearchConditions($type)
    {
        $conditions = array();
        $conditions[] = ' AuditStatus IN (1, 3) '; 
        $conditions[] = ' GuildType IN (1,2) ';
        $conditions[] = ' Guilderid = 0 ';
        return $conditions;
    }

}


class ChairmanFactory
{
    public static function createObject($type)
    {
        switch($type)
        {
            case 'audit':
                $controller = 'App\Http\Controllers\ChairmanAuditController';
                break;
            case 'game_authorization':
                $controller = 'App\Http\Controllers\ChairmanGameAuthorizationController';
                break;
            case 'blacklist':
                $controller = 'App\Http\Controllers\ChairmanBlacklistController';
                break;
            case 'statistic_users':
                $controller = 'App\Http\Controllers\Guild\StatisticUserController';
                break;
            case 'statistic_user_guilds':
                $controller = 'App\Http\Controllers\Guild\StatisticUserGuildController';
                break;
            case 'statistic_game_recharges':
                $controller = 'App\Http\Controllers\Guild\StatisticGameRechargeController';
                break;
            case 'statistic_game_consumes':
                $controller = 'App\Http\Controllers\Guild\StatisticGameConsumeController';
                break;
            case 'statistic_badou_consumes':
                $controller = 'App\Http\Controllers\Guild\StatisticBadouConsumeController';
                break;
        }
        
        $object = new $controller();
        return $object;       
    }
}
