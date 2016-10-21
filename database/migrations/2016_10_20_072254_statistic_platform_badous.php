<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StatisticPlatformBadous extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_platform_badous', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recharge');
            $table->integer('badou');
            $table->integer('return_badou');
            $table->integer('add_Vdou');
            $table->integer('ratio');
            $table->integer('consume');
            $table->integer('stock');
            $table->integer('log_date');
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
        Schema::drop('statistic_platform_badous');
    }
}
