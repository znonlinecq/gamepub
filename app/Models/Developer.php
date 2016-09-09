<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $connection = 'xfgame2';
    protected $table = 'cpinfo';
    public $timestamps = false;
    public $primaryKey = 'cpid';
}
