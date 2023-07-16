<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentManagementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('document_management', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('code', 191);
			$table->string('name', 191);
			$table->string('file', 191);
			$table->date('expired_date');
			$table->text('description')->nullable();
			$table->timestamps();
			$table->string('status', 191)->nullable();
			$table->string('reminder_type', 191)->nullable();
			$table->integer('nilai')->nullable();
			$table->string('pic', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('document_management');
	}

}
