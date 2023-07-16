<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssetCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('asset_categories', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('parent_id')->nullable();
			$table->string('name', 191);
			$table->string('description', 191)->nullable();
			$table->integer('display');
			$table->string('picture', 191);
			$table->integer('status');
			$table->string('path', 191)->nullable();
			$table->timestamps();
			$table->integer('children')->nullable();
			$table->string('type', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('asset_categories');
	}

}
