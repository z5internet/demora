<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationsId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('ui_notifications', function (Blueprint $table) {

            $table->increments('id')->change();
            $table->string('nid');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('ui_notifications', function (Blueprint $table) {

            $table->string('id', 50)->change();
            $table->drop('nid');

        });

    }

}
