<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeContractsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_contracts', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('code', 191);
			$table->date('start_date');
			$table->date('end_date');
			$table->string('status', 191);
			$table->text('description')->nullable();
			$table->timestamps();
			$table->string('file', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employee_contracts');
	}

}
