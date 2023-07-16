<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalaryReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salary_reports', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id')->nullable();
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('approved_by')->nullable();
			$table->float('gross_salary', 10, 0)->nullable();
			$table->float('deduction', 10, 0)->nullable();
			$table->float('net_salary', 10, 0)->nullable();
			$table->date('period')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->integer('print_status')->nullable()->default(0);
			$table->string('salary_type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('salary_reports');
	}

}
