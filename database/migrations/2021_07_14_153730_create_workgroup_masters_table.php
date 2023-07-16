<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkgroupMastersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workgroup_masters', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191);
			$table->string('name', 191);
			$table->integer('can_edit');
			$table->integer('can_delete');
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
		Schema::drop('workgroup_masters');
	}

}
