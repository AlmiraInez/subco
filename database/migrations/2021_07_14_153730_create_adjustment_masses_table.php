<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdjustmentMassesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('adjustment_masses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date')->nullable();
			$table->float('adjustment_workingtime', 10, 0)->nullable();
			$table->float('adjustment_overtime', 10, 0)->nullable();
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
		Schema::drop('adjustment_masses');
	}

}
