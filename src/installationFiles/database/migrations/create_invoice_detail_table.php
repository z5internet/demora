<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('invoice_detail', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->bigInteger('team_id')->index()->unsigned();

            $table->integer('quantity')->unsigned();

            $table->string('description');
            $table->integer('unit_amount');

            $table->string('product_id');

            $table->integer('total');
            $table->integer('tax');
            $table->decimal('tax_rate', 4, 2)->unsigned();
            $table->string('currency', 3);

            $table->datetime('date_to_process');

            $table->string('notes');

            $table->datetime('period_from')->nullable();
            $table->datetime('period_to')->nullable();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('team_id')->references('id')->on('teams');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_detail');
    }
}
