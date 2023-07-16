<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeDetailallowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_detailallowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->bigInteger('allowance_id');
			$table->bigInteger('workingtime_id')->nullable();
			$table->string('value', 191);
			$table->date('tanggal_masuk');
			$table->timestamps();
			$table->integer('year')->nullable();
			$table->string('month', 2)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employee_detailallowances');
	}

}
