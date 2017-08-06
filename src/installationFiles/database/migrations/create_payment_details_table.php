<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function ($table) {

            $table->bigIncrements('id');
            $table->bigInteger('team_id')->unsigned();
            $table->string('processor');
            $table->string('subscription_id');
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');

            $table->unique(['team_id']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_details');
    }
}
