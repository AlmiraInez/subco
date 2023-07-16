<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarExceptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar_exceptions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('calendar_id')->nullable();
			$table->date('date_exception');
			$table->string('description', 191);
			$table->string('day', 191);
			$table->timestamps();
			$table->string('label_color', 191)->nullable();
			$table->string('text_color', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calendar_exceptions');
	}

}
