<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chairman extends Model
{
    //
    protected $table = 'guild_list';
    protected $connection = 'xfgame1';
    protected $primaryKey = 'Id';
    public $timestamps = false;

}
