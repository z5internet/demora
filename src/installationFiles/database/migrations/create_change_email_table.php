<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->unsigned();
            $table->string('email');
            $table->string('code');
            $table->timestamp('created_at')->nullable();

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
        Schema::drop('joined');
    }
}
