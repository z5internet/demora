<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('team_id')->index()->unsigned();
            $table->string('processor');
            $table->string('transaction_id');

            $table->integer('total');
            $table->integer('tax');
            $table->string('currency', 3);

            $table->integer('converted_total');
            $table->integer('converted_fee');
            $table->integer('converted_tax');
            $table->string('converted_currency', 3);

            $table->string('card_country');
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('vat_id', 50)->nullable();

            $table->timestamps();

            $table->index('created_at');

            $table->foreign('team_id')->references('id')->on('teams');

            $table->unique(['processor', 'transaction_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
    }
}
