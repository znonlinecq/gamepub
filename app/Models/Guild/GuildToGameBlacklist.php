<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class GuildToGameBlacklist extends Model
{
    protected $connection='xfgame1';
    protected $table = 'guild_togamesblacklist';
    public $timestamps = false;


}
