<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeEducationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_education', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('institution', 191);
			$table->string('stage', 191);
			$table->string('period', 191);
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
		Schema::drop('employee_education');
	}

}
