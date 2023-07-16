<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarMaxoffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar_maxoffs', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('calendar_id');
			$table->string('month', 191);
			$table->integer('year');
			$table->float('amount_day_off', 10, 0);
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
		Schema::drop('calendar_maxoffs');
	}

}
