<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticPlatformIncomes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_platform_incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('game_name');
            $table->string('type');
            $table->float('sum_money');
            $table->integer('sum_users');
            $table->float('average');
            $table->integer('register_count');
            $table->integer('download_count');
            $table->integer('income_count');
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
        Schema::drop('statistic_platform_incomes');
    }
}
