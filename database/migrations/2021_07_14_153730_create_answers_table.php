<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('answers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('question_id')->nullable();
			$table->string('answer_type', 191)->nullable();
			$table->text('description')->nullable();
			$table->text('information')->nullable();
			$table->integer('rating')->nullable();
			$table->integer('order')->nullable();
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
		Schema::drop('answers');
	}

}
