<?php

use Illuminate\Database\Seeder;

class GuildsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection()->SetTablePrefix('dt_');
        for($i=0;$i<500;$i++)
        {
            $uid = rand(100000, 999999);
            $createDate = date('Y-m-d H:i:s', time());

            $guildId =DB::table('guild_list')->insertGetId([
                'Userid' => $uid,
                'UserName' => '测试'.str_random(10),
                'Name' => '测试员'.str_random(8),
                'GuildType' => 1,
                'GuilderId' => 0,
                'CampaignTag' => rand(1000, 9999),
                'RegisterCampaign' => rand(1000, 9999),
                'AuditStatus' => 0,
                'Summary' => '未审核',
                'AllowUpdateNameTimes' => 98,
                'CreateDate' => time(),
                'UpdateDate' => time(),
            ]);

            DB::table('guild_toguild')->insert([
                'Userid' => $uid,
                'GuildId' => $guildId,
                'Guild_A' => $guildId,
                'Guild_B' => 0,
                'Guild_C' => 0,
                'GuildType' => 1,
                'CreateDate' => $createDate,
                'UpdateDate' => $createDate,
            ]);
        }
    }
}
