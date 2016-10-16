<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticOrderGiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_order_gives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('created');
            $table->string('type');
            $table->string('level');
            $table->string('username')->nullable();
            $table->string('tousername');
            $table->string('badou');
            $table->string('afterbadou');
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
        Schema::drop('statistic_order_gives');
    }
}
