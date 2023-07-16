<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOutsourcingDocumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outsourcing_documents', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('outsourcing_id')->nullable();
			$table->string('category', 191);
			$table->string('phone', 191);
			$table->string('name', 191);
			$table->string('file', 191);
			$table->string('description', 191);
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
		Schema::drop('outsourcing_documents');
	}

}
