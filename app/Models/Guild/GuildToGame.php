<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;
use DB;

class GuildToGame extends Model
{
    protected $connection='xfgame1';
    protected $table = 'guild_togames';
    public $timestamps = false;
    protected $primaryKey = 'Id';

    public static function check_game_authorization($guildId, $gameId)
    {
      //  $toGame = GuildToGame::where('GuildId', $guildId)
      //      ->where('Appid', $gameId)
      //      ->where('AuditStatus', 'IN', '1,3')
      //      ->get();
        $toGame = DB::select("select * from dt_guild_togames WHERE Guildid = {$guildId} AND Appid = {$gameId} AND AuditStatus IN (1,3)");
        if(count($toGame))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
