<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimeSchemesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_schemes', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('scheme_name', 191)->nullable();
			$table->string('category', 191)->nullable();
			$table->integer('working_time')->nullable();
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
		Schema::drop('overtime_schemes');
	}

}
