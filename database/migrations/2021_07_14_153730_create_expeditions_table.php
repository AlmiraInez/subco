<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpeditionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expeditions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191);
			$table->string('name', 191);
			$table->integer('displayed');
			$table->float('margin', 10, 0);
			$table->text('image')->nullable();
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
		Schema::drop('expeditions');
	}

}
