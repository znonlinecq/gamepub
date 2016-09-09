<?php

use Illuminate\Database\Seeder;

class ApkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection()->settableprefix('game_');
        
        $ids = DB::select("SELECT id, Cpid FROM game_info ");
        foreach($ids as $id)
        {
            $guildid =db::table('apkinfo')->insertgetid([
                'Apkname' => '包测试'.str_random(8),
                'Gameid' => $id->id,
                'Apktypeid'  => 0,
                'Apkuptype' => 0,
                'Opendowndate' => date('Y-m-d H:i:s', time()),
                'OpeServndate' => date('Y-m-d H:i:s', time()),
                'Eventid' => 0,
                'Cpid' => $id->Cpid,
                'Uploaddate' => date('Y-m-d H:i:s', time()),
                'status' => 0,
                'Checkuserid' => 0,
                'Checkdate' => date('Y-m-d H:i:s', time()),
            ]);
        }
    }
}
