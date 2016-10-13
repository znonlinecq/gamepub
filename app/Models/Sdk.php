<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sdk extends Model
{
    //
    protected $table = 'sdkinfo';
    protected $connection = 'xfgame2';
    public $timestamps = false;
}
