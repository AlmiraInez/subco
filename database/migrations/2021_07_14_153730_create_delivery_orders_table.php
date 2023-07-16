<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_orders', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->dateTime('date')->nullable();
			$table->string('type_truck', 191)->nullable();
			$table->string('do_number', 191)->nullable();
			$table->bigInteger('driver_id')->nullable();
			$table->string('police_no', 191)->nullable();
			$table->string('destination', 191)->nullable();
			$table->timestamps();
			$table->integer('group')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('delivery_orders');
	}

}
