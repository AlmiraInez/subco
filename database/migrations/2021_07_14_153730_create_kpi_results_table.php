<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKpiResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpi_results', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('result_date')->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->float('value_total', 10, 0)->nullable();
			$table->timestamps();
			$table->string('status', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kpi_results');
	}

}
