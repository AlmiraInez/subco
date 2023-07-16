<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReimbursementAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reimbursement_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('reimbursement_id')->nullable();
			$table->bigInteger('driver_list_id')->nullable();
			$table->string('description', 191)->nullable();
			$table->float('value', 10, 0)->nullable();
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
		Schema::drop('reimbursement_allowances');
	}

}
