<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Log extends Model
{
    //
    public $timestamps = false;

    public static function record($params)
    {  
        $module = str_replace('App\\Http\\Controllers\\', '', $params['module']);
        $user = Auth::user();
        $log = new Log();
        $log->uid       = $user->id; 
        $log->module    = $module;
        $log->function  = $params['function'];
        $log->operation = $params['operation'];
        $log->object    = $params['object'];
        $log->content   = $params['content'];
        $log->created   = time();
        $log->save();
    }
}
