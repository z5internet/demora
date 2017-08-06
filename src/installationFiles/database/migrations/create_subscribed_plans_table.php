<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribedPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_plans', function ($table) {

            $table->bigIncrements('id');
            $table->bigInteger('team_id')->unsigned();
            $table->string('product_id');
            $table->string('product_group')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->integer('amount')->unsigned();
            $table->integer('undiscounted_amount')->unsigned()->nullable();
            $table->decimal('tax', 4, 2)->unsigned();
            $table->string('currency');
            $table->string('description');
            $table->string('term')->nullable();
            $table->string('status')->nullable();
            $table->boolean('is_recurring')->nullable();
            $table->boolean('trial_period')->nullable();

            $table->string('initial_payment_term')->nullable();
            $table->integer('initial_payment_amount')->unsigned()->nullable();
            $table->integer('initial_payment_quantity')->unsigned()->nullable();

            $table->integer('users_included')->unsigned();
            $table->text('amount_per_user')->nullable();
            $table->boolean('auto_bill_for_extra_users')->nullable();

            $table->timestamps();

            $table->unique(['team_id', 'product_id']);
            $table->unique(['team_id', 'product_group']);

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('product_id')->references('product_id')->on('products');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscribed_plans');
    }
}

