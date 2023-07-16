<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOutsourcingPicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outsourcing_pics', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('outsourcing_id')->nullable();
			$table->string('pic_name', 191);
			$table->string('pic_phone', 191);
			$table->string('pic_email', 191);
			$table->text('pic_address');
			$table->string('pic_category', 191);
			$table->boolean('default');
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
		Schema::drop('outsourcing_pics');
	}

}
