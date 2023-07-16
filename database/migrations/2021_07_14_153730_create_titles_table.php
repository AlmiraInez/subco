<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTitlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('titles', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('name', 191);
			$table->timestamps();
			$table->string('code', 150);
			$table->text('notes')->nullable();
			$table->integer('level')->nullable();
			$table->text('path')->nullable();
			$table->string('code_system', 191)->nullable();
			$table->string('status', 200);
			$table->bigInteger('site_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('titles');
	}

}
