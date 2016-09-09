<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $connection = 'xfgame2';
    protected $table = 'info';
    public $timestamps = false;

    public function developer()
    {
        return $this->belongsTo('App\Models\Developer', 'Cpid');
    }
}
