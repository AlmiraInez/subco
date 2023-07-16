<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeaveDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leave_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('leavesetting_id')->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->integer('balance')->nullable();
			$table->integer('used_balance')->nullable();
			$table->integer('remaining_balance')->nullable();
			$table->integer('over_balance')->nullable();
			$table->integer('year_balance')->nullable();
			$table->timestamps();
			$table->date('from_balance')->nullable();
			$table->date('to_balance')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leave_details');
	}

}
