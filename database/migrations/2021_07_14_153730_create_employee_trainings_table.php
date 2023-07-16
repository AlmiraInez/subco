<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeTrainingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_trainings', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('code', 191);
			$table->string('issued', 191);
			$table->date('start_date');
			$table->date('end_date');
			$table->text('description')->nullable();
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
		Schema::drop('employee_trainings');
	}

}
