<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalaryIncreaseDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salary_increase_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('salaryincrease_id');
			$table->bigInteger('employee_id');
			$table->text('upcoming_amount');
			$table->timestamps();
			$table->text('current_Salary')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('salary_increase_details');
	}

}
