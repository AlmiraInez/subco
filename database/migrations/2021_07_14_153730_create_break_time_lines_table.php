<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBreakTimeLinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('break_time_lines', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('breaktime_id');
			$table->bigInteger('workgroup_id');
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
		Schema::drop('break_time_lines');
	}

}
