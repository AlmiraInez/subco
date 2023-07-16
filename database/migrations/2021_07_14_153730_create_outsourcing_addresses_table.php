<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOutsourcingAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outsourcing_addresses', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('outsourcing_id')->nullable();
			$table->bigInteger('province_id');
			$table->bigInteger('region_id');
			$table->bigInteger('district_id');
			$table->text('address');
			$table->string('kode_pos', 191);
			$table->boolean('default');
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
		Schema::drop('outsourcing_addresses');
	}

}
