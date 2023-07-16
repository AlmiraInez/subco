<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAllowanceReportDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allowance_report_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('allowance_report_id');
			$table->bigInteger('employee_id');
			$table->string('allowance', 191);
			$table->string('category', 191)->nullable();
			$table->float('value', 10, 0)->nullable();
			$table->timestamps();
			$table->decimal('factor')->nullable();
			$table->text('total')->nullable();
			$table->bigInteger('allowance_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('allowance_report_details');
	}

}
