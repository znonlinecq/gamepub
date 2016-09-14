<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apk extends Model
{
    protected $connection = 'xfgame2';
    protected $table = 'apkinfo';
    protected $primaryKey = 'apkid';

    public $timestamps = false;
    
    public function developer()
    {
        return $this->belongsTo('App\Models\Developer', 'Cpid');
    }
    
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'Gameid');
    }


}
