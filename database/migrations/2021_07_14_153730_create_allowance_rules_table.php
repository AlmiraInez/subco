<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAllowanceRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allowance_rules', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('allowance_id')->nullable();
			$table->integer('qty_absent');
			$table->integer('qty_allowance');
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
		Schema::drop('allowance_rules');
	}

}
