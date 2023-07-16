<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReimbursementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reimbursements', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('date')->nullable();
			$table->string('notes', 191)->nullable();
			$table->bigInteger('daily_report_driver_id');
			$table->bigInteger('driver_id');
			$table->time('max_arrival')->nullable();
			$table->date('get_day')->nullable();
			$table->float('subtotal', 10, 0)->nullable();
			$table->float('subtotalallowance', 10, 0)->nullable();
			$table->float('grandtotal', 10, 0)->nullable();
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
		Schema::drop('reimbursements');
	}

}
