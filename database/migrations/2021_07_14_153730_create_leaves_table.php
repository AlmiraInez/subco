<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeavesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leaves', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id')->nullable();
			$table->integer('status')->nullable();
			$table->string('supporting_document', 191)->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
			$table->float('duration', 10, 0)->nullable();
			$table->bigInteger('leave_setting_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leaves');
	}

}
