<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeCareersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_careers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('reference', 191);
			$table->string('position', 191);
			$table->string('grade', 191);
			$table->string('department', 191);
			$table->date('start_date');
			$table->date('end_date');
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
		Schema::drop('employee_careers');
	}

}
