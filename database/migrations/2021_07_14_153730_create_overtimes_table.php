<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtimes', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('day', 191)->nullable();
			$table->integer('scheme_rule')->nullable();
			$table->float('hour', 10, 0)->nullable();
			$table->float('amount', 10, 0)->nullable();
			$table->float('basic_salary', 10, 0)->nullable();
			$table->float('final_salary', 10, 0)->nullable();
			$table->timestamps();
			$table->date('date')->nullable();
			$table->integer('year')->nullable();
			$table->string('month', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('overtimes');
	}

}
