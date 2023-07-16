<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkingtimeDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workingtime_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('workingtime_id')->nullable();
			$table->time('start')->nullable();
			$table->time('finish')->nullable();
			$table->time('min_in')->nullable();
			$table->time('max_out')->nullable();
			$table->timestamps();
			$table->float('workhour', 10, 0)->nullable();
			$table->string('day', 191)->nullable();
			$table->integer('status')->nullable();
			$table->integer('min_workhour')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workingtime_details');
	}

}
