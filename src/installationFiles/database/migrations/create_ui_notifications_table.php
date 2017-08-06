<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUiNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_notifications', function (Blueprint $table) {

            $table->string('id');
            $table->bigInteger('u')->unsigned();
            $table->string('i', 255);
            $table->string('l', 255);
            $table->text('b');
            $table->boolean('r');
            $table->timestamps();

            $table->unique(['id', 'u']);
            $table->foreign('u')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ui_notifications');
    }
}
