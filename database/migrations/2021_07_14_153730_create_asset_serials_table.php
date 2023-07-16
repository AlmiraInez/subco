<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetSerialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_serials', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('asset_id')->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->string('serial_no', 191)->nullable();
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
		Schema::drop('asset_serials');
	}

}
