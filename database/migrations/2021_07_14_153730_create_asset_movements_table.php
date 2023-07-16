<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetMovementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_movements', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('type', 191)->nullable();
			$table->integer('qty')->nullable();
			$table->timestamps();
			$table->text('note')->nullable();
			$table->bigInteger('asset_id');
			$table->string('reference', 191);
			$table->string('from', 191);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset_movements');
	}

}
