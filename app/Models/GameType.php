<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameType extends Model
{
    protected $connection   = 'xfgame2';
    protected $table        = 'type';
    protected $primaryKey   = 'Typeid';
    public $timestamps   = false;


}
