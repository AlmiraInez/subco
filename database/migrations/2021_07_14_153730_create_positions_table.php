<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('positions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 150);
			$table->string('name', 200);
			$table->bigInteger('parent_id')->nullable();
			$table->bigInteger('department_id')->nullable();
			$table->integer('max_person');
			$table->text('notes');
			$table->string('status', 200);
			$table->timestamps();
			$table->integer('level')->nullable();
			$table->text('path')->nullable();
			$table->string('code_system', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('positions');
	}

}
