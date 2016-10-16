<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticOrderRechargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_order_recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('createdate');
            $table->string('gamename');
            $table->string('type');
            $table->string('level');
            $table->string('username')->nullable();
            $table->string('payaccount');
            $table->string('paymethod');
            $table->string('rmb');
            $table->smallInteger('status');
            $table->smallInteger('isrebate');
            $table->string('rebate');
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
        Schema::drop('statistic_order_recharges');
    }
}
