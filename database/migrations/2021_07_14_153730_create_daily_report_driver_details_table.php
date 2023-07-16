<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyReportDriverDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_report_driver_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('daily_report_driver_id');
			$table->string('destination', 191)->nullable();
			$table->time('departure')->nullable();
			$table->float('departure_km', 10, 0)->nullable();
			$table->time('arrival')->nullable();
			$table->float('arrival_km', 10, 0)->nullable();
			$table->float('parking', 10, 0)->nullable();
			$table->float('toll_money', 10, 0)->nullable();
			$table->float('etc', 10, 0)->nullable();
			$table->float('total', 10, 0)->nullable();
			$table->timestamps();
			$table->integer('status')->default(0);
			$table->string('reff_detail', 191)->nullable();
			$table->float('oil', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_report_driver_details');
	}

}
