<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use z5internet\ReactUserFramework\App\EventNames;

class CreateEventNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_names', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');

        });

        EventNames::insert(['id' => 1, 'name' => 'start_page_loaded']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event_names');
    }
}
