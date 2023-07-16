<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkingtimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workingtimes', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('working_time_type', 100);
			$table->string('description', 100);
			$table->timestamps();
			$table->string('notes', 191)->nullable();
			$table->string('working_time_start', 200)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workingtimes');
	}

}
