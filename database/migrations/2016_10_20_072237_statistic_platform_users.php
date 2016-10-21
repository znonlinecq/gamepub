<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticPlatformUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_platform_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->integer('day_active');
            $table->integer('increase_users');
            $table->integer('all_users');
            $table->float('increase_recharge_users');
            $table->integer('all_recharge_users');
            $table->float('new_increase_recharge');
            $table->integer('all_recharge');
            $table->float('new_recharge_percent');
            $table->float('arpu');
            $table->float('one_remain');
            $table->float('seven_remain');
            $table->float('fifteen_remain');
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
        Schema::drop('statistic_platform_users');
    }
}
