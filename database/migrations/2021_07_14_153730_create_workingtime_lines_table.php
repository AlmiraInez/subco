<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkingtimeLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workingtime_lines', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('workingtime_id');
			$table->bigInteger('department_id');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workingtime_lines');
	}

}
