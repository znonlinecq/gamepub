<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticGuildUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_guild_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->string('increase_users');
            $table->string('recharge_users');
            $table->string('recharge_times');
            $table->float('money');
            $table->float('arpu');
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
        Schema::drop('statistic_guild_users');
    }
}
