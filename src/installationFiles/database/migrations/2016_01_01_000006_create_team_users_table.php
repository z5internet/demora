<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->bigInteger('tid')->unsigned();
            $table->bigInteger('uid')->unsigned();
            $table->tinyInteger('role')->unsigned();
            $table->unique(['tid', 'uid']);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('uid')->references('id')->on('users');
            $table->foreign('tid')->references('id')->on('teams');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('team_users');
    }
}
