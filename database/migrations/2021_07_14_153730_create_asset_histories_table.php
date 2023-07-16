<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_histories', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('asset_id')->nullable();
			$table->string('pic', 191);
			$table->string('location', 191);
			$table->float('stock', 10, 0);
			$table->timestamps();
			$table->string('driver', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset_histories');
	}

}
