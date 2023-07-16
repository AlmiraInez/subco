<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeaveBalancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leave_balances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('leave_type', 191);
			$table->integer('balance');
			$table->string('description', 191);
			$table->timestamps();
			$table->string('leave_tag', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leave_balances');
	}

}
