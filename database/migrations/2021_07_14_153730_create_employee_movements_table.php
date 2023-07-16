<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeMovementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_movements', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id')->nullable();
			$table->bigInteger('title_id')->nullable();
			$table->dateTime('start')->nullable();
			$table->dateTime('finish')->nullable();
			$table->string('reason', 191);
			$table->integer('status');
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
		Schema::drop('employee_movements');
	}

}
