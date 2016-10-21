<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticGuildBadousConsumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_guild_badous_consumes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guild_name');
            $table->string('username');
            $table->string('level');
            $table->integer('sum');
            $table->integer('direct_badou');
            $table->integer('give_badou');
            $table->integer('down_users');
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
        Schema::drop('statistic_guild_badous_consumes');
    }
}
