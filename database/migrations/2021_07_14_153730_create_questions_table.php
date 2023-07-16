<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('order')->nullable();
			$table->integer('is_parent')->nullable();
			$table->integer('question_parent_code')->nullable();
			$table->integer('answer_parent_code')->nullable();
			$table->string('type', 191)->nullable();
			$table->text('description')->nullable();
			$table->string('frequency', 191)->nullable();
			$table->date('start_date')->nullable();
			$table->date('finish_date')->nullable();
			$table->text('description_information')->nullable();
			$table->string('answer_type', 191)->nullable();
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
		Schema::drop('questions');
	}

}
