<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetSerialStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_serial_stocks', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('asset_serial_id')->nullable();
			$table->integer('stock')->nullable();
			$table->date('expired_date')->nullable();
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
		Schema::drop('asset_serial_stocks');
	}

}
