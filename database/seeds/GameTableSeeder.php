<?php

use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection()->settableprefix('game_');
        
        $ids = DB::select("SELECT cpid FROM game_cpinfo ");
        foreach($ids as $id)
        {
            for($i=0; $i<3; $i++)
            {
                $guildid =db::table('info')->insertgetid([
                    'Cpid' => $id->cpid,
                    'Gameid' => 0,
                    'Gamename'  => '游戏测试'.str_random(8),
                    'Apkid' => 0,
                    'Typeid' => 0,
                    'Classid' => 0,
                    'Tagid' => 0,
                    'Version' => rand(1,9).'.0.0.'.rand(100,999),
                    'Casenumber' => rand(10000000, 99999999),
                    'Brief' => '游戏介绍',
                    'Spread' => '游戏推广语',
                    'serviceTel' => '010-'.rand(10000000,99999999),
                    'Logo_big' => 'http://www.87870.com/service/images/logo.png',
                    'Logo_small' => 'http://www.87870.com/service/images/logo.png',
                    'Gameimg' => 'http://www.87870.com/service/images/logo.png',
                    'Isself' => 0,
                    'Copyrightimg' => 'http://www.87870.com/service/images/logo.png',
                    'Agencyimg' => 'http://www.87870.com/service/images/logo.png',
                    'Specialimg' => 'http://www.87870.com/service/images/logo.png',
                    'Videourl' => 'http://www.87870.com/test.mp4',
                    'Onlinedate' => date('Y-m-d H:i:s', time()),
                    'Remark' => '备注',
                    'Adddate' => date('Y-m-d H:i:s', time()),
                    'Lastupdate' => date('Y-m-d H:i:s', time()),
                    'status' => 0,
                    'Checkuserid' => 0,
                    'Checkdate' => date('Y-m-d H:i:s', time()),
                ]);
            }
        }
    }
}
