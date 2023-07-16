<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyReportDriverAdditionalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_report_driver_additionals', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('daily_report_driver_id');
			$table->string('additional_name', 191)->nullable();
			$table->float('additional_total', 10, 0)->nullable();
			$table->timestamps();
			$table->integer('status')->default(0);
			$table->string('reff_additional', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_report_driver_additionals');
	}

}
