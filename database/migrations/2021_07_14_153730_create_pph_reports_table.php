<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePphReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pph_reports', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('employee_id')->nullable();
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('approved_by')->nullable();
			$table->float('gross_salary', 10, 0)->nullable();
			$table->float('deduction', 10, 0)->nullable();
			$table->float('net_salary', 10, 0)->nullable();
			$table->date('period')->nullable();
			$table->integer('status')->nullable();
			$table->timestamps();
			$table->string('tax', 191)->nullable();
			$table->string('pph_type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pph_reports');
	}

}
