<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class GuildToGuild extends Model
{ 
    protected $connection = 'xfgame1';
    protected $table = 'guild_toguild';
    public $timestamps = false;
}
