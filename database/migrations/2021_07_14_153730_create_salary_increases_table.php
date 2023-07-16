<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalaryIncreasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salary_increases', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('ref', 191);
			$table->date('date');
			$table->string('basic_salary', 191);
			$table->string('type', 191);
			$table->text('value');
			$table->text('notes');
			$table->timestamps();
			$table->bigInteger('site_id')->nullable();
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
		Schema::drop('salary_increases');
	}

}
