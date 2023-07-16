<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGradesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('grades', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 150);
			$table->string('name', 200);
			$table->integer('order');
			$table->integer('month')->nullable();
			$table->integer('bestsallary_id');
			$table->string('additional_type', 150);
			$table->string('additional_value', 100);
			$table->integer('basic_sallary');
			$table->timestamps();
			$table->text('notes')->nullable();
			$table->integer('status');
			$table->integer('basic_umk_value');
			$table->string('min_duration', 200)->nullable();
			$table->string('increase_period', 191)->nullable();
			$table->string('code_system', 191)->nullable();
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
		Schema::drop('grades');
	}

}
