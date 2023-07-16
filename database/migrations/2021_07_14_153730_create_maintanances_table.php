<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMaintanancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('maintanances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('vehicle_id');
			$table->date('date');
			$table->float('km', 10, 0);
			$table->string('driver', 191);
			$table->float('total', 10, 0);
			$table->timestamps();
			$table->string('status', 191);
			$table->string('vehicle_name', 191)->nullable();
			$table->string('vehicle_no', 191)->nullable();
			$table->string('vehicle_category', 191)->nullable();
			$table->string('vendor', 191)->nullable();
			$table->string('technician', 191)->nullable();
			$table->string('image', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('maintanances');
	}

}
