<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;

class GuildToGuild extends Model
{ 
    protected $connection = 'xfgame1';
    protected $table = 'guild_toguild';
    public $timestamps = false;


    public static function getChilds($id)
    {
        $ids = array();

        $childsB = GuildToGuild::where('Guild_A', $id)->where('GuildType', 2)->get();
        if(count($childsB))
        {
            foreach($childsB as $childB)
            {
                $ids[] = $childB->Guild_B;
                $childsC = GuildToGuild::where('Guild_B', $childB->Guild_B)->where('GuildType', 3)->get();
                if(count($childsC))
                {
                    foreach($childsC as $childC)
                    {
                        $ids[] = $childC->Guild_C;
                    }
                }
            }
        }

        return $ids;
    }    
    
    public static function getChildsLevel($id)
    {
        $all[$id] = array();

        $childsB = GuildToGuild::where('Guild_A', $id)->where('GuildType', 2)->get();
        if(count($childsB))
        {
            foreach($childsB as $childB)
            {
                $all[$id][$childB->Guild_B] = array();
                $childsC = GuildToGuild::where('Guild_B', $childB->Guild_B)->where('GuildType', 3)->get();
                if(count($childsC))
                {
                    foreach($childsC as $childC)
                    {
                        $all[$id][$childB->Guild_B][$childC->Guild_C] = $childC->Guild_C;
                    }
                }
            }
        }

        return $all;
    }

}
