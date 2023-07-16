<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('driver', 191)->nullable();
			$table->string('allowance', 191)->nullable();
			$table->timestamps();
			$table->string('category', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('driver_allowances');
	}

}
