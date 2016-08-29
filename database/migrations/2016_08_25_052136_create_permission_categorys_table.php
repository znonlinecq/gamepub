<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_categorys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('controller');
            $table->text('description');
            $table->integer('weight');
            $table->integer('menu')->unsigned();
            $table->index('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_categorys');
    }
}
