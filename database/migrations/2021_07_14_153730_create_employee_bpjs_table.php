<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmployeeBpjsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_bpjs', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id');
			$table->string('name', 191);
			$table->string('nik', 191);
			$table->string('relation', 191);
			$table->text('address');
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
		Schema::drop('employee_bpjs');
	}

}
