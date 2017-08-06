<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function ($table) {

            $table->increments('id');
            $table->string('product_id');
            $table->string('product_group')->nullable();
            $table->string('description');

            /** Initial payments **/

            $table->string('initial_payment_term')->nullable(); // How long does initial payment last
            $table->integer('initial_payment_amount')->unsigned()->nullable();  // Initial payment amounts
            $table->integer('initial_payment_quantity')->unsigned()->nullable(); // How many initial payments lasting 'term' length

            /** Trial period **/

            $table->boolean('trial_period_card_required')->nullable();
            $table->string('trial_period')->nullable();

            $table->integer('users_included')->unsigned();
            $table->text('amount_per_user')->nullable();
            $table->boolean('auto_bill_for_extra_users')->nullable();

            /** standard payments **/

            $table->integer('amount')->unsigned();
            $table->integer('undiscounted_amount')->unsigned()->nullable();
            $table->decimal('tax', 4, 2)->unsigned();

            $table->string('currency', 3);
            $table->string('term');
            $table->boolean('is_recurring');

            $table->boolean('archived')->nullable();

            $table->timestamps();

            $table->unique(['product_id']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}

