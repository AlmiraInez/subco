<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyReportDriversTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_report_drivers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('date')->nullable();
			$table->string('police_no', 191);
			$table->bigInteger('driver_id')->nullable();
			$table->string('exp_passenger', 191)->nullable();
			$table->float('subtotal', 10, 0)->nullable();
			$table->float('subtotaladditional', 10, 0)->nullable();
			$table->float('grandtotal', 10, 0)->nullable();
			$table->timestamps();
			$table->string('code', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_report_drivers');
	}

}
