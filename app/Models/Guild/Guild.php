<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    protected $connection = 'xfgame1';
    protected $table = 'guild_list';
    public $timestamps = false;

#    public function toGuild()
#    {
#        return $this->hasOne('App\Models\Guild\GuildToGuild');
#    }
    
}
