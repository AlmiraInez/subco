<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReimbursementCalculationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reimbursement_calculations', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('reimbursement_id')->nullable();
			$table->bigInteger('drd_calculation_id')->nullable();
			$table->bigInteger('drd_additional_id')->nullable();
			$table->string('description', 191)->nullable();
			$table->float('value', 10, 0)->nullable();
			$table->timestamps();
			$table->string('reff_detail_additional', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reimbursement_calculations');
	}

}
