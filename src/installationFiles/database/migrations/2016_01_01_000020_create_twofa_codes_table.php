<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwofaCodesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	*/

	public function up()
	{
		Schema::create('twofa_codes', function (Blueprint $table) {

			$table->bigIncrements('id');
			$table->bigInteger('uid')->unsigned();
			$table->string('code');
			$table->datetime('created_at');

			$table->foreign('uid')->references('id')->on('users');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	*/

	public function down() {

		Schema::drop('twofa_codes');

	}

}
