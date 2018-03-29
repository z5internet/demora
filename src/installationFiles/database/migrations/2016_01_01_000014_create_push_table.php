<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('uid')->nullable()->unsigned();
            $table->string('channel');
            $table->string('fromConnection');
            $table->text('data');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('uid')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('push');
    }
}
