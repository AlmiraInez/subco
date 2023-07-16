<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlphaPenaltiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alpha_penalties', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id')->nullable();
			$table->date('date')->nullable();
			$table->float('salary', 10, 0)->nullable();
			$table->float('penalty', 10, 0)->nullable();
			$table->timestamps();
			$table->bigInteger('leave_id')->nullable();
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
		Schema::drop('alpha_penalties');
	}

}
