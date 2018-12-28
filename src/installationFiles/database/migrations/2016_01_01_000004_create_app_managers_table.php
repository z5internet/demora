<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('admin_services', function (Blueprint $table) {

            $table->increments('id');
            $table->string('service');
            $table->unique(['service']);

        });

        Schema::create('app_managers', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('uid')->unsigned()->nullable();
            $table->integer('service')->unsigned()->nullable();

            $table->foreign('uid')->references('id')->on('users');
            $table->foreign('service')->references('id')->on('admin_services');
            $table->unique(['uid', 'service']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('app_managers');
        Schema::drop('admin_services');

    }

}
