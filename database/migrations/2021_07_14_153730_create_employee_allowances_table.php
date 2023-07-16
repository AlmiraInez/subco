<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('allowance_id');
			$table->text('value')->nullable();
			$table->integer('status');
			$table->timestamps();
			$table->integer('employee_id');
			$table->string('type', 191)->nullable();
			$table->integer('year')->nullable();
			$table->string('month', 2)->nullable();
			$table->decimal('factor', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employee_allowances');
	}

}
