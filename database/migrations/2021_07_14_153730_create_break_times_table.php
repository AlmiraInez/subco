<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBreakTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('break_times', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('break_time', 191);
			$table->time('start_time');
			$table->time('finish_time');
			$table->text('notes')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->float('breaktime', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('break_times');
	}

}
