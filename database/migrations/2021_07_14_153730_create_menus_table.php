<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('parent_id')->nullable();
			$table->string('menu_name', 191);
			$table->string('menu_route', 191)->nullable();
			$table->string('menu_icon', 191)->nullable();
			$table->bigInteger('menu_sort')->nullable();
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
		Schema::drop('menus');
	}

}
