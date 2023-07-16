<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('departments', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('parent_id')->nullable();
			$table->string('name', 191);
			$table->timestamps();
			$table->string('code', 150);
			$table->text('notes');
			$table->integer('status');
			$table->integer('level')->nullable();
			$table->text('path')->nullable();
			$table->string('code_system', 191)->nullable();
			$table->integer('site_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('departments');
	}

}
