<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticGuildGamesRecharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_guild_games_recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->integer('guild_recharge');
            $table->integer('other_recharge');
            $table->integer('all_recharge');
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
        Schema::drop('statistic_guild_games_recharges');
    }
}
