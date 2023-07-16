<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConsumeOilsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consume_oils', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('date');
			$table->bigInteger('vehicle_id');
			$table->bigInteger('oil_id');
			$table->string('engine_oil', 191);
			$table->float('km', 10, 0);
			$table->string('driver', 191);
			$table->text('note')->nullable();
			$table->timestamps();
			$table->string('status', 191);
			$table->string('type', 191)->nullable();
			$table->float('stock', 10, 0)->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('consume_oils');
	}

}
