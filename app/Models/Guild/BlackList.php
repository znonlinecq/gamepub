<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    protected $connection = 'xfgame1';
    protected $table = 'guild_blacklist';
    public $timestamps = false;
}
