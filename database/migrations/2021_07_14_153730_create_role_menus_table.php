<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_menus', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('role_id');
			$table->bigInteger('menu_id');
			$table->integer('role_access');
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
		Schema::drop('role_menus');
	}

}
