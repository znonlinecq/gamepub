<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;

    static public function check_exist($rid, $mid, $fid){
        $permission = Permission::where('rid', $rid)
            ->where('mid', $mid)
            ->where('fid', $fid)
            ->get();
        if(count($permission))
        {
            return true;
        }else{
            return false;
        }
    }    
    
    static public function check_module($rid, $mid){
        $permission = Permission::where('rid', $rid)
            ->where('mid', $mid)
            ->get();
        if(count($permission))
        {
            return true;
        }else{
            return false;
        }
    }
}
