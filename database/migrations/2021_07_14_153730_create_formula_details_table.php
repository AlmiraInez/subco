<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFormulaDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('formula_details', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('formula_id');
			$table->string('operation', 191)->nullable();
			$table->integer('order')->nullable();
			$table->bigInteger('answer_id')->nullable();
			$table->string('operation_before', 191)->nullable();
			$table->string('value', 191)->nullable();
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
		Schema::drop('formula_details');
	}

}
