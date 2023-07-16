<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKpisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kpis', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('date')->nullable();
			$table->bigInteger('question_id')->nullable();
			$table->bigInteger('answer_id')->nullable();
			$table->float('rating', 10, 0)->nullable();
			$table->text('description')->nullable();
			$table->bigInteger('employee_id')->nullable();
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
		Schema::drop('kpis');
	}

}
