<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_categories', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('parent')->nullable();
			$table->string('name', 191);
			$table->string('description', 191)->nullable();
			$table->boolean('display');
			$table->string('picture', 191);
			$table->boolean('status');
			$table->timestamps();
			$table->string('path', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_categories');
	}

}
