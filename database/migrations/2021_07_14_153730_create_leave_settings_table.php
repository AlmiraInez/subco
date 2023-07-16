<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeaveSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leave_settings', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('leave_name', 191);
			$table->bigInteger('parent_id')->nullable();
			$table->float('balance', 10, 0)->nullable();
			$table->string('reset_time', 191)->nullable();
			$table->date('specific_date')->nullable();
			$table->string('use_time', 191)->nullable();
			$table->string('label_color', 191)->nullable();
			$table->text('note')->nullable();
			$table->integer('status')->nullable();
			$table->integer('description')->nullable();
			$table->integer('level')->nullable();
			$table->text('path')->nullable();
			$table->timestamps();
			$table->string('type', 20)->nullable();
			$table->integer('nominal')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('leave_settings');
	}

}
