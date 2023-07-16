<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assets', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('assetcategory_id')->nullable();
			$table->string('name', 191);
			$table->string('image', 191)->nullable();
			$table->timestamps();
			$table->string('pic', 191)->nullable();
			$table->string('location', 191)->nullable();
			$table->date('buy_date')->nullable();
			$table->string('note', 191)->nullable();
			$table->string('vendor', 191)->nullable();
			$table->string('document', 191)->nullable();
			$table->float('buy_price', 10, 0)->nullable();
			$table->float('stock', 10, 0)->nullable();
			$table->string('code', 191)->nullable();
			$table->string('asset_type', 191)->nullable();
			$table->string('license_no', 191)->nullable();
			$table->string('engine_no', 191)->nullable();
			$table->string('merk', 191)->nullable();
			$table->string('type', 191)->nullable();
			$table->string('model', 191)->nullable();
			$table->integer('production_year')->nullable();
			$table->string('manufacture', 191)->nullable();
			$table->string('engine_capacity', 191)->nullable();
			$table->string('driver', 191)->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->bigInteger('driver_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assets');
	}

}
