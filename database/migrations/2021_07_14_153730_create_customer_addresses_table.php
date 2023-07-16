<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_addresses', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('customer_id')->nullable();
			$table->bigInteger('province_id');
			$table->bigInteger('region_id');
			$table->bigInteger('district_id');
			$table->string('address', 191);
			$table->string('latitude', 191);
			$table->string('longitude', 191);
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
		Schema::drop('customer_addresses');
	}

}
