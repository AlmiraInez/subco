<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBreakTimeWorkGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('break_time_work_group', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('break_time_id');
			$table->integer('work_group_id');
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
		Schema::drop('break_time_work_group');
	}

}
