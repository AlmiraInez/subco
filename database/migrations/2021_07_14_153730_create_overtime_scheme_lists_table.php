<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimeSchemeListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_scheme_lists', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('overtime_scheme_id')->nullable();
			$table->string('recurrence_day', 191)->nullable();
			$table->integer('hour')->nullable();
			$table->float('amount', 10, 0)->nullable();
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
		Schema::drop('overtime_scheme_lists');
	}

}
