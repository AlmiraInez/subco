<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_groups', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191);
			$table->string('name', 191);
			$table->text('description')->nullable();
			$table->integer('status');
			$table->timestamps();
			$table->bigInteger('workgroupmaster_id')->nullable();
			$table->string('penalty', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('work_groups');
	}

}
