<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverAllowanceListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_allowance_lists', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('date')->nullable();
			$table->integer('rit')->nullable();
			$table->integer('value')->nullable();
			$table->timestamps();
			$table->string('truck', 191)->nullable();
			$table->bigInteger('driver_id')->nullable();
			$table->integer('group')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('driver_allowance_lists');
	}

}
