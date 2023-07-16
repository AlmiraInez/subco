<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSplsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spls', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('nik', 191)->nullable();
			$table->dateTime('start_overtime');
			$table->dateTime('finish_overtime');
			$table->text('notes')->nullable();
			$table->integer('status');
			$table->timestamps();
			$table->date('spl_date')->nullable();
			$table->float('duration', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('spls');
	}

}
