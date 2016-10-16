<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticOrderPayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_order_pays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('created');
            $table->string('gamename');
            $table->string('goodsname');
            $table->string('goodsnum');
            $table->string('username')->nullable();
            $table->string('payaccount');
            $table->string('paymethod');
            $table->string('paynum');
            $table->smallInteger('status');
            $table->text('remark')->nullable();
            $table->integer('created');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statistic_order_pays');
    }
}
