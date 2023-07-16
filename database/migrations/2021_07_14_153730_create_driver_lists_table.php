<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_lists', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('driver_allowance_id');
			$table->string('recurrence_day', 191)->nullable();
			$table->string('type', 191)->nullable();
			$table->time('start')->nullable();
			$table->time('finish')->nullable();
			$table->integer('rit')->nullable();
			$table->integer('value')->nullable();
			$table->timestamps();
			$table->integer('status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('driver_lists');
	}

}
