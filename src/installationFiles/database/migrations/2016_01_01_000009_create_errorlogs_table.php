<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('error_logs', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('uid');
            $table->string('type');
            $table->text('stacktrace');
            $table->text('url');
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
        Schema::drop('error_logs');
    }
}
