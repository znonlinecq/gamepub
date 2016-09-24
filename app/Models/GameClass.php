<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameClass extends Model
{
    protected $connection = 'xfgame2';
    protected $table = 'class';
    protected $primaryKey = 'Classid';
    public $timestamps = false;
}
