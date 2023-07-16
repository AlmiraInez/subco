<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allowances', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('allowance', 191);
			$table->string('category', 191);
			$table->string('reccurance', 191)->nullable();
			$table->string('working_type', 191)->nullable();
			$table->integer('days_devisor')->nullable();
			$table->text('notes')->nullable();
			$table->integer('status');
			$table->timestamps();
			$table->integer('basic_salary')->nullable();
			$table->bigInteger('account_id')->nullable();
			$table->bigInteger('group_allowance_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('allowances');
	}

}
