<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('productcategory_id');
			$table->bigInteger('uom_id');
			$table->string('type', 191);
			$table->string('name', 191);
			$table->text('description');
			$table->integer('best_product');
			$table->string('merk', 191);
			$table->integer('price');
			$table->string('image', 191)->nullable();
			$table->integer('weight');
			$table->integer('volume_l');
			$table->integer('volume_p');
			$table->integer('volume_t');
			$table->string('condition', 191);
			$table->string('sku', 191);
			$table->string('barcode', 50);
			$table->integer('minimum_qty');
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
		Schema::drop('products');
	}

}
