<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOutsourcingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outsourcings', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191)->nullable();
			$table->string('name', 191);
			$table->string('email', 191);
			$table->string('no_tlpn', 191);
			$table->bigInteger('workgroup_id');
			$table->string('status', 191);
			$table->string('image', 191);
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
		Schema::drop('outsourcings');
	}

}
