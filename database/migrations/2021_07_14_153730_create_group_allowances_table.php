<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191)->nullable();
			$table->string('name', 191)->nullable();
			$table->text('notes')->nullable();
			$table->integer('status');
			$table->integer('site_id')->nullable();
			$table->string('code_system', 191)->nullable();
			$table->string('group_type', 20)->nullable();
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
		Schema::drop('group_allowances');
	}

}
