<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalaryReportDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salary_report_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('salary_report_id')->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->string('description', 191)->nullable();
			$table->float('total', 10, 0)->nullable();
			$table->integer('type')->nullable();
			$table->string('status', 191)->nullable();
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
		Schema::drop('salary_report_details');
	}

}
