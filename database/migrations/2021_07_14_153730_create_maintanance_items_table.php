<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaintananceItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('maintanance_items', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('maintanance_id');
			$table->string('item', 191);
			$table->float('cost', 10, 0);
			$table->timestamps();
			$table->integer('qty')->nullable();
			$table->float('subtotal', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('maintanance_items');
	}

}
