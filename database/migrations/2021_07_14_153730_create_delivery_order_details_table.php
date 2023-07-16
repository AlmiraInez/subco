<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_order_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('delivery_order_id')->nullable();
			$table->string('po_number', 191)->nullable();
			$table->string('item_name', 191)->nullable();
			$table->string('size', 191)->nullable();
			$table->decimal('qty')->nullable();
			$table->string('remarks', 191)->nullable();
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
		Schema::drop('delivery_order_details');
	}

}
