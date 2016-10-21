<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticPlatformChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_platform_channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->integer('increase_users');
            $table->float('increase_recharge');
            $table->float('today_recharge');
            $table->integer('all_users');
            $table->integer('all_recharge_users');
            $table->string('log_date');
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
        Schema::drop('statistic_platform_channels');
    }
}
