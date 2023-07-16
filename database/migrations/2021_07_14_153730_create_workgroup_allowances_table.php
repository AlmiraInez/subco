<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkgroupAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workgroup_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('workgroup_id');
			$table->bigInteger('allowance_id');
			$table->integer('is_default');
			$table->timestamps();
			$table->string('value', 191)->nullable();
			$table->string('type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('workgroup_allowances');
	}

}
