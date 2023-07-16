<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkingtimeAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workingtime_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('workingtime_id')->nullable();
			$table->bigInteger('allowance_id')->nullable();
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
		Schema::drop('workingtime_allowances');
	}

}
