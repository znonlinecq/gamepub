<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticGuildUsersGuilds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_guild_users_guilds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->string('guild_id');
            $table->string('user_id');
            $table->integer('increase_users');
            $table->integer('active_users');
            $table->integer('recharge_users');
            $table->float('money');
            $table->float('arpu');
            $table->float('remain_1');
            $table->float('remain_7');
            $table->float('remain_15');
            $table->string('create_date');
            $table->integer('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statistic_guild_users_guilds');
    }
}
