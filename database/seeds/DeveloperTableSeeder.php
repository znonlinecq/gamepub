<?php

use Illuminate\Database\Seeder;

class DeveloperTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection()->settableprefix('game_');
        for($i=0;$i<100;$i++)
        {
            $uid = rand(100000, 999999);
            $createdate = date('y-m-d h:i:s', time());

            $guildid =db::table('cpinfo')->insertgetid([
                'username' => '测试员'.str_random(10),
                'compname' => 'cp测试'.str_random(8),
                'compweb'  => 'www.87870.com',
                'compaddr' => '北京市朝阳区建国路56号天洋运河壹号e1栋',
                'certificateno' => rand(100000, 999999),
                'certificateimg' => 'http://www.87870.com/service/images/logo.png',
                'taxno' => rand(100000,999999),
                'taximg' => 'http://www.87870.com/service/images/logo.png',
                'conname' => '联系人'.str_random(8),
                'conposition' => '职位'.str_random(8),
                'conmobile' => '135'.rand(10000000,99999999),
                'conqq' => rand(10000000,99999999),
                'conemail' => str_random(6).'@87870.com',
                'status' => 0,
                'adddate' => date('y-m-d h:i:s', time()),
                'checkuserid' => 0,
                'checkdate' => '',
            ]);
        }
 
    }
}
