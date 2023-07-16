<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_contacts', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('customer_id')->nullable();
			$table->string('contact_name', 191);
			$table->string('contact_phone', 191);
			$table->string('contact_email', 191);
			$table->string('contact_address', 191);
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
		Schema::drop('customer_contacts');
	}

}
