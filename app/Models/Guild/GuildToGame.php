<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class GuildToGame extends Model
{
    protected $connection='xfgame1';
    protected $table = 'guild_togames';
    public $timestamps = false;
    protected $primaryKey = 'Id';

    public static function check_game_authorization($guildId, $gameId)
    {
        $toGame = GuildToGame::where('GuildId', $guildId)
            ->where('Appid', $gameId)
            ->where('AuditStatus', 1)
            ->get();
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
