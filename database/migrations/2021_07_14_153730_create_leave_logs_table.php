<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeaveLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leave_logs', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('leave_id')->nullable();
			$table->string('type', 191);
			$table->time('start')->nullable();
			$table->time('finish')->nullable();
			$table->timestamps();
			$table->date('date')->nullable();
			$table->bigInteger('reference_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leave_logs');
	}

}
