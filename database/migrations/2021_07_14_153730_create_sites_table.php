<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 20);
			$table->string('name', 191);
			$table->string('phone', 191);
			$table->string('email', 191);
			$table->bigInteger('province_id');
			$table->bigInteger('region_id');
			$table->bigInteger('district_id');
			$table->string('address', 191);
			$table->string('postal_code', 191)->nullable();
			$table->string('logo', 191)->nullable();
			$table->text('receipt_header')->nullable();
			$table->text('receipt_footer')->nullable();
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
		Schema::drop('sites');
	}

}
