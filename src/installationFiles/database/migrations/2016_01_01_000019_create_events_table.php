<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('uid')->nullable()->unsigned();
            $table->integer('event')->nullable()->unsigned();
            $table->datetime('created_at');

            $table->foreign('uid')->references('id')->on('users');
            $table->foreign('event')->references('id')->on('event_names');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
