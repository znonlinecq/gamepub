<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticGuildGamesConsumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_guild_games_consumes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->integer('guild_consume');
            $table->integer('other_consume');
            $table->integer('alone_user');
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
        Schema::drop('statistic_guild_games_consumes');
    }
}
